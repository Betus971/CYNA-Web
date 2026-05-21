<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * Wrapper autour de deux APIs gouvernementales françaises (DINUM) :
 *
 *  - API Adresse  : https://api-adresse.data.gouv.fr  (autocomplétion adresse complète)
 *  - API Géo      : https://geo.api.gouv.fr            (régions, départements, communes)
 *
 * Aucun token requis — APIs publiques, gratuites et sans quota strict.
 */
final class GeoApiService
{
    /** Base URL de l'API Adresse (BAN — Base Adresse Nationale). */
    private const ADDR_URL = 'https://api-adresse.data.gouv.fr';

    /** Base URL de l'API Géo (DINUM). */
    private const GEO_URL = 'https://geo.api.gouv.fr';

    /** Champs renvoyés par défaut pour les communes. */
    private const COMMUNE_FIELDS = 'nom,code,codesPostaux,departement,region,population';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    // =========================================================================
    // API Adresse — BAN (Base Adresse Nationale)
    // =========================================================================

    /**
     * Recherche d'adresses complètes (numéro + rue + ville + code postal).
     *
     * Idéal pour l'autocomplétion du champ "adresse" dans le formulaire utilisateur.
     * L'API retourne un GeoJSON ; on normalise en tableau plat.
     *
     * @param string      $query    Texte libre saisi (ex: "8 bd du port amiens")
     * @param int         $limit    Max résultats (1–20, défaut 8)
     * @param string|null $postcode Filtre optionnel sur code postal (ex: "75001")
     * @param string|null $type     Filtre type : "housenumber"|"street"|"municipality"|"locality"
     *
     * @return list<array{
     *     label: string,
     *     housenumber: string,
     *     street: string,
     *     postcode: string,
     *     city: string,
     *     context: string,
     *     type: string,
     *     score: float,
     *     lon: float,
     *     lat: float,
     * }>
     */
    public function searchAddresses(
        string $query,
        int $limit = 8,
        ?string $postcode = null,
        ?string $type = null,
    ): array {
        $limit = max(1, min($limit, 20));

        $params = [
            'q'     => $query,
            'limit' => $limit,
        ];
        if ($postcode !== null) {
            $params['postcode'] = $postcode;
        }
        if ($type !== null) {
            $params['type'] = $type;
        }

        $raw = $this->getAbsolute(self::ADDR_URL . '/search/', $params);

        // L'API retourne { "type": "FeatureCollection", "features": [...] }
        // On normalise chaque Feature en tableau plat.
        $features = $raw['features'] ?? [];
        $results  = [];

        foreach ($features as $feature) {
            $props = $feature['properties'] ?? [];
            $coords = $feature['geometry']['coordinates'] ?? [0, 0];

            $results[] = [
                'label'       => $props['label']       ?? '',
                'housenumber' => $props['housenumber']  ?? '',
                'street'      => $props['street']       ?? ($props['name'] ?? ''),
                'postcode'    => $props['postcode']     ?? '',
                'city'        => $props['city']         ?? '',
                'context'     => $props['context']      ?? '',
                'type'        => $props['type']         ?? '',
                'score'       => (float) ($props['score'] ?? 0),
                'lon'         => (float) ($coords[0]   ?? 0),
                'lat'         => (float) ($coords[1]   ?? 0),
            ];
        }

        return $results;
    }

    /**
     * Recherche de rues uniquement (sans numéro).
     * Utile pour l'autocomplétion du champ "rue" seul.
     *
     * @return list<array{label: string, street: string, postcode: string, city: string}>
     */
    public function searchStreets(string $query, int $limit = 8): array
    {
        return $this->searchAddresses($query, $limit, type: 'street');
    }

    /**
     * Recherche de villes / communes via l'API Adresse.
     * Préférer searchCommunes() (API Géo) pour plus de détails (département, région).
     *
     * @return list<array{label: string, city: string, postcode: string}>
     */
    public function searchCitiesByAddress(string $query, int $limit = 8): array
    {
        return $this->searchAddresses($query, $limit, type: 'municipality');
    }

