<?php

namespace App\Tests\Controller;

use App\Controller\GeoController;
use App\Service\GeoApiService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GeoControllerTest extends TestCase
{
    public function testAddressSearchRejectsTooShortQuery(): void
    {
        $response = $this->controller()->addressSearch(new Request(['q' => 'ab']));

        self::assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        self::assertSame(['error' => 'Le paramètre "q" doit contenir au moins 3 caractères.'], json_decode($response->getContent(), true));
    }

    public function testAddressSearchNormalizesLimitAndInvalidType(): void
    {
        $httpResponse = $this->createStub(ResponseInterface::class);
        $httpResponse->method('getStatusCode')->willReturn(200);
        $httpResponse->method('toArray')->willReturn(['features' => []]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects(self::once())
            ->method('request')
            ->with('GET', 'https://api-adresse.data.gouv.fr/search/', self::callback(
                static fn (array $options): bool => $options['query']['limit'] === 20
                    && $options['query']['type'] === null
                    && $options['query']['postcode'] === '75001'
            ))
            ->willReturn($httpResponse);

        $controller = $this->controller($httpClient);
        $response = $controller->addressSearch(new Request([
            'q' => 'rue de rivoli',
            'limit' => '200',
            'postcode' => '75001',
            'type' => 'bad',
        ]));

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame([], json_decode($response->getContent(), true));
    }

    public function testPostalAndDepartmentValidation(): void
    {
        $controller = $this->controller();

        self::assertSame(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $controller->communesByPostalCode(new Request(['cp' => 'abc']))->getStatusCode(),
        );
        self::assertSame(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $controller->communesByDepartement('bad', new Request())->getStatusCode(),
        );
    }

    private function controller(?HttpClientInterface $httpClient = null): GeoController
    {
        $controller = new GeoController(new GeoApiService(
            $httpClient ?? $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class),
        ));
        $controller->setContainer(new Container());

        return $controller;
    }
}
