<?php

namespace App\Tests\Unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserSecurityTest extends TestCase
{
    public function testUserAlwaysKeepsRoleUser(): void
    {
        $user = (new User())->setRoles(['ROLE_ADMIN']);

        self::assertContains('ROLE_ADMIN', $user->getRoles());
        self::assertContains('ROLE_USER', $user->getRoles());
        self::assertSame(['ROLE_ADMIN', 'ROLE_USER'], array_values($user->getRoles()));
    }

    public function testGoogleAuthenticatorRequiresEnabledTotpAndSecret(): void
    {
        $user = (new User())
            ->setEmail('security@example.test')
            ->setTotpEnabled(true);

        self::assertFalse($user->isGoogleAuthenticatorEnabled());

        $user->setTotpSecret('totp-secret');

        self::assertTrue($user->isGoogleAuthenticatorEnabled());
        self::assertSame('security@example.test', $user->getGoogleAuthenticatorUsername());
        self::assertSame('totp-secret', $user->getGoogleAuthenticatorSecret());
    }

    public function testEmailTwoFactorCodeCanBeStoredHashedWithExpiration(): void
    {
        $code = '123456';
        $expiresAt = new \DateTimeImmutable('+10 minutes');
        $hash = password_hash($code, PASSWORD_DEFAULT);

        $user = (new User())
            ->setEmailTwoFactorEnabled(true)
            ->setEmailTwoFactorCodeHash($hash)
            ->setEmailTwoFactorCodeExpiresAt($expiresAt);

        self::assertTrue($user->isEmailTwoFactorEnabled());
        self::assertNotSame($code, $user->getEmailTwoFactorCodeHash());
        self::assertTrue(password_verify($code, (string) $user->getEmailTwoFactorCodeHash()));
        self::assertGreaterThan(new \DateTimeImmutable(), $user->getEmailTwoFactorCodeExpiresAt());
    }
}
