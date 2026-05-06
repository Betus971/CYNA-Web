<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\OrderItemRepository;
use App\Enum\SubscriptionStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN') or object.getOrder().getUser() == user"),
    ],
    normalizationContext: ['groups' => ['order_item:read']],
    denormalizationContext: ['groups' => ['order_item:write']]
)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read', 'order_item:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write', 'order_item:read', 'order_item:write'])]
    #[Assert\NotNull]
    private ?SaasService $saasService = null;

    /**
     * Snapshot du nom du service au moment de l'achat (historisation).
     */
    #[ORM\Column(length: 255)]
    #[Groups(['order:read', 'order_item:read'])]
    private ?string $productNameSnapshot = null;

    /**
     * Prix unitaire figé au moment de la commande.
     */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['order:read', 'order_item:read'])]
    private ?string $unitPriceSnapshot = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['order:read', 'order:write', 'order_item:read', 'order_item:write'])]
    private int $quantity = 1;

    /**
     * Durée de l'abonnement en mois (1, 12, 24...).
     */
    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['order:read', 'order:write', 'order_item:read', 'order_item:write'])]
    private int $durationMonths = 1;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read', 'order_item:read'])]
    private ?\DateTimeImmutable $subscriptionStartsAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read', 'order_item:read'])]
    private ?\DateTimeImmutable $subscriptionEndsAt = null;

    #[ORM\Column(enumType: SubscriptionStatus::class, nullable: true)]
    #[Groups(['order:read', 'order_item:read'])]
    private ?SubscriptionStatus $subscriptionStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getSaasService(): ?SaasService
    {
        return $this->saasService;
    }

    public function setSaasService(?SaasService $saasService): static
    {
        $this->saasService = $saasService;

        return $this;
    }

    public function getProductNameSnapshot(): ?string
    {
        return $this->productNameSnapshot;
    }

    public function setProductNameSnapshot(string $productNameSnapshot): static
    {
        $this->productNameSnapshot = $productNameSnapshot;

        return $this;
    }

    public function getUnitPriceSnapshot(): ?string
    {
        return $this->unitPriceSnapshot;
    }

    public function setUnitPriceSnapshot(string $unitPriceSnapshot): static
    {
        $this->unitPriceSnapshot = $unitPriceSnapshot;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDurationMonths(): int
    {
        return $this->durationMonths;
    }

    public function setDurationMonths(int $durationMonths): static
    {
        $this->durationMonths = $durationMonths;

        return $this;
    }

    public function getSubscriptionStartsAt(): ?\DateTimeImmutable
    {
        return $this->subscriptionStartsAt;
    }

    public function setSubscriptionStartsAt(?\DateTimeImmutable $subscriptionStartsAt): static
    {
        $this->subscriptionStartsAt = $subscriptionStartsAt;

        return $this;
    }

    public function getSubscriptionEndsAt(): ?\DateTimeImmutable
    {
        return $this->subscriptionEndsAt;
    }

    public function setSubscriptionEndsAt(?\DateTimeImmutable $subscriptionEndsAt): static
    {
        $this->subscriptionEndsAt = $subscriptionEndsAt;

        return $this;
    }

    public function getSubscriptionStatus(): ?SubscriptionStatus
    {
        return $this->subscriptionStatus;
    }

    public function setSubscriptionStatus(?SubscriptionStatus $subscriptionStatus): static
    {
        $this->subscriptionStatus = $subscriptionStatus;

        return $this;
    }
}
