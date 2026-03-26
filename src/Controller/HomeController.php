<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $highlights = [
            ['title' => 'Offre SOC', 'image' => 'https://picsum.photos/seed/soc/1200/700'],
            ['title' => 'EDR Pro',    'image' => 'https://picsum.photos/seed/edr/1200/700'],
        ];

        $categories = [
            ['name' => 'SOC',  'image' => 'https://picsum.photos/seed/cat-soc/800/500'],
            ['name' => 'EDR',  'image' => 'https://picsum.photos/seed/cat-edr/800/500'],
            ['name' => 'XDR',  'image' => 'https://picsum.photos/seed/cat-xdr/800/500'],
            ['name' => 'Audit','image' => 'https://picsum.photos/seed/cat-audit/800/500'],
        ];

        $products = [
            ['name' => 'SOC Starter',     'price' => 'À partir de 19€/mois', 'image' => 'https://picsum.photos/seed/p1/800/500'],
            ['name' => 'EDR Standard',    'price' => 'À partir de 19€/mois', 'image' => 'https://picsum.photos/seed/p2/800/500'],
            ['name' => 'XDR Plus',        'price' => 'À partir de 19€/mois', 'image' => 'https://picsum.photos/seed/p3/800/500'],
            ['name' => 'SOC Enterprise',  'price' => 'À partir de 19€/mois', 'image' => 'https://picsum.photos/seed/p4/800/500'],
        ];

        return $this->render('home/index.html.twig', [
            'highlights' => $highlights,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
