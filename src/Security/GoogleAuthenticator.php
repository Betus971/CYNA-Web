<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        private readonly ClientRegistry         $clientRegistry,
        private readonly EntityManagerInterface $em,
        private readonly RouterInterface        $router,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly string                 $frontendCallbackUrl,
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'login_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client      = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($client, $accessToken): User {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);
                $email      = $googleUser->getEmail();

                $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

                if (!$user) {
                    $user = new User();
                    $user->setEmail($email);
                    $user->setRoles(['ROLE_USER']);
                    $user->setFirstname($googleUser->getFirstName() ?? 'Google');
                    $user->setLastname($googleUser->getLastName() ?? 'User');
                    // Pas de mot de passe — compte SSO uniquement
                    $user->setPassword('');
                    $this->em->persist($user);
                    $this->em->flush();
                }

                return $user;
            })
        );
    }

    /**
     * Génère un JWT et redirige vers le frontend React avec le token en query param.
     * Ex: http://localhost:5173/auth/google/callback?token=eyJ...
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();
        $jwt  = $this->jwtManager->create($user);

        return new RedirectResponse(
            $this->frontendCallbackUrl . '?token=' . urlencode($jwt)
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $error = strtr($exception->getMessageKey(), $exception->getMessageData());

        // Redirige vers le frontend avec l'erreur
        return new RedirectResponse(
            $this->frontendCallbackUrl . '?error=' . urlencode($error)
        );
    }
}
