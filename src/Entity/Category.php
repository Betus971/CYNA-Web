<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource] // <--- 2. Et ajoute ceci juste au-dessus de "class Category"
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $displayOrder = null;

    /**
     * @var Collection<int, SaasService>
     */
    #[ORM\OneToMany(targetEntity: SaasService::class, mappedBy: 'category')]
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
            // set the owning side to null (unless already changed)
            if ($saasService->getCategory() === $this) {
                $saasService->setCategory(null);
            }
        }

        return $this;
    }
}
