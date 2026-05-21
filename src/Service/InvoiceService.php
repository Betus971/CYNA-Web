<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Crée et persiste la facture associée à une commande après paiement.
 */
final class InvoiceService
{
    private const TAX_RATE = 0.20; // 20 % TVA

    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * Génère la facture pour une commande PAID.
     * Idempotent : retourne la facture existante si elle existe déjà.
     */
    public function createForOrder(Order $order): Invoice
    {
        // Idempotence : on ne génère pas deux fois
        $existing = $this->em->getRepository(Invoice::class)->findOneBy(['order' => $order]);
        if ($existing !== null) {
            return $existing;
        }

        $totalHT = (float) $order->getTotalPrice();
        $taxAmount = round($totalHT * self::TAX_RATE, 2);

        $invoice = (new Invoice())
            ->setNumber($this->generateNumber())
            ->setOrder($order)
            ->setTotalAmount((string) $totalHT)
            ->setTaxAmount((string) $taxAmount);

        $this->em->persist($invoice);
        // flush géré par l'appelant

        return $invoice;
    }

    private function generateNumber(): string
    {
        // Format : INV-YYYY-XXXXXXXX (ex. INV-2026-00000042)
        $year = (new \DateTimeImmutable())->format('Y');
        $seq  = random_int(10_000_000, 99_999_999);

        return sprintf('INV-%s-%08d', $year, $seq);
    }
}
