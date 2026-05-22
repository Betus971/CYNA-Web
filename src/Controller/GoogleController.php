<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SecurityEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleController extends AbstractController
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly EntityManagerInterface $entityManager,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly SecurityEmailService $securityEmailService,
        private readonly CacheItemPoolInterface $cache,
        private readonly LoggerInterface $logger,
        private readonly RouterInterface $router,
        #[Autowire('%env(string:GOOGLE_CLIENT_ID)%')]
        private readonly string $googleClientId,
        #[Autowire('%env(string:GOOGLE_CLIENT_SECRET)%')]
        private readonly string $googleClientSecret,
        #[Autowire('%env(string:FRONTEND_URL)%')]
        private readonly string $frontendUrl,
    ) {
    }

    #[Route('/login/google', name: 'login_google', methods: ['GET'])]
    public function redirectToGoogle(): RedirectResponse
    {
        $query = http_build_query([
            'client_id' => $this->googleClientId,
            'redirect_uri' => $this->getBackendCallbackUrl(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'online',
            'prompt' => 'select_account',
            'state' => bin2hex(random_bytes(16)),
        ], '', '&', PHP_QUERY_RFC3986);

        return new RedirectResponse('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    #[Route('/login/google/check', name: 'login_google_check', methods: ['GET'])]
    public function check(Request $request): Response
    {
        $error = trim((string) $request->query->get('error', ''));
        $code = trim((string) $request->query->get('code', ''));

        if ($error !== '') {
            return $this->redirectToFrontendError('Connexion Google refusee.');
        }

        if ($code === '') {
            return $this->redirectToFrontendError('Le code Google est manquant.');
        }

        try {
            $user = $this->authenticateGoogleCode($code, $this->getBackendCallbackUrl());

            return $this->redirectToFrontendLoginResult($user, $request);
        } catch (ClientExceptionInterface $exception) {
            $this->logger->warning('Google OAuth rejected the authorization code.', [
                'statusCode' => $exception->getResponse()->getStatusCode(),
            ]);

            return $this->redirectToFrontendError('Le code Google est invalide ou expire.');
        } catch (RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface | \RuntimeException $exception) {
            $this->logger->error('Google OAuth communication failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return $this->redirectToFrontendError(
                'Le service d\'authentification Google est momentanement indisponible.'
            );
        } catch (\Throwable $exception) {
            $this->logger->error('Google login finalization failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return $this->redirectToFrontendError('Impossible de finaliser la connexion Google.');
        }
    }

    #[Route('/api/auth/google/callback', name: 'api_auth_google_callback', methods: ['POST'])]
    public function callback(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        $code = is_array($payload) ? trim((string) ($payload['code'] ?? '')) : '';

        if ($code === '') {
            return $this->jsonLdError('Le code Google est manquant.', Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->authenticateGoogleCode($code, $this->getFrontendCallbackUrl());

            return $this->json($this->buildLoginPayload($user, $request));
        } catch (ClientExceptionInterface $exception) {
            $this->logger->warning('Google OAuth rejected the authorization code.', [
                'statusCode' => $exception->getResponse()->getStatusCode(),
            ]);

            return $this->jsonLdError('Le code Google est invalide ou expire.', Response::HTTP_BAD_REQUEST);
        } catch (RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface | \RuntimeException $exception) {
            $this->logger->error('Google OAuth communication failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return $this->jsonLdError(
                'Le service d\'authentification Google est momentanement indisponible.',
                Response::HTTP_BAD_GATEWAY
            );
        } catch (\Throwable $exception) {
            $this->logger->error('Google login finalization failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return $this->jsonLdError(
                'Impossible de finaliser la connexion Google.',
                Response::HTTP_BAD_GATEWAY
            );
        }
    }

    /**
     * @return array<string, mixed>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function exchangeCodeForToken(string $code, string $redirectUri): array
    {
        $response = $this->httpClient->request('POST', 'https://oauth2.googleapis.com/token', [
            'timeout' => 5,
            'body' => [
                'code' => $code,
                'client_id' => $this->googleClientId,
                'client_secret' => $this->googleClientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ],
        ]);

        return $response->toArray();
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function authenticateGoogleCode(string $code, string $redirectUri): User
    {
        $tokenPayload = $this->exchangeCodeForToken($code, $redirectUri);
        $accessToken = (string) ($tokenPayload['access_token'] ?? '');

        if ($accessToken === '') {
            $this->logger->warning('Google OAuth token response did not include an access token.');

            throw new \RuntimeException('Google token response is missing an access token.');
        }

        $profile = $this->fetchGoogleProfile($accessToken);
        $email = strtolower(trim((string) ($profile['email'] ?? '')));
        $googleId = trim((string) ($profile['sub'] ?? ''));

        if ($email === '' || $googleId === '') {
            $this->logger->warning('Google OAuth profile response is missing required identifiers.', [
                'hasEmail' => $email !== '',
                'hasGoogleId' => $googleId !== '',
            ]);

            throw new \RuntimeException('Google profile response is missing required identifiers.');
        }

        return $this->findOrCreateGoogleUser($email, $googleId, $profile);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fetchGoogleProfile(string $accessToken): array
    {
        $response = $this->httpClient->request('GET', 'https://openidconnect.googleapis.com/v1/userinfo', [
            'timeout' => 5,
            'auth_bearer' => $accessToken,
        ]);

        return $response->toArray();
    }

    /**
     * @param array<string, mixed> $profile
     */
    private function findOrCreateGoogleUser(string $email, string $googleId, array $profile): User
    {
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['googleId' => $googleId])
            ?? $repository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword('');
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($this->normalizeProfileName($profile['given_name'] ?? null, 'Google'));
            $user->setLastname($this->normalizeProfileName($profile['family_name'] ?? null, 'User'));
            $user->setIsVerified(true);
            $this->entityManager->persist($user);
        }

        if ($user->getGoogleId() !== $googleId) {
            $user->setGoogleId($googleId);
        }

        if (!$user->isVerified()) {
            $user->setIsVerified(true);
        }

        $this->entityManager->flush();

        return $user;
    }

    private function normalizeProfileName(mixed $value, string $fallback): string
    {
        $name = trim((string) $value);

        return $name !== '' ? mb_substr($name, 0, 255) : $fallback;
    }

    private function getFrontendCallbackUrl(): string
    {
        return rtrim($this->frontendUrl, '/') . '/auth/google/callback';
    }

    private function getBackendCallbackUrl(): string
    {
        return $this->router->generate('login_google_check', [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function redirectToFrontendLoginResult(User $user, Request $request): RedirectResponse
    {
        $payload = $this->buildLoginPayload($user, $request);

        if (($payload['requires2fa'] ?? false) === true) {
            return new RedirectResponse($this->getFrontendCallbackUrl() . '?' . http_build_query($payload));
        }

        return new RedirectResponse($this->getFrontendCallbackUrl() . '?token=' . urlencode((string) $payload['token']));
    }

    /**
     * @return array<string, mixed>
     */
    private function buildLoginPayload(User $user, Request $request): array
    {
        if ($user->isEmailTwoFactorEnabled()) {
            $this->securityEmailService->generateAndSendTwoFactorCode($user);

            return $this->buildTwoFactorPayload($user, 'email');
        }

        if ($user->isGoogleAuthenticatorEnabled()) {
            return $this->buildTwoFactorPayload($user, 'totp');
        }

        $this->securityEmailService->sendLoginNotification($user, $request);

        return [
            'token' => $this->jwtManager->create($user),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'roles' => $user->getRoles(),
            ],
        ];
    }

    /**
     * @return array{requires2fa: bool, method: string, provider: string, email: string, challenge: string}
     */
    private function buildTwoFactorPayload(User $user, string $method): array
    {
        $challenge = bin2hex(random_bytes(32));
        $item = $this->cache->getItem('google_2fa_' . hash('sha256', $challenge));
        $item->set($user->getEmail());
        $item->expiresAfter(600);
        $this->cache->save($item);

        return [
            'requires2fa' => true,
            'method' => $method,
            'provider' => 'google',
            'email' => (string) $user->getEmail(),
            'challenge' => $challenge,
        ];
    }

    private function redirectToFrontendError(string $message): RedirectResponse
    {
        return new RedirectResponse($this->getFrontendCallbackUrl() . '?error=' . urlencode($message));
    }

    private function jsonLdError(string $detail, int $status): Response
    {
        return $this->json([
            '@context' => '/api/contexts/Error',
            '@type' => 'hydra:Error',
            'hydra:title' => Response::$statusTexts[$status] ?? 'Error',
            'hydra:description' => $detail,
        ], $status, ['Content-Type' => 'application/ld+json']);
    }
}
