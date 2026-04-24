<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\SaasServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SaasServiceRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['saas_service:read']],
    denormalizationContext: ['groups' => ['saas_service:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'partial',
    'description' => 'partial',
    'category' => 'exact',
    'category.name' => 'partial',
])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(BooleanFilter::class, properties: ['isAvailable'])]
#[ApiFilter(OrderFilter::class, properties: ['price', 'name', 'priority'], arguments: ['orderParameterName' => 'order'])]
class SaasService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['saas_service:read', 'category:read', 'order:read', 'order_item:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read', 'order:read', 'order_item:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?string $technicalSpecs = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read', 'order:read', 'order_item:read'])]
    private ?string $price = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private bool $isAvailable = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?int $priority = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['saas_service:read', 'saas_service:write', 'category:read'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'saasServices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isAvailable(): bool
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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
