<?php

namespace App\Controller;

use App\Service\GeoApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Proxy vers l'API Géo du gouvernement français (DINUM — https://geo.api.gouv.fr).
 *
 * Endpoints publics (pas d'authentification requise) destinés à l'autocomplétion
 * d'adresse dans le tunnel de commande React.
 *
 * Toutes les routes sont préfixées /api/geo/* et exclues d'API Platform.
 */
#[Route('/api/geo', name: 'geo_')]
final class GeoController extends AbstractController
{
    public function __construct(
        private readonly GeoApiService $geoApi,
    ) {
    }

    /**
     * Recherche de communes par nom (autocomplétion).
     *
     * GET /api/geo/communes?q=Paris&limit=10
     *
     * Params :
     *   - q     : string  (requis, min 2 caractères)
     *   - limit : integer (optionnel, 1–20, défaut 10)
     */
    #[Route('/communes', name: 'communes', methods: ['GET'])]
    public function communes(Request $request): JsonResponse
    {
        $q = trim((string) $request->query->get('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->json(
                ['error' => 'Le paramètre "q" doit contenir au moins 2 caractères.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $limit = (int) $request->query->get('limit', 10);
        $limit = max(1, min($limit, 20));

        $data = $this->geoApi->searchCommunes($q, $limit);

        return $this->json($data);
    }

    /**
     * Recherche de communes par code postal.
     *
     * GET /api/geo/communes/postal?cp=75001&limit=10
     */
    #[Route('/communes/postal', name: 'communes_postal', methods: ['GET'])]
    public function communesByPostalCode(Request $request): JsonResponse
    {
        $cp = trim((string) $request->query->get('cp', ''));

        if (!preg_match('/^\d{4,5}$/', $cp)) {
            return $this->json(
                ['error' => 'Le paramètre "cp" doit être un code postal valide (4 ou 5 chiffres).'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $limit = (int) $request->query->get('limit', 10);
        $limit = max(1, min($limit, 20));

        $data = $this->geoApi->searchCommunesByPostalCode($cp, $limit);

        return $this->json($data);
    }

    /**
     * Liste complète des régions.
     *
     * GET /api/geo/regions
     */
    #[Route('/regions', name: 'regions', methods: ['GET'])]
    public function regions(): JsonResponse
    {
        return $this->json($this->geoApi->getRegions());
    }

    /**
     * Liste des départements, avec filtre optionnel par région.
     *
     * GET /api/geo/departements
     * GET /api/geo/departements?region=11
     */
    #[Route('/departements', name: 'departements', methods: ['GET'])]
    public function departements(Request $request): JsonResponse
    {
        $regionCode = $request->query->get('region');
        $data = $this->geoApi->getDepartements($regionCode !== null ? (string) $regionCode : null);

        return $this->json($data);
    }

    /**
     * Communes d'un département.
     *
     * GET /api/geo/departements/75/communes?limit=50
     */
    #[Route('/departements/{code}/communes', name: 'departement_communes', methods: ['GET'])]
    public function communesByDepartement(string $code, Request $request): JsonResponse
    {
        if (!preg_match('/^[0-9]{2,3}[ABab]?$/', $code)) {
            return $this->json(
                ['error' => 'Code département invalide.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $limit = (int) $request->query->get('limit', 100);
        $limit = max(1, min($limit, 500));

        $data = $this->geoApi->getCommunesByDepartement($code, $limit);

        return $this->json($data);
    }
}
