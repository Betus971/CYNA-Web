<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\EmailVerifier;
use App\Service\InvoicePdfService;
use App\Service\SecurityEmailService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Twig\Environment;

final class SecurityEmailServiceTest extends TestCase
{
    public function testVerifyTwoFactorCodeClearsCodeOnSuccess(): void
    {
        $user = (new User())
            ->setEmailTwoFactorCodeHash(password_hash('123456', PASSWORD_DEFAULT))
            ->setEmailTwoFactorCodeExpiresAt(new \DateTimeImmutable('+10 minutes'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('flush');

        $service = new SecurityEmailService(
            $entityManager,
            $this->createEmailVerifier(),
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class),
        );

        self::assertTrue($service->verifyTwoFactorCode($user, '123456'));
        self::assertNull($user->getEmailTwoFactorCodeHash());
        self::assertNull($user->getEmailTwoFactorCodeExpiresAt());
    }

    public function testVerifyTwoFactorCodeRejectsExpiredOrWrongCode(): void
    {
        $user = (new User())
            ->setEmailTwoFactorCodeHash(password_hash('123456', PASSWORD_DEFAULT))
            ->setEmailTwoFactorCodeExpiresAt(new \DateTimeImmutable('-1 minute'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('flush');

        $service = new SecurityEmailService(
            $entityManager,
            $this->createEmailVerifier(),
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class),
        );

        self::assertFalse($service->verifyTwoFactorCode($user, '123456'));
    }

    public function testBuildLoginContextDetectsBrowserPlatformDeviceAndLocation(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('toArray')->willReturn([
            'success' => true,
            'city' => 'Paris',
            'region' => 'Ile-de-France',
            'country' => 'France',
        ]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects(self::once())
            ->method('request')
            ->with('GET', 'https://ipwho.is/8.8.8.8', self::anything())
            ->willReturn($response);

        $service = new SecurityEmailService(
            $this->createStub(EntityManagerInterface::class),
            $this->createEmailVerifier(),
            $httpClient,
            $this->createStub(LoggerInterface::class),
        );

        $request = Request::create('/', server: [
            'REMOTE_ADDR' => '8.8.8.8',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/125.0.0.0 Safari/537.36',
        ]);

        $context = $service->buildLoginContext($request);

        self::assertSame('8.8.8.8', $context['ip']);
        self::assertSame('Paris, Ile-de-France, France', $context['location']);
        self::assertSame('Windows NT 10.0', $context['platform']);
        self::assertSame('Chrome 125.0.0.0', $context['browser']);
        self::assertSame('Ordinateur', $context['device']);
    }

    private function createEmailVerifier(): EmailVerifier
    {
        return new EmailVerifier(
            $this->createStub(MailerInterface::class),
            $this->createStub(LoggerInterface::class),
            new InvoicePdfService($this->createStub(Environment::class), sys_get_temp_dir(), dirname(__DIR__, 2)),
            'http://localhost:5173',
            'no-reply@example.test',
        );
    }
}
