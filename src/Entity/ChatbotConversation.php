<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\ChatbotConversationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Conversation chatbot historisee pour le suivi support backoffice.
 */
#[ORM\Entity(repositoryClass: ChatbotConversationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['chatbot_conversation:read']],
    denormalizationContext: ['groups' => ['chatbot_conversation:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
class ChatbotConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['chatbot_conversation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['chatbot_conversation:read'])]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['chatbot_conversation:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['chatbot_conversation:read'])]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['chatbot_conversation:read'])]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['chatbot_conversation:read'])]
    private ?string $answer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['chatbot_conversation:read'])]
    private ?string $transcript = null;

    #[ORM\Column(length: 10, options: ['default' => 'fr'])]
    #[Groups(['chatbot_conversation:read'])]
    private string $locale = 'fr';

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['chatbot_conversation:read'])]
    private bool $escalated = false;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['chatbot_conversation:read', 'chatbot_conversation:write'])]
    private bool $handled = false;

    #[ORM\Column]
    #[Groups(['chatbot_conversation:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getTranscript(): ?string
    {
        return $this->transcript;
    }

    public function setTranscript(?string $transcript): static
    {
        $this->transcript = $transcript;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function isEscalated(): bool
    {
        return $this->escalated;
    }

    public function setEscalated(bool $escalated): static
    {
        $this->escalated = $escalated;

        return $this;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }

    public function setHandled(bool $handled): static
    {
        $this->handled = $handled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
