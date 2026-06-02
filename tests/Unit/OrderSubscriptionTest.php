<?php

namespace App\Tests\Unit;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Enum\SubscriptionStatus;
use PHPUnit\Framework\TestCase;

final class OrderSubscriptionTest extends TestCase
{
    public function testOrderStartsPendingAndRequiresAtLeastOneItemForBusinessValidity(): void
    {
        $order = new Order();

        self::assertSame(OrderStatus::PENDING, $order->getStatus());
        self::assertCount(0, $order->getItems());
    }

    public function testPaymentActivationSetsSubscriptionWindowOnItems(): void
    {
        $startsAt = new \DateTimeImmutable('2026-06-02 10:00:00');
        $item = (new OrderItem())
            ->setDurationMonths(12)
            ->setSubscriptionStartsAt($startsAt);

        $order = (new Order())
            ->setReference('CYNA-TEST-001')
            ->setTotalPrice('1200.00')
            ->addItem($item);

        $order
            ->setStatus(OrderStatus::PAID)
            ->setPaidAt($startsAt)
            ->setStripePaymentStatus('succeeded')
            ->setPaymentFailureReason(null);

        foreach ($order->getItems() as $orderItem) {
            $subscriptionStart = $orderItem->getSubscriptionStartsAt() ?? $startsAt;
            $orderItem
                ->setSubscriptionStartsAt($subscriptionStart)
                ->setSubscriptionEndsAt($subscriptionStart->modify(sprintf('+%d months', $orderItem->getDurationMonths())))
                ->setSubscriptionStatus(SubscriptionStatus::ACTIVE);
        }

        self::assertSame(OrderStatus::PAID, $order->getStatus());
        self::assertSame($startsAt, $order->getPaidAt());
        self::assertSame(SubscriptionStatus::ACTIVE, $item->getSubscriptionStatus());
        self::assertEquals(new \DateTimeImmutable('2027-06-02 10:00:00'), $item->getSubscriptionEndsAt());
    }
}
