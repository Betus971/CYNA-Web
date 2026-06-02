<?php

namespace App\Tests\Service;

use App\Service\Chatbot\GeminiChatbotClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GeminiChatbotClientTest extends TestCase
{
    public function testRejectsMissingApiKey(): void
    {
        $client = new GeminiChatbotClient($this->createStub(HttpClientInterface::class), '', 'model');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Gemini API key is not configured.');

        $client->generateReply('Bonjour', [], 'fr');
    }

    public function testGeneratesReplyFromGeminiPayload(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn([
            'candidates' => [[
                'content' => ['parts' => [
                    ['text' => 'Réponse'],
                    ['text' => 'CYNA'],
                ]],
            ]],
        ]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects(self::once())
            ->method('request')
            ->with('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-test:generateContent', self::callback(
                static fn (array $options): bool => $options['headers']['x-goog-api-key'] === 'api-key'
                    && $options['json']['contents'][0]['role'] === 'model'
                    && $options['json']['contents'][1]['role'] === 'user'
            ))
            ->willReturn($response);

        $answer = (new GeminiChatbotClient($httpClient, 'api-key', 'gemini-test'))
            ->generateReply('Question', [['role' => 'assistant', 'content' => 'Historique']], 'fr');

        self::assertSame("Réponse\nCYNA", $answer);
    }

    public function testThrowsOnHttpErrorOrEmptyResponse(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);
        $response->method('toArray')->willReturn(['error' => ['message' => 'Gemini error']]);

        $httpClient = $this->createStub(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Gemini error');

        (new GeminiChatbotClient($httpClient, 'api-key', 'gemini-test'))->generateReply('Question', [], 'fr');
    }
}
