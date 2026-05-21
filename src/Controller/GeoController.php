<?php

namespace App\Controller;

use App\Service\GeoApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Proxy vers les APIs géographiques du gouvernement français (DINUM).
 *
 *  - API Adresse (BAN) : https://api-adresse.data.gouv.fr
 *    Autocomplétion d'adresses complètes (numéro + rue + code postal + ville).
 *
 *  - API Géo : https://geo.api.gouv.fr
 *    Régions, départements, communes.
 *
 * Tous les endpoints sont PUBLIC_ACCESS (security.yaml) car l'autocomplétion
 * d'adresse doit fonctionner même avant la connexion (formulaire checkout guest).
 */
#[Route('/api/geo', name: 'geo_')]
final class GeoController extends AbstractController
{
    public function __construct(
        private readonly GeoApiService $geoApi,
    ) {
    }

    // =========================================================================
    // API Adresse — BAN
    // =========================================================================

    /**
     * Autocomplétion d'adresses complètes.
     *
     * Retourne : label, housenumber, street, postcode, city, context, type, score, lon, lat.
     * Le frontend peut directement pré-remplir les champs adresse1 / code postal / ville.
     *
     * GET /api/geo/address?q=8+bd+du+port+amiens&limit=8
     * GET /api/geo/address?q=8+rue+de+rivoli&postcode=75001
     */
    #[Route('/address', name: 'address_search', methods: ['GET'])]
    public function addressSearch(Request $request): JsonResponse
    {
        $q = trim((string) $request->query->get('q', ''));

        if (mb_strlen($q) < 3) {
            return $this->json(
                ['error' => 'Le paramètre "q" doit contenir au moins 3 caractères.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $limit    = (int) $request->query->get('limit', 8);
        $limit    = max(1, min($limit, 20));
        $postcode = $request->query->get('postcode');
        $type     = $request->query->get('type');

        $allowedTypes = ['housenumber', 'street', 'municipality', 'locality'];
        if ($type !== null && !in_array($type, $allowedTypes, true)) {
            $type = null;
        }

        $data = $this->geoApi->searchAddresses(
            $q,
            $limit,
            $postcode !== null ? (string) $postcode : null,
            $type,
        );

        return $this->json($data);
    }

    /**
     * Autocomplétion de rues uniquement (sans numéro de voie).
     *
     * GET /api/geo/streets?q=rue+de+rivoli&limit=8
     */
    #[Route('/streets', name: 'street_search', methods: ['GET'])]
    public function streetSearch(Request $request): JsonResponse
    {
        $q = trim((string) $request->query->get('q', ''));

        if (mb_strlen($q) < 3) {
            return $this->json(
                ['error' => 'Le paramètre "q" doit contenir au moins 3 caractères.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $limit = (int) $request->query->get('limit', 8);
        $limit = max(1, min($limit, 20));

        return $this->json($this->geoApi->searchStreets($q, $limit));
    }

    // =========================================================================
    // API Géo — Communes, Régions, Départements
    // =========================================================================

    /**
     * Recherche de communes par nom (autocomplétion champ Ville).
     *
     * GET /api/geo/communes?q=Paris&limit=10
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

        return $this->json($this->geoApi->searchCommunes($q, $limit));
    }

    /**
     * Recherche de communes par code postal.
     *
     * GET /api/geo/communes/postal?cp=75001
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

        return $this->json($this->geoApi->searchCommunesByPostalCode($cp, $limit));
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

        return $this->json(
            $this->geoApi->getDepartements($regionCode !== null ? (string) $regionCode : null)
        );
    }

    /**
     * Communes d'un département.
     *
     * GET /api/geo/departements/75/communes?limit=50
     */
    #[Route('/departements/{code}/communes', name: 'departement_communes', methods: ['GET'])]
    public function communesByDepartement(string $code, Request $request): JsonResponse
    {
        if (!preg_match('/^\d{2,3}[ABab]?$/', $code)) {
            return $this->json(
                ['error' => 'Code département invalide.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $limit = (int) $request->query->get('limit', 100);
        $limit = max(1, min($limit, 500));

        return $this->json($this->geoApi->getCommunesByDepartement($code, $limit));
    }
}
