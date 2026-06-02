<?php

namespace App\Tests\Service;

use App\Service\GeoApiService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GeoApiServiceTest extends TestCase
{
    public function testSearchAddressesNormalizesBanFeatureCollection(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn([
            'features' => [[
                'properties' => [
                    'label' => '8 Boulevard du Port 80000 Amiens',
                    'housenumber' => '8',
                    'street' => 'Boulevard du Port',
                    'postcode' => '80000',
                    'city' => 'Amiens',
                    'context' => '80, Somme',
                    'type' => 'housenumber',
                    'score' => 0.98,
                ],
                'geometry' => ['coordinates' => [2.3, 49.9]],
            ]],
        ]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())->method('request')->willReturn($response);

        $results = (new GeoApiService($httpClient, $this->createStub(LoggerInterface::class)))
            ->searchAddresses('8 bd du port', 50);

        self::assertCount(1, $results);
        self::assertSame('Amiens', $results[0]['city']);
        self::assertSame(2.3, $results[0]['lon']);
        self::assertSame(49.9, $results[0]['lat']);
    }

    public function testGeoApiReturnsEmptyArrayOnNon200Response(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);

        $httpClient = $this->createStub(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $results = (new GeoApiService($httpClient, $this->createStub(LoggerInterface::class)))
            ->getRegions();

        self::assertSame([], $results);
    }
}
