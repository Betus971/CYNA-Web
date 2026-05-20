<?php

namespace App\Controller\Admin;

use App\Entity\PromoCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class PromoCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PromoCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Code promo')
            ->setEntityLabelInPlural('Codes promo')
            ->setDefaultSort(['startsAt' => 'DESC'])
            ->setSearchFields(['code']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('active')->setLabel('Actif'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('code', 'Code');
        yield NumberField::new('percentage', 'Réduction (%)')
            ->setNumDecimals(2)
            ->setStoredAsString(true);
        yield BooleanField::new('active', 'Actif');
        yield DateTimeField::new('startsAt', 'Début')->setFormat('dd/MM/yyyy');
        yield DateTimeField::new('endsAt', 'Fin')->setFormat('dd/MM/yyyy');
        yield IntegerField::new('maxUsages', 'Usages max')->hideOnIndex();
        yield IntegerField::new('usageCount', 'Utilisé')->hideOnForm();
    }
}
