<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['cart_item:read']],
    denormalizationContext: ['groups' => ['cart_item:write']],
    operations: [
        new Post(),
        new Patch(),
        new Delete(),
    ]
)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cart:read', 'cart_item:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart_item:read', 'cart_item:write'])]
    #[Assert\NotNull]
    private ?Cart $cart = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart:read', 'cart_item:read', 'cart_item:write'])]
    #[Assert\NotNull]
    private ?SaasService $saasService = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['cart:read', 'cart:write', 'cart_item:read', 'cart_item:write'])]
    private int $quantity = 1;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['cart:read', 'cart:write', 'cart_item:read', 'cart_item:write'])]
    private int $durationMonths = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

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
}
