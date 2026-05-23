<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\SecurityEmailService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccessResponse')]
class AuthenticationSuccessListener
{
    public function __construct(
        private readonly SecurityEmailService $securityEmailService,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        if ($user->isEmailTwoFactorEnabled()) {
            try {
                $this->securityEmailService->generateAndSendTwoFactorCode($user);
            } catch (\Throwable) {
                $event->setData([
                    'error' => 'Impossible d envoyer le code A2F par e-mail. Verifiez que l IP actuelle est autorisee dans Brevo.',
                ]);

                return;
            }

            $event->setData([
                'requires2fa' => true,
                'method' => 'email',
                'email' => $user->getEmail(),
            ]);

            return;
        }

        // If 2FA is active and required at login for this user
        if ($user->isGoogleAuthenticatorEnabled()) {
            $event->setData([
                'requires2fa' => true,
                'method' => 'totp',
                'email' => $user->getEmail(),
            ]);

            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request) {
            $this->securityEmailService->sendLoginNotification($user, $request);
        }
    }
}
