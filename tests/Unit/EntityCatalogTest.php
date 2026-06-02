<?php

namespace App\Tests\Unit;

use App\Entity\CarouselSlide;
use App\Entity\Category;
use App\Entity\HomepageText;
use App\Entity\PromoCode;
use App\Entity\SaasService;
use PHPUnit\Framework\TestCase;

final class EntityCatalogTest extends TestCase
{
    public function testCategoryMaintainsBidirectionalServicesRelation(): void
    {
        $category = (new Category())->setName('SOC')->setDisplayOrder(2);
        $service = (new SaasService())
            ->setName('SOC managé')
            ->setDescription('Surveillance sécurité')
            ->setPrice('299.00');

        $category->addSaasService($service);

        self::assertCount(1, $category->getSaasServices());
        self::assertSame($category, $service->getCategory());

        $category->removeSaasService($service);

        self::assertCount(0, $category->getSaasServices());
        self::assertNull($service->getCategory());
    }

    public function testPromoCodeNormalizesCodeAndChecksValidityWindow(): void
    {
        $now = new \DateTimeImmutable('2026-06-02 12:00:00');
        $promo = (new PromoCode())
            ->setCode('cyna20')
            ->setPercentage('20.00')
            ->setStartsAt($now->modify('-1 day'))
            ->setEndsAt($now->modify('+1 day'))
            ->setMaxUsages(2)
            ->setUsageCount(1);

        self::assertSame('CYNA20', $promo->getCode());
        self::assertTrue($promo->isUsable($now));

        $promo->setUsageCount(2);
        self::assertFalse($promo->isUsable($now));

        $promo->setUsageCount(0)->setActive(false);
        self::assertFalse($promo->isUsable($now));
    }

    public function testEditableHomepageEntitiesExposeExpectedDefaults(): void
    {
        $slide = (new CarouselSlide())
            ->setTitle('CYNA')
            ->setSubtitle('Cybersecurity')
            ->setImage('/images/hero.png')
            ->setLinkUrl('/services')
            ->setCtaLabel('Voir')
            ->setDisplayOrder(5);

        $text = (new HomepageText())
            ->setSlug('hero')
            ->setTitle('Sécurité')
            ->setBody('Texte éditorial');

        self::assertTrue($slide->isActive());
        self::assertSame(5, $slide->getDisplayOrder());
        self::assertSame('hero', $text->getSlug());
        self::assertSame('Texte éditorial', $text->getBody());
    }
}
