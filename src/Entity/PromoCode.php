<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PromoCodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PromoCodeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['promo_code:read']],
    denormalizationContext: ['groups' => ['promo_code:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
class PromoCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['promo_code:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank]
    #[Groups(['promo_code:read', 'promo_code:write'])]
    private ?string $code = null;

    /**
     * Pourcentage de réduction entre 0 et 100.
     */
    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    #[Assert\Range(min: 0, max: 100)]
    #[Groups(['promo_code:read', 'promo_code:write'])]
    private ?string $percentage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['promo_code:read', 'promo_code:write'])]
    private ?\DateTimeImmutable $startsAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['promo_code:read', 'promo_code:write'])]
    private ?\DateTimeImmutable $endsAt = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['promo_code:read', 'promo_code:write'])]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['promo_code:read', 'promo_code:write'])]
    private ?int $maxUsages = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['promo_code:read'])]
    private int $usageCount = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = strtoupper($code);

        return $this;
    }

    public function getPercentage(): ?string
    {
        return $this->percentage;
    }

    public function setPercentage(string $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeImmutable $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeImmutable $endsAt): static
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getMaxUsages(): ?int
    {
        return $this->maxUsages;
    }

    public function setMaxUsages(?int $maxUsages): static
    {
        $this->maxUsages = $maxUsages;

        return $this;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function setUsageCount(int $usageCount): static
    {
        $this->usageCount = $usageCount;

        return $this;
    }

    public function isUsable(\DateTimeImmutable $at = new \DateTimeImmutable()): bool
    {
        if (!$this->active) {
            return false;
        }
        if (null !== $this->startsAt && $this->startsAt > $at) {
            return false;
        }
        if (null !== $this->endsAt && $this->endsAt < $at) {
            return false;
        }
        if (null !== $this->maxUsages && $this->usageCount >= $this->maxUsages) {
            return false;
        }

        return true;
    }
}
