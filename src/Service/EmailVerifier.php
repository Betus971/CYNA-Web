<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Gère l'envoi des emails transactionnels liés aux comptes :
 *  - vérification d'email à l'inscription,
 *  - lien de réinitialisation de mot de passe.
 *
 * Volontairement découplé de verify-email-bundle / reset-password-bundle
 * pour éviter une dépendance supplémentaire ; la logique est simple et
 * suffisante pour le cahier des charges CYNA.
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

        $email = (new Email())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject('CYNA — Vérifiez votre adresse email')
            ->html($this->buildVerifyBody($user, $link));

        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            // On logge mais on n'échoue pas l'inscription pour un incident mail.
            $this->logger->error('Impossible d\'envoyer l\'email de vérification.', [
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

        $email = (new Email())
            ->from($this->mailFrom)
            ->to((string) $user->getEmail())
            ->subject('CYNA — Réinitialisation de votre mot de passe')
            ->html($this->buildResetBody($user, $link));

        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->error('Impossible d\'envoyer l\'email de reset.', [
                'exception' => $e,
                'user_id' => $user->getId(),
            ]);
        }
    }

    private function buildVerifyBody(User $user, string $link): string
    {
        return sprintf(
            '<p>Bonjour %s,</p>'
            .'<p>Merci de votre inscription sur CYNA. Pour activer votre compte, cliquez sur le lien suivant :</p>'
            .'<p><a href="%s">Vérifier mon email</a></p>'
            .'<p>Ce lien est personnel et ne doit pas être partagé.</p>',
            htmlspecialchars((string) $user->getFirstname(), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($link, ENT_QUOTES, 'UTF-8')
        );
    }

    private function buildResetBody(User $user, string $link): string
    {
        return sprintf(
            '<p>Bonjour %s,</p>'
            .'<p>Une demande de réinitialisation de mot de passe a été faite pour votre compte.</p>'
            .'<p><a href="%s">Choisir un nouveau mot de passe</a></p>'
            .'<p>Si vous n\'êtes pas à l\'origine de cette demande, ignorez simplement ce message.</p>',
            htmlspecialchars((string) $user->getFirstname(), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($link, ENT_QUOTES, 'UTF-8')
        );
    }
}
