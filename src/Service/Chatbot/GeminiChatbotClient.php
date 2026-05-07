<?php

namespace App\Service\Chatbot;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GeminiChatbotClient
{
    private const ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';

    private const SYSTEM_PROMPT = <<<'PROMPT'
Tu es l'assistant support CYNA-IT, plateforme e-commerce B2B de solutions cybersecurite SaaS.
Reponds en francais par defaut, ou en anglais si l'utilisateur ecrit en anglais.
Perimetre: catalogue CYNA, SOC, EDR, XDR, abonnements, paiement, compte client, panier, contact support.
Reponses courtes, pratiques, professionnelles. Maximum 6 phrases.
Reponds normalement aux questions simples de navigation, catalogue, panier, commandes, abonnements et compte client.
Ne propose pas d'humain pour une question generale comme retrouver une commande, modifier son compte, consulter un abonnement, comprendre un paiement ou trouver une page.
Propose un humain uniquement si l'utilisateur le demande explicitement, si tu ne peux vraiment pas repondre, si la demande implique une commande avec numero/reference, un incident urgent, une donnee personnelle, un paiement bloque, un remboursement, une reclamation, ou une action que seul le support peut faire.
Dans ces cas uniquement, termine ta reponse par la ligne exacte [ESCALADE_HUMAIN].
Ne promets pas d'action deja realisee si elle demande un agent humain.
PROMPT;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $model,
    ) {
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     */
    public function generateReply(string $message, array $history, string $locale): string
    {
        if ('' === trim($this->apiKey)) {
            throw new \RuntimeException('Gemini API key is not configured.');
        }

        $contents = $this->buildContents($history, $message);

        try {
            $response = $this->httpClient->request('POST', sprintf(self::ENDPOINT, rawurlencode($this->model)), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey,
                ],
                'json' => [
                    'system_instruction' => [
                        'parts' => [[
                            'text' => self::SYSTEM_PROMPT."\nLocale UI: ".$locale,
                        ]],
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.35,
                        'maxOutputTokens' => 700,
                    ],
                ],
            ]);

            $payload = $response->toArray(false);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Gemini service is unreachable.', 0, $e);
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode >= 300) {
            $message = $payload['error']['message'] ?? 'Gemini service returned an error.';
            throw new \RuntimeException((string) $message);
        }

        $answer = $this->extractText($payload);
        if ('' === $answer) {
            throw new \RuntimeException('Gemini response is empty.');
        }

        return $answer;
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     *
     * @return array<int, array{role: string, parts: array<int, array{text: string}>}>
     */
    private function buildContents(array $history, string $message): array
    {
        $contents = [];

        foreach (array_slice($history, -8) as $item) {
            $role = 'assistant' === $item['role'] || 'model' === $item['role'] ? 'model' : 'user';
            $content = trim($item['content']);
            if ('' === $content) {
                continue;
            }

            $contents[] = [
                'role' => $role,
                'parts' => [['text' => mb_substr($content, 0, 1200)]],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        return $contents;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function extractText(array $payload): string
    {
        $parts = $payload['candidates'][0]['content']['parts'] ?? [];
        if (!is_array($parts)) {
            return '';
        }

        $texts = [];
        foreach ($parts as $part) {
            if (is_array($part) && isset($part['text']) && is_string($part['text'])) {
                $texts[] = trim($part['text']);
            }
        }

        return trim(implode("\n", array_filter($texts)));
    }
}
