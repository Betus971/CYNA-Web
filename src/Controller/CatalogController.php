<?php

namespace App\Controller;

use App\Repository\SaasServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Recherche à facettes du catalogue (q, catégorie, fourchette de prix, tri).
 * Exposé publiquement pour que la SPA puisse filtrer sans authentification.
 */
final class CatalogController extends AbstractController
{
    public function __construct(private readonly SaasServiceRepository $services)
    {
    }

    #[Route('/api/catalog/search', name: 'catalog_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $criteria = [
            'q' => $request->query->get('q'),
            'categoryId' => $request->query->get('category') !== null ? (int) $request->query->get('category') : null,
            'minPrice' => $request->query->get('minPrice'),
            'maxPrice' => $request->query->get('maxPrice'),
            'availableOnly' => $request->query->getBoolean('availableOnly', true),
            'sort' => $request->query->get('sort', 'priority'),
            'direction' => $request->query->get('direction', 'asc'),
            'limit' => (int) $request->query->get('limit', 24),
            'offset' => (int) $request->query->get('offset', 0),
        ];

        $results = $this->services->searchFacets($criteria);
        $total = $this->services->countFacets($criteria);

        return $this->json([
            'total' => $total,
            'items' => array_map(fn($s) => [
                'id' => $s->getId(),
                'name' => $s->getName(),
                'description' => $s->getDescription(),
                'price' => $s->getPrice(),
                'image' => $s->getImage(),
                'isAvailable' => $s->isAvailable(),
                'category' => [
                    'id' => $s->getCategory()?->getId(),
                    'name' => $s->getCategory()?->getName(),
                ],
            ], $results),
        ]);
    }
}
