<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Handles account-related transactional emails.
 */
final class EmailVerifier
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        #[Autowire(param: 'app.frontend_url')]
        private readonly string $frontendUrl,
        #[Autowire(param: 'app.mail_from')]
        private readonly string $mailFrom,
    ) {
    }

    public function sendVerificationEmail(User $user): void
    {
        $token = $user->getEmailVerificationToken();
        if (null === $token) {
            return;
        }

        $link = sprintf(
            '%s/verify-email?token=%s&email=%s',
            rtrim($this->frontendUrl, '/'),
            $token,
            urlencode((string) $user->getEmail())
        );

        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject('CYNA - Verification de votre adresse email')
            ->htmlTemplate('emails/account_confirmation.html.twig')
            ->context([
                'user' => $user,
                'verification_link' => $link,
            ]);

        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->error('Unable to send verification email.', [
                'exception' => $e,
                'user_id' => $user->getId(),
            ]);
        }
    }

    public function sendPasswordResetEmail(User $user): void
    {
        $token = $user->getPasswordResetToken();
        if (null === $token) {
            return;
        }

        $link = sprintf(
            '%s/reset-password?token=%s',
            rtrim($this->frontendUrl, '/'),
            $token
        );

        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject('CYNA - Reinitialisation de votre mot de passe')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context([
                'user' => $user,
                'reset_link' => $link,
            ]);

        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->error('Unable to send password reset email.', [
                'exception' => $e,
                'user_id' => $user->getId(),
            ]);
        }
    }
}
