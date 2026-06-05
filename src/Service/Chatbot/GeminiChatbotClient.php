<?php

namespace App\Service\Chatbot;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GeminiChatbotClient
{
    private const ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';

    private const SYSTEM_PROMPT = <<<'PROMPT'
Tu es l'assistant support CYNA-IT, plateforme e-commerce B2B de solutions cybersecurite SaaS.
Agis comme un agent CYNA personnalise. Tu as acces en temps reel au contexte de l'utilisateur (nom, prenom, email, etat de connexion) et au contenu de son panier d'achat actuel, fournis ci-dessous.
Reponds en francais par defaut, ou dans la langue de l'utilisateur (anglais, arabe, hebreu, etc.) s'il te sollicite dans cette langue.
Perimetre: catalogue CYNA, SOC, EDR, XDR, abonnements, paiement, compte client, panier, contact support.
Reponses courtes, pratiques, professionnelles. Maximum 6 phrases.
Utilise le contexte fourni pour repondre directement, chaleureusement et precisement aux questions sur l'identite de l'utilisateur (ex: "Qui suis-je ?") ou le contenu de son panier (ex: "Qu'y a-t-il dans mon panier ?", "Quel est le montant de mon panier ?") sans proposer d'assistance humaine ni ajouter [ESCALADE_HUMAIN].
Si le panier est vide ou si l'utilisateur n'est pas connecte, indique-le simplement et poliment.
Ne dis pas que tu ne peux pas voir le panier ou le compte, car tu as desormais acces a ces informations.
Propose un humain uniquement si l'utilisateur le demande explicitement, si tu ne peux vraiment pas repondre, ou si la demande implique une commande deja passee avec numero/reference, un incident urgent, une donnee personnelle sensible a modifier, un paiement echoue/bloque Stripe, un remboursement ou une reclamation.
Dans ces cas d'escalade uniquement, termine ta reponse par la ligne exacte [ESCALADE_HUMAIN].
Ne promets pas d'action administrative ou technique deja realisee si elle demande l'intervention d'un agent humain.
PROMPT;

    private const ALLOWED_MODELS = [
        'gemini-1.5-flash',
        'gemini-1.5-pro',
        'gemini-2.0-flash',
        'gemini-2.0-pro',
    ];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $model,
    ) {
        // Validation du modèle pour éviter l'utilisation de modèles non autorisés
        if (!in_array($model, self::ALLOWED_MODELS, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Modèle non autorisé : %s. Modèles autorisés : %s',
                $model,
                implode(', ', self::ALLOWED_MODELS)
            ));
        }
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     */
    public function generateReply(string $message, array $history, string $locale, string $userContext = ''): string
    {
        if ('' === trim($this->apiKey)) {
            throw new \RuntimeException('Configuration du service de chatbot manquante.');
        }

        $contents = $this->buildContents($history, $message, $userContext, $locale);

        try {
            $response = $this->httpClient->request('POST', sprintf(self::ENDPOINT, rawurlencode($this->model)), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey,
                ],
                'json' => [
                    'system_instruction' => [
                        'parts' => [[
                            'text' => self::SYSTEM_PROMPT,
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

        // Nettoyage de la réponse pour éviter les injections
        return $this->sanitizeAnswer($answer);
    }

    /**
     * @param array<int, array{role: string, content: string}> $history
     * @return array<int, array{role: string, parts: array<int, array{text: string}>}>
     */
    private function buildContents(array $history, string $message, string $userContext, string $locale): array
    {
        $contents = [];

        // Ajouter le contexte utilisateur comme un message système séparé
        if ($userContext !== '') {
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => "Contexte utilisateur actuel :\n" . $this->sanitizeUserContext($userContext) . "\nLocale UI: " . $locale]],
            ];
        }

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
     * Nettoie le contexte utilisateur pour éviter les injections de prompt
     */
    private function sanitizeUserContext(string $context): string
    {
        // Mots-clés dangereux à bloquer
        $forbiddenPatterns = [
            '/ignore.*previous/i',
            '/forget.*instructions?/i',
            '/disregard.*above/i',
            '/system.*prompt/i',
            '/prompt.*injection/i',
            '/you.*are.*now/i',
            '/act.*as/i',
            '/pretend.*you/i',
        ];

        foreach ($forbiddenPatterns as $pattern) {
            $context = preg_replace($pattern, '[CONTENU_BLOQUÉ]', $context);
        }

        return $context;
    }

    /**
     * Nettoie la réponse pour éviter les injections de code
     */
    private function sanitizeAnswer(string $answer): string
    {
        // Supprimer toutes les balises HTML
        $answer = strip_tags($answer);

        // Échapper les caractères spéciaux
        $answer = htmlspecialchars($answer, ENT_QUOTES, 'UTF-8');

        // Supprimer les URLs
        $answer = preg_replace('/https?:\/\/[^\s]+/', '[LIEN_BLOQUÉ]', $answer);

        // Supprimer les caractères de formatage Markdown
        $answer = preg_replace('/[#*_~`]/', '', $answer);

        // Limiter la longueur de la réponse
        return mb_substr($answer, 0, 2000);
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