    // =========================================================================
    // API Géo — Communes, Régions, Départements
    // =========================================================================

    /**
     * Recherche de communes par nom (pour la liste déroulante Ville).
     *
     * @param string $query   Texte saisi par l'utilisateur (min. 2 caractères recommandé)
     * @param int    $limit   Nombre de résultats (max 20, par défaut 10)
     *
     * @return list<array{
     *     nom: string,
     *     code: string,
     *     codesPostaux: list<string>,
     *     departement: array{code: string, nom: string},
     *     region: array{code: string, nom: string},
     *     population: int,
     * }>
     */
    public function searchCommunes(string $query, int $limit = 10): array
    {
        $limit = min($limit, 20);

        return $this->getGeo('/communes', [
            'nom'    => $query,
            'fields' => self::COMMUNE_FIELDS,
            'boost'  => 'population',
            'limit'  => $limit,
        ]);
    }

    /**
     * Recherche de communes par code postal.
     *
     * @return list<array{nom: string, code: string, codesPostaux: list<string>, departement: array, region: array}>
     */
    public function searchCommunesByPostalCode(string $postalCode, int $limit = 10): array
    {
        return $this->getGeo('/communes', [
            'codePostal' => $postalCode,
            'fields'     => self::COMMUNE_FIELDS,
            'boost'      => 'population',
            'limit'      => $limit,
        ]);
    }

    /**
     * Liste complète des régions françaises.
     *
     * @return list<array{code: string, nom: string}>
     */
    public function getRegions(): array
    {
        return $this->getGeo('/regions', ['fields' => 'nom,code']);
    }

    /**
     * Liste des départements, avec filtre optionnel par code région.
     *
     * @return list<array{code: string, nom: string, codeRegion: string}>
     */
    public function getDepartements(?string $regionCode = null): array
    {
        $params = ['fields' => 'nom,code,codeRegion'];
        if ($regionCode !== null) {
            $params['codeRegion'] = $regionCode;
        }

        return $this->getGeo('/departements', $params);
    }

    /**
     * Communes d'un département, triées par population.
     *
     * @return list<array{nom: string, code: string, codesPostaux: list<string>}>
     */
    public function getCommunesByDepartement(string $departementCode, int $limit = 100): array
    {
        return $this->getGeo('/communes', [
            'codeDepartement' => $departementCode,
            'fields'          => 'nom,code,codesPostaux,population',
            'boost'           => 'population',
            'limit'           => $limit,
        ]);
    }

    // =========================================================================
    // Internals
    // =========================================================================

    /**
     * GET vers l'API Géo (geo.api.gouv.fr) — retourne un tableau JSON (liste).
     *
     * @param array<string, mixed> $params
     * @return list<array<string, mixed>>
     */
    private function getGeo(string $path, array $params = []): array
    {
        /** @var list<array<string, mixed>> $result */
        $result = $this->getAbsolute(self::GEO_URL . $path, $params);
        return $result;
    }

    /**
     * GET vers une URL absolue — retourne le contenu JSON décodé tel quel.
     * Retourne [] en cas d'erreur réseau, HTTP non-200, ou JSON invalide.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>|list<array<string, mixed>>
     */
    private function getAbsolute(string $url, array $params = []): array
    {
        try {
            $response = $this->httpClient->request('GET', $url, [
                'query'   => $params,
                'timeout' => 5,
                'headers' => ['Accept' => 'application/json'],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                $this->logger->warning('GeoApiService: non-200 response.', [
                    'url'    => $url,
                    'status' => $statusCode,
                ]);
                return [];
            }

            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('GeoApiService: network error.', [
                'url'       => $url,
                'exception' => $e->getMessage(),
            ]);
            return [];
        } catch (\Throwable $e) {
            $this->logger->error('GeoApiService: unexpected error.', [
                'url'       => $url,
                'exception' => $e->getMessage(),
            ]);
            return [];
        }
    }
}
