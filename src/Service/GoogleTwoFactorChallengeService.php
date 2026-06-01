<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class GoogleTwoFactorChallengeService
{
    private const TTL_SECONDS = 600;

    public function __construct(
        #[Autowire('%kernel.secret%')]
        private readonly string $secret,
    ) {
    }

    public function create(User $user): string
    {
        $payload = [
            'email' => (string) $user->getEmail(),
            'exp' => time() + self::TTL_SECONDS,
            'nonce' => bin2hex(random_bytes(16)),
        ];

        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        $signature = $this->sign($encodedPayload);

        return $encodedPayload . '.' . $signature;
    }

    public function isValid(string $challenge, string $email): bool
    {
        [$encodedPayload, $signature] = array_pad(explode('.', $challenge, 2), 2, null);
        if (!is_string($encodedPayload) || !is_string($signature) || $encodedPayload === '' || $signature === '') {
            return false;
        }

        if (!hash_equals($this->sign($encodedPayload), $signature)) {
            return false;
        }

        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        if (!is_array($payload)) {
            return false;
        }

        $payloadEmail = (string) ($payload['email'] ?? '');
        $expiresAt = (int) ($payload['exp'] ?? 0);

        return hash_equals(mb_strtolower($payloadEmail), mb_strtolower($email)) && $expiresAt >= time();
    }

    private function sign(string $encodedPayload): string
    {
        return $this->base64UrlEncode(hash_hmac('sha256', $encodedPayload, $this->secret, true));
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $padding = strlen($value) % 4;
        if ($padding > 0) {
            $value .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($value, '-_', '+/'), true) ?: '';
    }
}
