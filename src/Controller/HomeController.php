<?php

namespace App\Controller;

use App\Repository\CarouselSlideRepository;
use App\Repository\CategoryRepository;
use App\Repository\HomepageTextRepository;
use App\Repository\SaasServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Home : rendu Twig pour le site statique de prévisualisation + endpoint JSON
 * consommé par la SPA (carousel, catégories, top produits, textes éditables).
 */
final class HomeController extends AbstractController
{
    public function __construct(
        private readonly CarouselSlideRepository $slides,
        private readonly CategoryRepository $categories,
        private readonly SaasServiceRepository $services,
        private readonly HomepageTextRepository $texts,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'highlights' => $this->slides->findActiveOrdered(),
            'categories' => $this->categories->findAllOrdered(),
            'products'   => $this->services->findTopByPriority(4),
        ]);
    }

    /**
     * Endpoint JSON pour la SPA : tout ce qui est nécessaire à la home.
     */
    #[Route('/api/home', name: 'api_home', methods: ['GET'])]
    public function apiHome(): JsonResponse
    {
        return $this->json([
            'carousel'   => array_map(fn($s) => [
                'id' => $s->getId(),
                'title' => $s->getTitle(),
                'subtitle' => $s->getSubtitle(),
                'image' => $s->getImage(),
                'linkUrl' => $s->getLinkUrl(),
                'ctaLabel' => $s->getCtaLabel(),
            ], $this->slides->findActiveOrdered()),
            'categories' => array_map(fn($c) => [
                'id' => $c->getId(),
                'name' => $c->getName(),
                'image' => $c->getImage(),
                'displayOrder' => $c->getDisplayOrder(),
            ], $this->categories->findAllOrdered()),
            'topProducts' => array_map(fn($p) => [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'description' => $p->getDescription(),
                'price' => $p->getPrice(),
                'image' => $p->getImage(),
                'category' => $p->getCategory()?->getName(),
            ], $this->services->findTopByPriority(4)),
            'texts' => array_map(fn($t) => [
                'slug' => $t->getSlug(),
                'title' => $t->getTitle(),
                'body' => $t->getBody(),
            ], $this->texts->findAll()),
        ]);
    }
}
