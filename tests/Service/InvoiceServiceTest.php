<?php

namespace App\Tests\Service;

use App\Entity\Invoice;
use App\Entity\Order;
use App\Service\InvoiceService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

final class InvoiceServiceTest extends TestCase
{
    public function testCreateForOrderReturnsExistingInvoiceWhenPresent(): void
    {
        $order = (new Order())->setTotalPrice('100.00');
        $existing = (new Invoice())->setNumber('INV-EXISTING')->setOrder($order)->setTotalAmount('100.00')->setTaxAmount('20.00');

        $repository = $this->createStub(EntityRepository::class);
        $repository->method('findOneBy')->willReturn($existing);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(Invoice::class)->willReturn($repository);
        $em->expects(self::never())->method('persist');

        self::assertSame($existing, (new InvoiceService($em))->createForOrder($order));
    }

    public function testCreateForOrderPersistsNewInvoiceWithTax(): void
    {
        $order = (new Order())->setTotalPrice('150.00');

        $repository = $this->createStub(EntityRepository::class);
        $repository->method('findOneBy')->willReturn(null);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(Invoice::class)->willReturn($repository);
        $em->expects(self::once())->method('persist')->with(self::isInstanceOf(Invoice::class));

        $invoice = (new InvoiceService($em))->createForOrder($order);

        self::assertSame($order, $invoice->getOrder());
        self::assertSame('150', $invoice->getTotalAmount());
        self::assertSame('30', $invoice->getTaxAmount());
        self::assertStringStartsWith('INV-', (string) $invoice->getNumber());
    }
}
