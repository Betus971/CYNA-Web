<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SecurityEmailService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailVerifier $emailVerifier,
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function generateAndSendTwoFactorCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user
            ->setEmailTwoFactorCodeHash(password_hash($code, PASSWORD_DEFAULT))
            ->setEmailTwoFactorCodeExpiresAt(new \DateTimeImmutable('+10 minutes'));

        $this->entityManager->flush();

        try {
            $this->emailVerifier->sendEmailTwoFactorCode($user, $code);
        } catch (\Throwable $exception) {
            $this->logger->error('Unable to send email 2FA code.', [
                'exception' => $exception,
                'user_id' => $user->getId(),
            ]);

            throw $exception;
        }
    }

    public function verifyTwoFactorCode(User $user, string $code): bool
    {
        $hash = $user->getEmailTwoFactorCodeHash();
        $expiresAt = $user->getEmailTwoFactorCodeExpiresAt();

        if (null === $hash || null === $expiresAt || $expiresAt < new \DateTimeImmutable()) {
            return false;
        }

        if (!password_verify($code, $hash)) {
            return false;
        }

        $user
            ->setEmailTwoFactorCodeHash(null)
            ->setEmailTwoFactorCodeExpiresAt(null);
        $this->entityManager->flush();

        return true;
    }

    public function sendLoginNotification(User $user, Request $request, bool $force = false): void
    {
        if (!$force && !$user->isLoginNotificationEnabled()) {
            return;
        }

        try {
            $this->emailVerifier->sendLoginNotification($user, $this->buildLoginContext($request));
        } catch (\Throwable $exception) {
            $this->logger->error('Unable to send login security notification.', [
                'exception' => $exception,
                'user_id' => $user->getId(),
            ]);
        }
    }

    /**
     * @return array{
     *     ip: string,
     *     location: string,
     *     user_agent: string,
     *     platform: string,
     *     browser: string,
     *     device: string,
     *     occurred_at: \DateTimeImmutable
     * }
     */
    public function buildLoginContext(Request $request): array
    {
        $userAgent = $request->headers->get('User-Agent', 'Inconnu');
        $ip = $request->getClientIp() ?? 'Inconnue';

        return [
            'ip' => $ip,
            'location' => $this->resolveLocation($ip),
            'user_agent' => $userAgent,
            'platform' => $this->detectPlatform($userAgent),
            'browser' => $this->detectBrowser($userAgent),
            'device' => $this->detectDevice($userAgent),
            'occurred_at' => new \DateTimeImmutable(),
        ];
    }

    private function resolveLocation(string $ip): string
    {
        if (!$this->isPublicIp($ip)) {
            return 'Locale ou inconnue';
        }

        try {
            $response = $this->httpClient->request('GET', 'https://ipwho.is/' . rawurlencode($ip), [
                'timeout' => 2,
            ]);
            $data = $response->toArray(false);

            if (($data['success'] ?? false) !== true) {
                return 'Inconnue';
            }

            $parts = array_filter([
                $data['city'] ?? null,
                $data['region'] ?? null,
                $data['country'] ?? null,
            ], static fn (mixed $value): bool => is_string($value) && trim($value) !== '');

            return $parts !== [] ? implode(', ', $parts) : 'Inconnue';
        } catch (\Throwable $exception) {
            $this->logger->warning('Unable to resolve login IP location.', [
                'exception' => $exception,
                'ip' => $ip,
            ]);

            return 'Inconnue';
        }
    }

    private function isPublicIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
    }

    private function detectPlatform(string $userAgent): string
    {
        return match (true) {
            preg_match('/Windows NT ([0-9.]+)/', $userAgent, $matches) === 1 => 'Windows NT ' . $matches[1],
            preg_match('/Android ([0-9.]+)/', $userAgent, $matches) === 1 => 'Android ' . $matches[1],
            preg_match('/iPhone OS ([0-9_]+)/', $userAgent, $matches) === 1 => 'iOS ' . str_replace('_', '.', $matches[1]),
            preg_match('/CPU OS ([0-9_]+)/', $userAgent, $matches) === 1 => 'iPadOS ' . str_replace('_', '.', $matches[1]),
            preg_match('/Mac OS X ([0-9_]+)/', $userAgent, $matches) === 1 => 'macOS ' . str_replace('_', '.', $matches[1]),
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Inconnu',
        };
    }

    private function detectBrowser(string $userAgent): string
    {
        return match (true) {
            preg_match('/Edg\/([0-9.]+)/', $userAgent, $matches) === 1 => 'Microsoft Edge ' . $matches[1],
            preg_match('/OPR\/([0-9.]+)/', $userAgent, $matches) === 1 => 'Opera ' . $matches[1],
            preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches) === 1 => 'Chrome ' . $matches[1],
            preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches) === 1 => 'Firefox ' . $matches[1],
            preg_match('/Version\/([0-9.]+).*Safari\//', $userAgent, $matches) === 1 => 'Safari ' . $matches[1],
            default => 'Inconnu',
        };
    }

    private function detectDevice(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'iPhone') => 'iPhone',
            str_contains($userAgent, 'iPad') => 'iPad',
            str_contains($userAgent, 'Android') && str_contains($userAgent, 'Mobile') => 'Telephone Android',
            str_contains($userAgent, 'Android') => 'Tablette Android',
            str_contains($userAgent, 'Mobile') => 'Mobile',
            default => 'Ordinateur',
        };
    }
}
