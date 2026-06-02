<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class AdminDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {}

    public function index(): Response
    {
        $now  = new \DateTimeImmutable();
        $from = new \DateTimeImmutable('first day of this month 00:00:00');
        $to   = new \DateTimeImmutable('last day of this month 23:59:59');

        return $this->render('admin/dashboard.html.twig', [
            'revenue_total'   => $this->orderRepository->totalRevenueAllTime(),
            'revenue_month'   => $this->orderRepository->revenueThisMonth(),
            'orders_paid'     => $this->orderRepository->countPaidOrders(),
            'recent_orders'   => $this->orderRepository->findRecentPaid(5),
            'current_month'   => $now->format('F Y'),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<strong>CY</strong>NA Admin')
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('messages')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-chart-line');

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Catégories', 'fa fa-folder');
        yield MenuItem::linkTo(SaasServiceCrudController::class, 'Services SaaS', 'fa fa-box');

        yield MenuItem::section('Contenu Homepage');
        yield MenuItem::linkTo(CarouselSlideCrudController::class, 'Carrousel', 'fa fa-images');
        yield MenuItem::linkTo(HomepageTextCrudController::class, 'Textes dynamiques', 'fa fa-align-left');

        yield MenuItem::section('Commandes & Facturation');
        yield MenuItem::linkTo(OrderCrudController::class, 'Commandes', 'fa fa-shopping-cart');
        yield MenuItem::linkTo(InvoiceCrudController::class, 'Factures', 'fa fa-file-invoice');
        yield MenuItem::linkTo(PromoCodeCrudController::class, 'Codes promo', 'fa fa-tag');

        yield MenuItem::section('Support');
        yield MenuItem::linkTo(ContactMessageCrudController::class, 'Messages contact', 'fa fa-envelope');
        yield MenuItem::linkTo(ChatbotConversationCrudController::class, 'Conversations chatbot', 'fa fa-robot');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fa fa-users');

        yield MenuItem::section('');
        yield MenuItem::linkToUrl('API Docs', 'fa fa-code', '/api/docs')->setLinkTarget('_blank');
        yield MenuItem::linkToRoute('Déconnexion', 'fa fa-sign-out-alt', 'admin_logout');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addHtmlContentToHead('
            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  crossorigin="anonymous" referrerpolicy="no-referrer" />
        ');
    }
}
