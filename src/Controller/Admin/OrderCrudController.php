<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\OrderStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['reference', 'stripePaymentIntentId']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('user')->setLabel('Utilisateur'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('reference', 'Référence');
        yield AssociationField::new('user', 'Client');
        yield NumberField::new('totalPrice', 'Total (€)')
            ->setNumDecimals(2)
            ->setStoredAsString(true);
        yield ChoiceField::new('status', 'Statut')
            ->setChoices([
                'En attente' => OrderStatus::PENDING,
                'Payée'      => OrderStatus::PAID,
                'Active'     => OrderStatus::ACTIVE,
                'Renouvelée' => OrderStatus::RENEWED,
                'Annulée'    => OrderStatus::CANCELLED,
                'Remboursée' => OrderStatus::REFUNDED,
                'Échouée'    => OrderStatus::FAILED,
            ])
            ->renderAsBadges([
                'pending'   => 'warning',
                'paid'      => 'success',
                'active'    => 'success',
                'renewed'   => 'primary',
                'cancelled' => 'danger',
                'refunded'  => 'secondary',
                'failed'    => 'danger',
            ]);
        yield DateTimeField::new('createdAt', 'Créée le')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();
        yield DateTimeField::new('paidAt', 'Payée le')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();
        yield TextField::new('stripePaymentIntentId', 'Stripe PI')
            ->hideOnIndex()
            ->hideOnForm();
    }
}
