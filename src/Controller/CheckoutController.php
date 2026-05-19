<?php

namespace App\Controller;

use App\Service\Checkout\CheckoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    public function __construct(private readonly CheckoutService $checkout)
    {
    }

    #[Route('/api/checkout/payment-intent', name: 'checkout_payment_intent', methods: ['POST'])]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            throw new BadRequestHttpException('Payload JSON invalide.');
        }

        $cartId = isset($payload['cartId']) ? (int) $payload['cartId'] : 0;
        $billingAddressId = $this->extractId($payload['billingAddress'] ?? null, 'adresse de facturation');
        if ($cartId <= 0) {
            throw new BadRequestHttpException('Panier requis.');
        }

        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException('Authentification requise.');
        }

        $result = $this->checkout->createPaymentIntent($user, $cartId, $billingAddressId);

        return $this->json([
            'orderId' => $result->order->getId(),
            'orderReference' => $result->order->getReference(),
            'amount' => $result->amount,
            'currency' => $result->currency,
            'clientSecret' => $result->clientSecret,
        ]);
    }

    private function extractId(mixed $value, string $label): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && preg_match('#/(\d+)$#', $value, $matches)) {
            return (int) $matches[1];
        }

        throw new BadRequestHttpException(sprintf('%s invalide.', ucfirst($label)));
    }
}
