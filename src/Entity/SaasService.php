<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SaasServiceRepository;
use Symfony\Component\Serializer\Annotation\Groups; // <--- L'import
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaasServiceRepository::class)]
#[ApiResource (
    normalizationContext: ['groups' => ['saas_service:read']],
    denormalizationContext: ['groups' => ['saas_service:write']]
)]
class SaasService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['saas_service:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read', 'order:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?string $technicalSpecs = null;

    #[ORM\Column]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read', 'order:read'])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?bool $isAvailable = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?int $priority = null;

    #[ORM\ManyToOne(inversedBy: 'saasServices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['saas_service:read', 'saas_service:write'])]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTechnicalSpecs(): ?string
    {
        return $this->technicalSpecs;
    }

    public function setTechnicalSpecs(?string $technicalSpecs): static
    {
        $this->technicalSpecs = $technicalSpecs;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
