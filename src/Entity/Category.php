<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['displayOrder', 'name'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read', 'saas_service:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['category:read', 'category:write', 'saas_service:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['category:read', 'category:write'])]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['category:read', 'category:write'])]
    private ?int $displayOrder = null;

    /**
     * @var Collection<int, SaasService>
     */
    #[ORM\OneToMany(targetEntity: SaasService::class, mappedBy: 'category')]
    #[Groups(['category:read'])]
    private Collection $saasServices;

    public function __construct()
    {
        $this->saasServices = new ArrayCollection();
    }

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * @return Collection<int, SaasService>
     */
    public function getSaasServices(): Collection
    {
        return $this->saasServices;
    }

    public function addSaasService(SaasService $saasService): static
    {
        if (!$this->saasServices->contains($saasService)) {
            $this->saasServices->add($saasService);
            $saasService->setCategory($this);
        }

        return $this;
    }

    public function removeSaasService(SaasService $saasService): static
    {
        if ($this->saasServices->removeElement($saasService)) {
            if ($saasService->getCategory() === $this) {
                $saasService->setCategory(null);
            }
        }

        return $this;
    }
}
