<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InvoiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invoice::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Facture')
            ->setEntityLabelInPlural('Factures')
            ->setDefaultSort(['issuedAt' => 'DESC'])
            ->setSearchFields(['number']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('number', 'Numéro');
        yield AssociationField::new('order', 'Commande');
        yield NumberField::new('totalAmount', 'Total HT (€)')
            ->setNumDecimals(2)
            ->setStoredAsString(true);
        yield NumberField::new('taxAmount', 'TVA (€)')
            ->setNumDecimals(2)
            ->setStoredAsString(true)
            ->hideOnIndex();
        yield TextField::new('pdfPath', 'Fichier PDF')->hideOnIndex();
        yield DateTimeField::new('issuedAt', 'Émise le')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();
    }
}
