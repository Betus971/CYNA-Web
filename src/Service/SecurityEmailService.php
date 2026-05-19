<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class SecurityEmailService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailVerifier $emailVerifier,
    ) {
    }

    public function generateAndSendTwoFactorCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user
            ->setEmailTwoFactorCodeHash(password_hash($code, PASSWORD_DEFAULT))
            ->setEmailTwoFactorCodeExpiresAt(new \DateTimeImmutable('+10 minutes'));

        $this->entityManager->flush();
        $this->emailVerifier->sendEmailTwoFactorCode($user, $code);
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
        } catch (\Throwable) {
            // Login must not fail because a security notification email failed.
        }
    }

    /**
     * @return array{ip: string, user_agent: string, platform: string, browser: string, occurred_at: \DateTimeImmutable}
     */
    public function buildLoginContext(Request $request): array
    {
        $userAgent = $request->headers->get('User-Agent', 'Inconnu');

        return [
            'ip' => $request->getClientIp() ?? 'Inconnue',
            'user_agent' => $userAgent,
            'platform' => $this->detectPlatform($userAgent),
            'browser' => $this->detectBrowser($userAgent),
            'occurred_at' => new \DateTimeImmutable(),
        ];
    }

    private function detectPlatform(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Inconnu',
        };
    }

    private function detectBrowser(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Microsoft Edge',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') => 'Safari',
            default => 'Inconnu',
        };
    }
}
