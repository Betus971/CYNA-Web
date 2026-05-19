<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SecurityEmailService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
        SecurityEmailService $securityEmailService
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];
        $email = (string) ($data['email'] ?? '');
        $password = (string) ($data['password'] ?? '');
        $code = (string) ($data['code'] ?? '');

        if ('' === $email || '' === $password || '' === $code) {
            return $this->json(['error' => 'Paramètres manquants.'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);
        if (null === $user) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        // Verify password first to prevent bruteforcing 2FA codes without knowing the password
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Identifiants invalides.'], 401);
        }

        if ($user->isEmailTwoFactorEnabled()) {
            if (!$securityEmailService->verifyTwoFactorCode($user, $code)) {
                return $this->json(['error' => 'Code 2FA invalide.'], 400);
            }
        } elseif (!$googleAuthenticator->checkCode($user, $code)) {
            return $this->json(['error' => 'Code 2FA invalide.'], 400);
        }

        // Generate the JWT token
        $token = $jwtManager->create($user);
        $securityEmailService->sendLoginNotification($user, $request);

        return $this->json([
            'token' => $token,
        ]);
    }
}
