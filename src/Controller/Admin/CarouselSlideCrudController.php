<?php

namespace App\Controller\Admin;

use App\Entity\CarouselSlide;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class CarouselSlideCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CarouselSlide::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Slide carrousel')
            ->setEntityLabelInPlural('Carrousel')
            ->setDefaultSort(['displayOrder' => 'ASC'])
            ->setSearchFields(['title', 'subtitle']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield IntegerField::new('displayOrder', 'Ordre');
        yield TextField::new('title', 'Titre');
        yield TextField::new('subtitle', 'Sous-titre')->hideOnIndex();
        yield TextField::new('image', 'Image (URL/chemin)');
        yield UrlField::new('linkUrl', 'Lien URL')->hideOnIndex();
        yield TextField::new('ctaLabel', 'Texte bouton CTA')->hideOnIndex();
        yield BooleanField::new('active', 'Actif');
    }
}
