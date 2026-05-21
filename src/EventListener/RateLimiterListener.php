<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * Applique un rate limiting par IP sur les endpoints sensibles de l'API.
 *
 * Limiteurs configurés dans config/packages/rate_limiter.yaml :
 *   - login          : 10 req / 5 min
 *   - password_forgot: 5 req / 15 min
 *   - contact        : 5 req / 10 min
 *   - chatbot        : 30 req / min
 */
#[AsEventListener(event: KernelEvents::REQUEST, priority: 16)]
final class RateLimiterListener
{
    /** @var array<string, array{limiter: RateLimiterFactory, methods: string[]}> */
    private array $routes;

    public function __construct(
        private readonly RateLimiterFactory $loginLimiter,
        private readonly RateLimiterFactory $passwordForgotLimiter,
        private readonly RateLimiterFactory $contactLimiter,
        private readonly RateLimiterFactory $chatbotLimiter,
    ) {
        $this->routes = [
            // route name => [factory, allowed methods]
            'api_login_check'           => ['limiter' => $this->loginLimiter,          'methods' => ['POST']],
            'app_password_forgot'       => ['limiter' => $this->passwordForgotLimiter, 'methods' => ['POST']],
            'api_contact_messages_post_collection' => ['limiter' => $this->contactLimiter,  'methods' => ['POST']],
            'api_chatbot_messages_post_collection' => ['limiter' => $this->chatbotLimiter,  'methods' => ['POST']],
        ];
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request   = $event->getRequest();
        $routeName = $request->attributes->get('_route', '');
        $method    = $request->getMethod();

        if (!isset($this->routes[$routeName])) {
            return;
        }

        $config = $this->routes[$routeName];
        if (!in_array($method, $config['methods'], true)) {
            return;
        }

        $ip      = $request->getClientIp() ?? 'unknown';
        $limiter = $config['limiter']->create($ip);
        $limit   = $limiter->consume(1);

        if (!$limit->isAccepted()) {
            $retryAfter = $limit->getRetryAfter()->getTimestamp() - time();
            $event->setResponse(new JsonResponse(
                ['message' => 'Trop de requêtes. Veuillez réessayer dans quelques instants.', 'retry_after' => max(0, $retryAfter)],
                Response::HTTP_TOO_MANY_REQUESTS,
                ['Retry-After' => (string) max(0, $retryAfter)],
            ));
        }
    }
}
