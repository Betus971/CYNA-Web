<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * Wrapper autour de l'API Géo du gouvernement français (DINUM).
 * Documentation : https://geo.api.gouv.fr
 *
 * Aucun token requis — API publique.
 */
final class GeoApiService
{
    private const BASE_URL = 'https://geo.api.gouv.fr';

    /**
     * Champs renvoyés par défaut pour les communes (optimise la taille de réponse).
     */
    private const COMMUNE_FIELDS = 'nom,code,codesPostaux,departement,region,population';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Recherche de communes par nom (pour l'autocomplétion d'adresse).
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

        return $this->get('/communes', [
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
        return $this->get('/communes', [
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
        return $this->get('/regions', ['fields' => 'nom,code']);
    }

    /**
     * Liste complète des départements.
     *
     * @param string|null $regionCode  Filtre optionnel par code région
     *
     * @return list<array{code: string, nom: string, codeRegion: string}>
     */
    public function getDepartements(?string $regionCode = null): array
    {
        $params = ['fields' => 'nom,code,codeRegion'];
        if ($regionCode !== null) {
            $params['codeRegion'] = $regionCode;
        }

        return $this->get('/departements', $params);
    }

    /**
     * Liste des communes d'un département.
     *
     * @return list<array{nom: string, code: string, codesPostaux: list<string>}>
     */
    public function getCommunesByDepartement(string $departementCode, int $limit = 100): array
    {
        return $this->get('/communes', [
            'codeDepartement' => $departementCode,
            'fields'          => 'nom,code,codesPostaux,population',
            'boost'           => 'population',
            'limit'           => $limit,
        ]);
    }

    // -------------------------------------------------------------------------
    // Internals
    // -------------------------------------------------------------------------

    /**
     * Exécute un GET sur l'API Géo et retourne le tableau JSON décodé.
     * Retourne [] en cas d'erreur réseau ou de réponse invalide.
     *
     * @param array<string, mixed> $params
     * @return list<array<string, mixed>>
     */
    private function get(string $path, array $params = []): array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . $path, [
                'query'   => $params,
                'timeout' => 5,
                'headers' => ['Accept' => 'application/json'],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                $this->logger->warning('API Geo returned non-200 status.', [
                    'path'   => $path,
                    'status' => $statusCode,
                ]);
                return [];
            }

            /** @var list<array<string, mixed>> $data */
            $data = $response->toArray();
            return $data;
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('API Geo network error.', [
                'path'      => $path,
                'exception' => $e->getMessage(),
            ]);
            return [];
        } catch (\Throwable $e) {
            $this->logger->error('API Geo unexpected error.', [
                'path'      => $path,
                'exception' => $e->getMessage(),
            ]);
            return [];
        }
    }
}
