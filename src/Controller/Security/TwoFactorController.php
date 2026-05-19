<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Service\SecurityEmailService;
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
        private readonly EntityManagerInterface $entityManager,
        private readonly SecurityEmailService $securityEmailService,
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

    #[Route('/email/setup', name: 'email_setup', methods: ['POST'])]
    public function setupEmailTwoFactor(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->securityEmailService->generateAndSendTwoFactorCode($user);

        return $this->json(['success' => true]);
    }

    #[Route('/email/enable', name: 'email_enable', methods: ['POST'])]
    public function enableEmailTwoFactor(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true) ?? [];
        $code = (string) ($data['code'] ?? '');

        if (!$this->securityEmailService->verifyTwoFactorCode($user, $code)) {
            return $this->json(['error' => 'Code invalide'], 400);
        }

        $user->setEmailTwoFactorEnabled(true);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'emailTwoFactorEnabled' => true]);
    }

    #[Route('/email/disable', name: 'email_disable', methods: ['POST'])]
    public function disableEmailTwoFactor(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $user
            ->setEmailTwoFactorEnabled(false)
            ->setEmailTwoFactorCodeHash(null)
            ->setEmailTwoFactorCodeExpiresAt(null);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'emailTwoFactorEnabled' => false]);
    }

    #[Route('/login-notifications/toggle', name: 'login_notifications_toggle', methods: ['POST'])]
    public function toggleLoginNotifications(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true) ?? [];
        $enabled = (bool) ($data['enabled'] ?? false);

        $user->setLoginNotificationEnabled($enabled);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'loginNotificationEnabled' => $enabled]);
    }

    #[Route('/login-notifications/test', name: 'login_notifications_test', methods: ['POST'])]
    public function testLoginNotification(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->securityEmailService->sendLoginNotification($user, $request, true);

        return $this->json(['success' => true]);
    }
}
