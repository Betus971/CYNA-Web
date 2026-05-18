<?php

namespace App\Controller\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/security/2fa', name: 'api_2fa_')]
#[IsGranted('ROLE_USER')]
class TwoFactorController extends AbstractController
{
    public function __construct(
        private readonly GoogleAuthenticatorInterface $googleAuthenticator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/setup', name: 'setup', methods: ['POST'])]
    public function setup(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->isTotpEnabled()) {
            return $this->json(['error' => '2FA already enabled'], 400);
        }

        // Generate secret if not exists
        if (!$user->getTotpSecret()) {
            $user->setTotpSecret($this->googleAuthenticator->generateSecret());
            $this->entityManager->flush();
        }

        return $this->json([
            'secret' => $user->getTotpSecret(),
            'qrCodeContent' => $this->googleAuthenticator->getQRContent($user),
        ]);
    }

    #[Route('/enable', name: 'enable', methods: ['POST'])]
    public function enable(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? '';

        if ($this->googleAuthenticator->checkCode($user, $code)) {
            $user->setTotpEnabled(true);
            $this->entityManager->flush();

            return $this->json(['success' => true]);
        }

        return $this->json(['error' => 'Invalid code'], 400);
    }

    #[Route('/disable', name: 'disable', methods: ['POST'])]
    public function disable(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        
        // Optionnel : vérifier un code avant de désactiver
        $user->setTotpEnabled(false);
        $user->setTotpSecret(null);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/test', name: 'test', methods: ['POST'])]
    public function test(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? '';

        if ($this->googleAuthenticator->checkCode($user, $code)) {
            return $this->json(['success' => true]);
        }

        return $this->json(['error' => 'Code invalide'], 400);
    }

    #[Route('/toggle-login', name: 'toggle_login', methods: ['POST'])]
    public function toggleLogin(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $enabled = (bool)($data['enabled'] ?? false);

        $user->setTotpEnabled($enabled);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'totpEnabled' => $user->isTotpEnabled()]);
    }
}
