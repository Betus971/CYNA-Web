<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Moyen de paiement enregistré par un utilisateur. On ne stocke JAMAIS les données carte
 * en clair : seuls un identifiant externe du PSP (ex. Stripe pm_xxx) et les 4 derniers
 * chiffres sont persistés.
 */
#[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['payment_method:read']],
    denormalizationContext: ['groups' => ['payment_method:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Get(security: "is_granted('ROLE_ADMIN') or object.getUser() == user"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.getUser() == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getUser() == user"),
    ]
)]
class PaymentMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['payment_method:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Identifiant opaque fourni par le prestataire de paiement (Stripe, etc.).
     */
    #[ORM\Column(length: 191)]
    #[Assert\NotBlank]
    private ?string $providerToken = null;

    #[ORM\Column(length: 50)]
    #[Groups(['payment_method:read', 'payment_method:write'])]
    private ?string $provider = null;

    #[ORM\Column(length: 20)]
    #[Groups(['payment_method:read', 'payment_method:write'])]
    private ?string $brand = null;

    #[ORM\Column(length: 4)]
    #[Assert\Length(exactly: 4)]
    #[Groups(['payment_method:read', 'payment_method:write'])]
    private ?string $last4 = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 12)]
    #[Groups(['payment_method:read', 'payment_method:write'])]
    private ?int $expMonth = null;

    #[ORM\Column]
    #[Groups(['payment_method:read', 'payment_method:write'])]
    private ?int $expYear = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['payment_method:read', 'payment_method:write'])]
    private bool $isDefault = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProviderToken(): ?string
    {
        return $this->providerToken;
    }

    public function setProviderToken(string $providerToken): static
    {
        $this->providerToken = $providerToken;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getLast4(): ?string
    {
        return $this->last4;
    }

    public function setLast4(string $last4): static
    {
        $this->last4 = $last4;

        return $this;
    }

    public function getExpMonth(): ?int
    {
        return $this->expMonth;
    }

    public function setExpMonth(int $expMonth): static
    {
        $this->expMonth = $expMonth;

        return $this;
    }

    public function getExpYear(): ?int
    {
        return $this->expYear;
    }

    public function setExpYear(int $expYear): static
    {
        $this->expYear = $expYear;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
