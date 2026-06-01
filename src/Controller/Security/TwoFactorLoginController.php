<?php

namespace App\Controller\Security;

use App\Repository\UserRepository;
use App\Service\SecurityEmailService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwoFactorLoginController extends AbstractController
{
    #[Route('/api/login/2fa-verify', name: 'api_login_2fa_verify', methods: ['POST'])]
    public function verify(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        GoogleAuthenticatorInterface $googleAuthenticator,
        JWTTokenManagerInterface $jwtManager,
        SecurityEmailService $securityEmailService,
        CacheItemPoolInterface $cache
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];
        $email = (string) ($data['email'] ?? '');
        $password = (string) ($data['password'] ?? '');
        $code = (string) ($data['code'] ?? '');
        $provider = (string) ($data['provider'] ?? '');
        $challenge = (string) ($data['challenge'] ?? '');

        if ('' === $email || '' === $code || ('google' !== $provider && '' === $password) || ('google' === $provider && '' === $challenge)) {
            return $this->json(['error' => 'Parametres manquants.'], 400);
        }

        if ('google' === $provider) {
            $challengeKey = 'google_2fa_' . hash('sha256', $challenge);
            $challengeItem = $cache->getItem($challengeKey);

            if (!$challengeItem->isHit() || $challengeItem->get() !== $email) {
                return $this->json(['error' => 'Challenge Google invalide ou expire.'], 401);
            }
        }

        $user = $userRepository->findOneBy(['email' => $email]);
        if (null === $user) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        if ('google' !== $provider && !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        if ($user->isEmailTwoFactorEnabled()) {
            if (!$securityEmailService->verifyTwoFactorCode($user, $code)) {
                return $this->json(['error' => 'Code 2FA invalide.'], 400);
            }
        } elseif ($user->isTotpEnabled()) {
            if (!$googleAuthenticator->checkCode($user, $code)) {
                return $this->json(['error' => 'Code 2FA invalide.'], 400);
            }
        } else {
            return $this->json(['error' => 'Aucune double authentification active.'], 400);
        }

        $token = $jwtManager->create($user);
        $securityEmailService->sendLoginNotification($user, $request);

        if ('google' === $provider) {
            $cache->deleteItem('google_2fa_' . hash('sha256', $challenge));
        }

        return $this->json(['token' => $token]);
    }

    #[Route('/api/login/2fa-resend', name: 'api_login_2fa_resend', methods: ['POST'])]
    public function resend(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        SecurityEmailService $securityEmailService,
        CacheItemPoolInterface $cache
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];
        $email = (string) ($data['email'] ?? '');
        $password = (string) ($data['password'] ?? '');
        $provider = (string) ($data['provider'] ?? '');
        $challenge = (string) ($data['challenge'] ?? '');

        if ('' === $email || ('google' !== $provider && '' === $password) || ('google' === $provider && '' === $challenge)) {
            return $this->json(['error' => 'Parametres manquants.'], 400);
        }

        if ('google' === $provider) {
            $challengeItem = $cache->getItem('google_2fa_' . hash('sha256', $challenge));

            if (!$challengeItem->isHit() || $challengeItem->get() !== $email) {
                return $this->json(['error' => 'Challenge Google invalide ou expire.'], 401);
            }
        }

        $user = $userRepository->findOneBy(['email' => $email]);
        if (null === $user) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        if ('google' !== $provider && !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        if (!$user->isEmailTwoFactorEnabled()) {
            return $this->json(['error' => 'La double authentification par e-mail nest pas active.'], 400);
        }

        try {
            $securityEmailService->generateAndSendTwoFactorCode($user);
        } catch (\Throwable) {
            return $this->json([
                'error' => 'Impossible d envoyer le code A2F par e-mail. Verifiez que l IP actuelle est autorisee dans Brevo.',
            ], 502);
        }

        return $this->json(['success' => true]);
    }
}
