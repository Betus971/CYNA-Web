<?php

namespace App\Tests\Controller;

use App\Controller\ChatbotController;
use App\Entity\ChatbotConversation;
use App\Entity\ContactMessage;
use App\Service\Chatbot\GeminiChatbotClient;
use App\Service\EmailVerifier;
use App\Service\InvoicePdfService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\StorageInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Twig\Environment;

final class ChatbotControllerTest extends TestCase
{
    public function testMessageRejectsInvalidPayloadAndInvalidMessage(): void
    {
        $controller = $this->controller();

        self::assertSame(
            Response::HTTP_BAD_REQUEST,
            $controller->message(Request::create('/api/chatbot/message', 'POST', [], [], [], [], '{bad-json'))->getStatusCode(),
        );
        self::assertSame(
            Response::HTTP_BAD_REQUEST,
            $controller->message($this->jsonRequest(['message' => 'a']))->getStatusCode(),
        );
    }

    public function testMessageStoresConversationAndDetectsEscalationMarker(): void
    {
        $httpResponse = $this->createStub(ResponseInterface::class);
        $httpResponse->method('getStatusCode')->willReturn(200);
        $httpResponse->method('toArray')->willReturn([
            'candidates' => [[
                'content' => ['parts' => [['text' => "Je transmets.\n[ESCALADE_HUMAIN]"]]],
            ]],
        ]);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())->method('request')->willReturn($httpResponse);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('persist')->with(self::isInstanceOf(ChatbotConversation::class));
        $em->expects(self::once())->method('flush');

        $response = $this->controller($httpClient, $em)->message($this->jsonRequest([
            'message' => 'Je veux parler a un humain',
            'locale' => 'fr-FR',
            'history' => [['role' => 'assistant', 'content' => 'Bonjour']],
        ]));

        $payload = json_decode($response->getContent(), true);

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame('Je transmets.', $payload['answer']);
        self::assertTrue($payload['shouldEscalate']);
    }

    public function testEscalationRequiresEmailThenPersistsContactAndConversation(): void
    {
        $controller = $this->controller();

        self::assertSame(
            Response::HTTP_BAD_REQUEST,
            $controller->message($this->jsonRequest(['message' => 'Aide support', 'escalate' => true]))->getStatusCode(),
        );

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::exactly(2))->method('persist')->with(self::logicalOr(
            self::isInstanceOf(ChatbotConversation::class),
            self::isInstanceOf(ContactMessage::class),
        ));
        $em->expects(self::once())->method('flush');

        $response = $this->controller(null, $em)->message($this->jsonRequest([
            'message' => 'Aide support',
            'escalate' => true,
            'email' => 'client@example.test',
            'fullName' => 'Client',
            'subject' => 'Support',
        ]));

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertTrue(json_decode($response->getContent(), true)['escalated']);
    }

    private function controller(?HttpClientInterface $httpClient = null, ?EntityManagerInterface $em = null): ChatbotController
    {
        $controller = new ChatbotController(
            new GeminiChatbotClient(
                $httpClient ?? $this->createStub(HttpClientInterface::class),
                'test-api-key',
                'gemini-test',
            ),
            $em ?? $this->createStub(EntityManagerInterface::class),
            new EmailVerifier(
                $this->createStub(MailerInterface::class),
                $this->createStub(LoggerInterface::class),
                new InvoicePdfService($this->createStub(Environment::class), sys_get_temp_dir(), dirname(__DIR__, 2)),
                'http://localhost:5173',
                'noreply@example.test',
            ),
            new RateLimiterFactory(
                ['id' => 'chatbot_test', 'policy' => 'no_limit'],
                $this->createStub(StorageInterface::class),
            ),
            Validation::createValidator(),
            $this->createStub(LoggerInterface::class),
            'support@example.test',
        );
        $controller->setContainer(new Container());

        return $controller;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function jsonRequest(array $payload): Request
    {
        return Request::create('/api/chatbot/message', 'POST', [], [], [], [], json_encode($payload, JSON_THROW_ON_ERROR));
    }
}
