<?php

namespace App\Tests\Unit;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\CarouselSlide;
use App\Entity\ContactMessage;
use App\Entity\HomepageText;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Entity\PaymentMethod;
use App\Entity\PromoCode;
use App\Entity\SaasService;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class EntityCommerceTest extends TestCase
{
    public function testCartGeneratesTokenAndMaintainsItemsRelation(): void
    {
        $cart = new Cart();
        $item = (new CartItem())
            ->setSaasService(new SaasService())
            ->setQuantity(2)
            ->setDurationMonths(12);

        $cart->addItem($item);

        self::assertNotEmpty($cart->getToken());
        self::assertSame(32, strlen((string) $cart->getToken()));
        self::assertSame($cart, $item->getCart());

        $cart->removeItem($item);

        self::assertNull($item->getCart());
    }

    public function testAddressMaintainsOrdersCollectionFromOwningSideHelper(): void
    {
        $address = (new Address())
            ->setFirstname('Julia')
            ->setLastname('Test')
            ->setAdresse1('1 rue CYNA')
            ->setCity('Paris')
            ->setRegion('IDF')
            ->setZipCode('75001')
            ->setCountry('France')
            ->setMobilephone('0600000000');

        self::assertSame('Paris', $address->getCity());
        self::assertCount(0, $address->getOrders());

        $order = new Order();
        $address->addOrder($order);

        self::assertCount(1, $address->getOrders());
        self::assertSame($address, $order->getBillingAddress());

        $address->removeOrder($order);

        self::assertCount(0, $address->getOrders());
        self::assertNull($order->getBillingAddress());
    }

    public function testPaymentMethodStoresOnlyProviderMetadata(): void
    {
        $user = (new User())->setEmail('billing@example.test')->setPassword('');
        $method = (new PaymentMethod())
            ->setUser($user)
            ->setProvider('stripe')
            ->setProviderToken('pm_123')
            ->setBrand('visa')
            ->setLast4('4242')
            ->setExpMonth(12)
            ->setExpYear(2030)
            ->setIsDefault(true);

        self::assertSame($user, $method->getUser());
        self::assertSame('pm_123', $method->getProviderToken());
        self::assertTrue($method->isDefault());
    }

    public function testInvoiceAndContactMessageDefaults(): void
    {
        $invoice = (new Invoice())
            ->setNumber('INV-2026-00000001')
            ->setTotalAmount('100.00')
            ->setTaxAmount('20.00');

        $message = (new ContactMessage())
            ->setFullName('Client CYNA')
            ->setEmail('client@example.test')
            ->setSubject('Support')
            ->setMessage('Message suffisamment long');

        self::assertInstanceOf(\DateTimeImmutable::class, $invoice->getIssuedAt());
        self::assertFalse($message->isHandled());
        self::assertInstanceOf(\DateTimeImmutable::class, $message->getCreatedAt());
    }

    public function testPromoCodeNormalizesCodeAndEvaluatesUsabilityWindow(): void
    {
        $now = new \DateTimeImmutable('2026-06-02 12:00:00');
        $promo = (new PromoCode())
            ->setCode('summer25')
            ->setPercentage('25.00')
            ->setStartsAt($now->modify('-1 day'))
            ->setEndsAt($now->modify('+1 day'))
            ->setMaxUsages(3)
            ->setUsageCount(2);

        self::assertSame('SUMMER25', $promo->getCode());
        self::assertSame('25.00', $promo->getPercentage());
        self::assertTrue($promo->isUsable($now));

        $promo->setActive(false);
        self::assertFalse($promo->isUsable($now));

        $promo->setActive(true)->setUsageCount(3);
        self::assertFalse($promo->isUsable($now));

        $promo->setUsageCount(0)->setStartsAt($now->modify('+1 minute'));
        self::assertFalse($promo->isUsable($now));

        $promo->setStartsAt(null)->setEndsAt($now->modify('-1 minute'));
        self::assertFalse($promo->isUsable($now));
    }

    public function testHomepageEditorialEntitiesStoreTheirFields(): void
    {
        $slide = (new CarouselSlide())
            ->setTitle('Protection continue')
            ->setSubtitle('SOC managé')
            ->setImage('/images/soc.jpg')
            ->setLinkUrl('/services/soc')
            ->setCtaLabel('Découvrir')
            ->setDisplayOrder(4)
            ->setActive(false);

        $text = (new HomepageText())
            ->setSlug('hero')
            ->setTitle('CYNA')
            ->setBody('Texte de présentation de la page accueil.');

        self::assertSame('Protection continue', $slide->getTitle());
        self::assertSame('SOC managé', $slide->getSubtitle());
        self::assertSame('/images/soc.jpg', $slide->getImage());
        self::assertSame('/services/soc', $slide->getLinkUrl());
        self::assertSame('Découvrir', $slide->getCtaLabel());
        self::assertSame(4, $slide->getDisplayOrder());
        self::assertFalse($slide->isActive());

        self::assertSame('hero', $text->getSlug());
        self::assertSame('CYNA', $text->getTitle());
        self::assertSame('Texte de présentation de la page accueil.', $text->getBody());
    }
}
