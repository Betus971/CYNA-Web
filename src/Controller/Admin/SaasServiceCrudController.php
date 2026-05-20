<?php

namespace App\Controller\Admin;

use App\Entity\SaasService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class SaasServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SaasService::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Service SaaS')
            ->setEntityLabelInPlural('Services SaaS')
            ->setDefaultSort(['priority' => 'ASC'])
            ->setSearchFields(['name', 'description']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isAvailable')->setLabel('Disponible'))
            ->add(EntityFilter::new('category')->setLabel('Catégorie'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Nom');
        yield AssociationField::new('category', 'Catégorie');
        yield NumberField::new('price', 'Prix (€/mois)')
            ->setNumDecimals(2)
            ->setStoredAsString(true);
        yield BooleanField::new('isAvailable', 'Disponible');
        yield IntegerField::new('priority', 'Priorité (1 = en avant)');
        yield TextField::new('image', 'Image')->hideOnIndex();
        yield TextareaField::new('description', 'Description')->hideOnIndex();
        yield TextareaField::new('technicalSpecs', 'Specs techniques')->hideOnIndex();
    }
}
