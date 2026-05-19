<?php

namespace App\Service\Checkout;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

final class CheckoutService
{
    public function __construct(
        private readonly CartRepository $carts,
        private readonly AddressRepository $addresses,
        private readonly EntityManagerInterface $em,
        #[Autowire('%env(string:STRIPE_SECRET_KEY)%')]
        private readonly string $stripeSecretKey,
        #[Autowire('%env(default:app.stripe_currency:STRIPE_CURRENCY)%')]
        private readonly string $currency,
    ) {
    }

    public function createPaymentIntent(UserInterface $user, int $cartId, int $billingAddressId): CheckoutPaymentIntentResult
    {
        if (!$user instanceof User) {
            throw new AccessDeniedHttpException('Utilisateur invalide.');
        }

        /** @var Cart|null $cart */
        $cart = $this->carts->find($cartId);
        if (!$cart) {
            throw new NotFoundHttpException('Panier introuvable.');
        }

        if ($cart->getUser()?->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Ce panier ne vous appartient pas.');
        }

        /** @var Address|null $billingAddress */
        $billingAddress = $this->addresses->find($billingAddressId);
        if (!$billingAddress) {
            throw new NotFoundHttpException('Adresse de facturation introuvable.');
        }

        if ($billingAddress->getUser()?->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Cette adresse ne vous appartient pas.');
        }

        if ($cart->getItems()->isEmpty()) {
            throw new BadRequestHttpException('Votre panier est vide.');
        }

        $order = (new Order())
            ->setReference($this->generateReference())
            ->setUser($user)
            ->setBillingAddress($billingAddress)
            ->setTotalPrice('0.00')
            ->setStripePaymentStatus('requires_payment_method');

        $totalCents = 0;
        foreach ($cart->getItems() as $cartItem) {
            $service = $cartItem->getSaasService();
            if (!$service || !$service->isAvailable()) {
                throw new BadRequestHttpException('Un produit du panier est indisponible.');
            }

            $quantity = $cartItem->getQuantity();
            $durationMonths = $cartItem->getDurationMonths();
            if ($quantity < 1 || $durationMonths < 1) {
                throw new BadRequestHttpException('Quantite ou duree invalide.');
            }

            $unitCents = $this->decimalToCents($service->getPrice());
            $totalCents += $unitCents * $quantity * $durationMonths;

            $order->addItem(
                (new OrderItem())
                    ->setSaasService($service)
                    ->setProductNameSnapshot($service->getName())
                    ->setUnitPriceSnapshot($service->getPrice())
                    ->setQuantity($quantity)
                    ->setDurationMonths($durationMonths)
            );
        }

        if ($totalCents <= 0) {
            throw new BadRequestHttpException('Le total de commande est invalide.');
        }

        $order->setTotalPrice($this->centsToDecimal($totalCents));

        $this->em->persist($order);
        $this->em->flush();

        Stripe::setApiKey($this->stripeSecretKey);
        $intent = PaymentIntent::create([
            'amount' => $totalCents,
            'currency' => strtolower($this->currency),
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'order_id' => (string) $order->getId(),
                'user_id' => (string) $user->getId(),
                'order_reference' => (string) $order->getReference(),
            ],
        ]);

        $order
            ->setStripePaymentIntentId($intent->id)
            ->setStripePaymentStatus($intent->status);

        $this->em->flush();

        if (!$intent->client_secret) {
            throw new BadRequestHttpException('Stripe n\'a pas retourne de secret client.');
        }

        return new CheckoutPaymentIntentResult($order, $intent->client_secret, $totalCents, strtolower($this->currency));
    }

    private function generateReference(): string
    {
        return 'CYNA-' . (new \DateTimeImmutable())->format('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    private function decimalToCents(?string $amount): int
    {
        if ($amount === null || !is_numeric($amount)) {
            throw new BadRequestHttpException('Prix produit invalide.');
        }

        return (int) round(((float) $amount) * 100);
    }

    private function centsToDecimal(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }
}
