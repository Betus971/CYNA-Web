<?php

namespace App\Controller\Admin;

use App\Entity\ChatbotConversation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class ChatbotConversationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ChatbotConversation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Conversation chatbot')
            ->setEntityLabelInPlural('Conversations chatbot')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['fullName', 'email', 'subject']);
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
            ->add(BooleanFilter::new('escalated')->setLabel('Escaladé'))
            ->add(BooleanFilter::new('handled')->setLabel('Traité'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('fullName', 'Nom');
        yield EmailField::new('email', 'Email');
        yield TextField::new('subject', 'Sujet');
        yield TextField::new('locale', 'Langue')->hideOnIndex();
        yield BooleanField::new('escalated', 'Escaladé');
        yield BooleanField::new('handled', 'Traité');
        yield TextareaField::new('question', 'Question')->hideOnIndex();
        yield TextareaField::new('answer', 'Réponse IA')->hideOnIndex();
        yield TextareaField::new('transcript', 'Transcript')->hideOnIndex();
        yield DateTimeField::new('createdAt', 'Date')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();
    }
}
