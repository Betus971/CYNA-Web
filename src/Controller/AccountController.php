<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Endpoints publics liés au cycle de vie du compte :
 *  - vérification d'email,
 *  - demande de mot de passe oublié,
 *  - réinitialisation du mot de passe.
 *
 * Tout est stateless et compatible SPA.
 */
#[Route('/api', name: 'account_')]
final class AccountController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EmailVerifier $emailVerifier,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/verify-email', name: 'verify_email', methods: ['POST'])]
    public function verifyEmail(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $token = (string) ($payload['token'] ?? '');
        $email = (string) ($payload['email'] ?? '');

        if ('' === $token || '' === $email) {
            return $this->json(['error' => 'Token ou email manquant.'], 400);
        }

        $user = $this->users->findOneBy(['email' => $email]);
        if (null === $user || $user->getEmailVerificationToken() !== $token) {
            return $this->json(['error' => 'Lien invalide ou expiré.'], 400);
        }

        $sentAt = $user->getEmailVerificationSentAt();
        if (null !== $sentAt && $sentAt < new \DateTimeImmutable('-7 days')) {
            return $this->json(['error' => 'Lien expiré.'], 400);
        }

        $user->setIsVerified(true);
        $user->setEmailVerificationToken(null);
        $user->setEmailVerificationSentAt(null);
        $this->em->flush();

        return $this->json(['status' => 'verified']);
    }

    #[Route('/password/forgot', name: 'password_forgot', methods: ['POST'])]
    public function forgotPassword(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $email = (string) ($payload['email'] ?? '');

        $violations = $this->validator->validate($email, [
            new Assert\NotBlank(),
            new Assert\Email(),
        ]);
        if (count($violations) > 0) {
            return $this->json(['error' => 'Email invalide.'], 400);
        }

        $user = $this->users->findOneBy(['email' => $email]);
        // Réponse identique qu'on trouve ou non l'utilisateur : on ne divulgue pas
        // l'existence du compte (bonne pratique RGPD / sécurité).
        if (null !== $user) {
            $user->setPasswordResetToken(bin2hex(random_bytes(32)));
            $user->setPasswordResetExpiresAt(new \DateTimeImmutable('+1 hour'));
            $this->em->flush();

            $this->emailVerifier->sendPasswordResetEmail($user);
        }

        return $this->json(['status' => 'ok']);
    }

    #[Route('/password/reset', name: 'password_reset', methods: ['POST'])]
    public function resetPassword(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $token = (string) ($payload['token'] ?? '');
        $newPassword = (string) ($payload['password'] ?? '');

        if ('' === $token || '' === $newPassword) {
            return $this->json(['error' => 'Paramètres manquants.'], 400);
        }

        $violations = $this->validator->validate($newPassword, [
            new Assert\Length(min: 12),
            new Assert\Regex(pattern: '/[A-Z]/'),
            new Assert\Regex(pattern: '/[a-z]/'),
            new Assert\Regex(pattern: '/[0-9]/'),
            new Assert\Regex(pattern: '/[^A-Za-z0-9]/'),
        ]);
        if (count($violations) > 0) {
            return $this->json(['error' => 'Mot de passe trop faible.'], 400);
        }

        $user = $this->users->findOneBy(['passwordResetToken' => $token]);
        if (null === $user) {
            return $this->json(['error' => 'Lien invalide.'], 400);
        }

        $expiresAt = $user->getPasswordResetExpiresAt();
        if (null === $expiresAt || $expiresAt < new \DateTimeImmutable()) {
            return $this->json(['error' => 'Lien expiré.'], 400);
        }

        $user->setPassword($this->hasher->hashPassword($user, $newPassword));
        $user->setPasswordResetToken(null);
        $user->setPasswordResetExpiresAt(null);
        $this->em->flush();

        return $this->json(['status' => 'ok']);
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (null === $user) {
            return $this->json(['error' => 'Not authenticated.'], 401);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'roles' => $user->getRoles(),
            'isVerified' => $user->isVerified(),
            'totpEnabled' => $user->isTotpEnabled(),
        ]);
    }
}
