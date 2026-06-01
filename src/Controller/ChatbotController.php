<?php

namespace App\Controller;

use App\Entity\ChatbotConversation;
use App\Entity\ContactMessage;
use App\Service\Chatbot\GeminiChatbotClient;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/api/chatbot', name: 'chatbot_')]
final class ChatbotController extends AbstractController
{
    private const ESCALATION_MARKER = '[ESCALADE_HUMAIN]';

    public function __construct(
        private readonly GeminiChatbotClient $chatbotClient,
        private readonly EntityManagerInterface $em,
        private readonly EmailVerifier $emailVerifier,
        private readonly RateLimiterFactory $chatbotLimiter,
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface $logger,
        #[Autowire(param: 'app.support_email')]
        private readonly string $supportEmail,
    ) {
    }

    #[Route('/message', name: 'message', methods: ['POST'])]
    public function message(Request $request): JsonResponse
    {
        $limiter = $this->chatbotLimiter->create($request->getClientIp());
        $limit = $limiter->consume();
        if (false === $limit->isAccepted()) {
            $retryAfter = $limit->getRetryAfter();
            $seconds = $retryAfter ? ($retryAfter->getTimestamp() - time()) : 60;
            throw new TooManyRequestsHttpException(
                $seconds,
                'Trop de messages envoyés. Veuillez patienter avant de réessayer.'
            );
        }

        $payload = json_decode($request->getContent() ?: '{}', true);
        if (!is_array($payload)) {
            return $this->json(['error' => 'Payload invalide.'], Response::HTTP_BAD_REQUEST);
        }

        $message = trim((string) ($payload['message'] ?? ''));
        $violations = $this->validator->validate($message, [
            new Assert\NotBlank(),
            new Assert\Length(min: 2, max: 1000),
            new Assert\Regex([
                'pattern' => '/<[^>]*script/i',
                'match' => false,
                'message' => 'Les injections de scripts ne sont pas autorisées.'
            ])
        ]);
        if (count($violations) > 0) {
            return $this->json(['error' => $violations[0]->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $locale = $this->normalizeLocale((string) ($payload['locale'] ?? 'fr'));
        $history = $this->sanitizeHistory($payload['history'] ?? []);
        $email = trim((string) ($payload['email'] ?? ''));
        $subject = $this->limit(trim((string) ($payload['subject'] ?? 'Demande chatbot')), 255);
        $fullName = $this->limit(trim((string) ($payload['fullName'] ?? '')), 255);
        $escalate = true === ($payload['escalate'] ?? false);

        if ('' !== $email && false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Email invalide.'], Response::HTTP_BAD_REQUEST);
        }

        if ($escalate) {
            return $this->escalate($message, $history, $locale, $email, $subject, $fullName);
        }

        $userContext = '';
        if (is_array($payload['currentUser'] ?? null)) {
            $cUser = $payload['currentUser'];
            $userContext .= sprintf("Utilisateur connecte : %s %s (%s)\n", $cUser['firstname'] ?? '', $cUser['lastname'] ?? '', $cUser['email'] ?? '');
        } else {
            $userContext .= "Utilisateur : Visiteur invite non connecte\n";
        }

        if (is_array($payload['cartItems'] ?? null) && count($payload['cartItems']) > 0) {
            $userContext .= "Contenu du panier actuel de l'utilisateur :\n";
            foreach ($payload['cartItems'] as $item) {
                $userContext .= sprintf(
                    "- %s (Quantite : %d, Duree : %d mois, Prix unitaire : %s EUR)\n",
                    $item['name'] ?? 'Produit',
                    $item['quantity'] ?? 1,
                    $item['durationMonths'] ?? 1,
                    $item['price'] ?? '0'
                );
            }
        } else {
            $userContext .= "Panier actuel de l'utilisateur : Vide\n";
        }

        try {
            $answer = $this->chatbotClient->generateReply($message, $history, $locale, $userContext);
            $shouldEscalate = str_contains($answer, self::ESCALATION_MARKER) || $this->isExplicitSupportRequest($message);
            $answer = trim(str_replace(self::ESCALATION_MARKER, '', $answer));
            $statusCode = Response::HTTP_OK;
        } catch (\Throwable $e) {
            $this->logger->error('Gemini API error occurred.', [
                'exception' => $e->getMessage(),
                'message' => $message,
            ]);

            return $this->json([
                'error' => 'Le service de chatbot est temporairement indisponible.'
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $conversation = $this->storeConversation(
            message: $message,
            answer: $answer,
            history: $history,
            locale: $locale,
            email: '' !== $email ? $email : null,
            subject: $subject,
            fullName: '' !== $fullName ? $fullName : null,
            escalated: false,
        );

        return $this->json([
            'answer' => $answer,
            'conversationId' => $conversation->getId(),
            'shouldEscalate' => $shouldEscalate,
        ], $statusCode);
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     */
    private function escalate(
        string $message,
        array $history,
        string $locale,
        string $email,
        string $subject,
        string $fullName,
    ): JsonResponse {
        if ('' === $email) {
            return $this->json(['error' => 'Email requis pour contacter un agent.'], Response::HTTP_BAD_REQUEST);
        }

        $answer = 'Votre demande a ete transmise au support CYNA avec la conversation. Un membre de l equipe reviendra vers vous rapidement.';
        $transcript = $this->buildTranscript($history, $message);

        $contact = (new ContactMessage())
            ->setFullName('' !== $fullName ? $fullName : 'Visiteur chatbot')
            ->setEmail($email)
            ->setSubject($subject)
            ->setMessage($this->limit("Escalade chatbot\n\n".$transcript, 4000));

        $conversation = $this->storeConversation(
            message: $message,
            answer: $answer,
            history: $history,
            locale: $locale,
            email: $email,
            subject: $subject,
            fullName: '' !== $fullName ? $fullName : null,
            escalated: true,
            flush: false,
        );

        $conversation->setContactMessage($contact);

        $this->em->persist($contact);
        $this->em->flush();

        // Envoi de la notification par email au support configuré
        $this->emailVerifier->sendChatbotEscalationEmail($conversation, $this->supportEmail);

        return $this->json([
            'answer' => $answer,
            'conversationId' => $conversation->getId(),
            'shouldEscalate' => false,
            'escalated' => true,
        ]);
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     */
    private function storeConversation(
        string $message,
        string $answer,
        array $history,
        string $locale,
        ?string $email,
        string $subject,
        ?string $fullName,
        bool $escalated,
        bool $flush = true,
    ): ChatbotConversation {
        $conversation = (new ChatbotConversation())
            ->setFullName($fullName)
            ->setEmail($email)
            ->setSubject($subject)
            ->setQuestion($message)
            ->setAnswer($answer)
            ->setTranscript($this->buildTranscript($history, $message, $answer))
            ->setLocale($locale)
            ->setEscalated($escalated);

        $this->em->persist($conversation);
        if ($flush) {
            $this->em->flush();
        }

        return $conversation;
    }

    /**
     * @return array<int, array{role: string, content: string}>
     */
    private function sanitizeHistory(mixed $history): array
    {
        if (!is_array($history)) {
            return [];
        }

        $items = [];
        foreach (array_slice($history, -30) as $item) {
            if (!is_array($item)) {
                continue;
            }

            $role = (string) ($item['role'] ?? 'user');
            $content = trim((string) ($item['content'] ?? ''));
            if ('' === $content) {
                continue;
            }

            $items[] = [
                'role' => in_array($role, ['assistant', 'model'], true) ? 'assistant' : 'user',
                'content' => $this->limit($content, 1200),
            ];
        }

        return $items;
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     */
    private function buildTranscript(array $history, string $message, ?string $answer = null): string
    {
        $lines = [];
        foreach ($history as $item) {
            $label = 'assistant' === $item['role'] ? 'Assistant' : 'Utilisateur';
            $lines[] = $label.': '.$item['content'];
        }

        $lines[] = 'Utilisateur: '.$message;
        if (null !== $answer) {
            $lines[] = 'Assistant: '.$answer;
        }

        return $this->limit(implode("\n", $lines), 4000);
    }

    private function normalizeLocale(string $locale): string
    {
        $locale = strtolower(substr(trim($locale), 0, 5));

        return preg_match('/^[a-z]{2}(-[a-z]{2})?$/', $locale) ? $locale : 'fr';
    }

    private function isExplicitSupportRequest(string $message): bool
    {
        $message = mb_strtolower($message);

        foreach ([
            'parler a un humain',
            'parler à un humain',
            'contacter un humain',
            'contacter l assistance',
            "contacter l'assistance",
            'agent humain',
            'conseiller',
            'assistance',
            'support humain',
        ] as $needle) {
            if (str_contains($message, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function limit(string $value, int $max): string
    {
        return mb_substr($value, 0, $max);
    }
}
