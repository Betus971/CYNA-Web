<?php

namespace App\Controller\Admin;

use App\Entity\HomepageText;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomepageTextCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomepageText::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Texte dynamique')
            ->setEntityLabelInPlural('Textes dynamiques')
            ->setDefaultSort(['slug' => 'ASC'])
            ->setSearchFields(['slug', 'title']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('slug', 'Slug (identifiant)');
        yield TextField::new('title', 'Titre');
        yield TextareaField::new('body', 'Contenu')->hideOnIndex();
    }
}
