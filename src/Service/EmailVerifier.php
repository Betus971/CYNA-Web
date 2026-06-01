<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\Order;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

/**
 * Handles account-related transactional emails.
 */
final class EmailVerifier
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly InvoicePdfService $invoicePdfService,
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

    public function sendTestEmail(User $user, string $recipient): void
    {
        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to($recipient)
            ->subject('CYNA - Test Brevo transactionnel')
            ->htmlTemplate('emails/mail_test.html.twig')
            ->context([
                'user' => $user,
                'recipient' => $recipient,
                'sent_at' => new \DateTimeImmutable(),
            ]);

        $this->mailer->send($email);
    }

    public function sendEmailTwoFactorCode(User $user, string $code): void
    {
        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject('CYNA - Code de securite')
            ->htmlTemplate('emails/security_two_factor_code.html.twig')
            ->context([
                'user' => $user,
                'code' => $code,
                'expires_in_minutes' => 10,
            ]);

        $this->mailer->send($email);
    }

    public function sendOrderConfirmation(User $user, Order $order, Invoice $invoice): void
    {
        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject(sprintf('CYNA - Confirmation de votre commande %s', $order->getReference()))
            ->htmlTemplate('emails/order_confirmation.html.twig')
            ->context([
                'user'         => $user,
                'order'        => $order,
                'invoice'      => $invoice,
                'frontend_url' => rtrim($this->frontendUrl, '/'),
            ]);

        // Génération et attachement du PDF de facture
        try {
            $pdfPath = $this->invoicePdfService->generate($invoice);
            $email->addPart(new DataPart(
                new File($pdfPath),
                sprintf('facture-%s.pdf', $invoice->getNumber()),
                'application/pdf'
            ));
        } catch (\Throwable $e) {
            $this->logger->warning('Unable to attach invoice PDF to order confirmation email.', [
                'exception'  => $e,
                'invoice_id' => $invoice->getId(),
            ]);
        }

        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->error('Unable to send order confirmation email.', [
                'exception' => $e,
                'order_id'  => $order->getId(),
            ]);
        }
    }

    /**
     * @param array{ip: string, location: string, user_agent: string, platform: string, browser: string, device: string, occurred_at: \DateTimeImmutable} $context
     */
    public function sendLoginNotification(User $user, array $context): void
    {
        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject('CYNA - Nouvelle connexion a votre compte')
            ->htmlTemplate('emails/security_login_notification.html.twig')
            ->context([
                'user' => $user,
                'login' => $context,
            ]);

        $this->mailer->send($email);
    }
}
