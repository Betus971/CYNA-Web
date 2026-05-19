<?php

namespace App\Service\Checkout;

use App\Entity\Order;

final readonly class CheckoutPaymentIntentResult
{
    public function __construct(
        public Order $order,
        public string $clientSecret,
        public int $amount,
        public string $currency,
    ) {
    }
}
