<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\GoogleTwoFactorChallengeService;
use PHPUnit\Framework\TestCase;

final class GoogleTwoFactorChallengeServiceTest extends TestCase
{
    public function testChallengeIsValidForMatchingEmailWithinTtl(): void
    {
        $service = new GoogleTwoFactorChallengeService('kernel-secret-for-tests');
        $user = (new User())->setEmail('User@Example.test');

        $challenge = $service->create($user);

        self::assertTrue($service->isValid($challenge, 'user@example.test'));
        self::assertStringContainsString('.', $challenge);
    }

    public function testChallengeRejectsWrongEmailAndTamperedPayload(): void
    {
        $service = new GoogleTwoFactorChallengeService('kernel-secret-for-tests');
        $challenge = $service->create((new User())->setEmail('user@example.test'));

        self::assertFalse($service->isValid($challenge, 'attacker@example.test'));
        self::assertFalse($service->isValid($challenge . 'tampered', 'user@example.test'));
    }

    public function testExpiredChallengeIsRejected(): void
    {
        $service = new GoogleTwoFactorChallengeService('kernel-secret-for-tests');
        $encodedPayload = $this->base64UrlEncode(json_encode([
            'email' => 'user@example.test',
            'exp' => time() - 1,
            'nonce' => 'fixed-nonce',
        ], JSON_THROW_ON_ERROR));
        $signature = $this->base64UrlEncode(hash_hmac('sha256', $encodedPayload, 'kernel-secret-for-tests', true));

        self::assertFalse($service->isValid($encodedPayload . '.' . $signature, 'user@example.test'));
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
