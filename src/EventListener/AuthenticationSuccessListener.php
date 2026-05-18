<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccessResponse')]
class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        // If 2FA is active and required at login for this user
        if ($user->isTotpEnabled() && $user->getTotpSecret() !== null) {
            // Override the default data (which would contain the JWT 'token')
            $event->setData([
                'requires2fa' => true,
                'email' => $user->getEmail(),
            ]);
        }
    }
}
