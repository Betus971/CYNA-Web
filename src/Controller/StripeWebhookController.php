<?php

namespace App\Controller;

use App\Enum\OrderStatus;
use App\Enum\SubscriptionStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StripeWebhookController extends AbstractController
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly EntityManagerInterface $em,
        #[Autowire('%env(string:STRIPE_WEBHOOK_SECRET)%')]
        private readonly string $webhookSecret,
    ) {
    }

    #[Route('/api/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->headers->get('Stripe-Signature', '');

        try {
            $event = Webhook::constructEvent($payload, $signature, $this->webhookSecret);
        } catch (\UnexpectedValueException | SignatureVerificationException) {
            return $this->json(['message' => 'Signature Stripe invalide.'], Response::HTTP_BAD_REQUEST);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $this->markPaid($event);
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $this->markFailed($event);
        }

        return $this->json(['received' => true]);
    }

    private function markPaid(Event $event): void
    {
        $intent = $event->data->object;
        if (!$intent instanceof PaymentIntent) {
            return;
        }

        $order = $this->orders->findOneBy(['stripePaymentIntentId' => $intent->id]);
        if (!$order || $order->getStatus() === OrderStatus::PAID) {
            return;
        }

        $now = new \DateTimeImmutable();
        $order
            ->setStatus(OrderStatus::PAID)
            ->setPaidAt($now)
            ->setStripePaymentStatus($intent->status)
            ->setPaymentFailureReason(null);

        foreach ($order->getItems() as $item) {
            $startsAt = $item->getSubscriptionStartsAt() ?? $now;
            $item
                ->setSubscriptionStartsAt($startsAt)
                ->setSubscriptionEndsAt($startsAt->modify(sprintf('+%d months', $item->getDurationMonths())))
                ->setSubscriptionStatus(SubscriptionStatus::ACTIVE);
        }

        $this->em->flush();
    }

    private function markFailed(Event $event): void
    {
        $intent = $event->data->object;
        if (!$intent instanceof PaymentIntent) {
            return;
        }

        $order = $this->orders->findOneBy(['stripePaymentIntentId' => $intent->id]);
        if (!$order || $order->getStatus() === OrderStatus::PAID) {
            return;
        }

        $message = $intent->last_payment_error?->message;
        $order
            ->setStatus(OrderStatus::FAILED)
            ->setStripePaymentStatus($intent->status)
            ->setPaymentFailureReason($message ? mb_substr($message, 0, 500) : 'Paiement refuse.');

        $this->em->flush();
    }
}
