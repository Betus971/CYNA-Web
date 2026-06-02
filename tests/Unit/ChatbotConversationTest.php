<?php

namespace App\Tests\Unit;

use App\Entity\ChatbotConversation;
use PHPUnit\Framework\TestCase;

final class ChatbotConversationTest extends TestCase
{
    public function testConversationDefaultsAndEscalationFlags(): void
    {
        $conversation = (new ChatbotConversation())
            ->setFullName('Client')
            ->setEmail('client@example.test')
            ->setSubject('Question offre')
            ->setQuestion('Quelle offre choisir ?')
            ->setAnswer('Réponse CYNA')
            ->setTranscript("Client: question\nBot: réponse")
            ->setLocale('en')
            ->setEscalated(true)
            ->setHandled(true);

        self::assertSame('en', $conversation->getLocale());
        self::assertTrue($conversation->isEscalated());
        self::assertTrue($conversation->isHandled());
        self::assertInstanceOf(\DateTimeImmutable::class, $conversation->getCreatedAt());
    }
}
