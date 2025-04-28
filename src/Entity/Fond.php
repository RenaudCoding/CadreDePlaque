<?php

namespace App\Entity;

use App\Repository\FondRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FondRepository::class)]
class Fond
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $couleurFond = null;

    #[ORM\Column(length: 255)]
    private ?string $urlFond = null;

    /**
     * @var Collection<int, Base>
     */
    #[ORM\OneToMany(targetEntity: Base::class, mappedBy: 'fond', orphanRemoval: true)]
    private Collection $bases;

    public function __construct()
    {
        $this->bases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouleurFond(): ?string
    {
        return $this->couleurFond;
    }

    public function setCouleurFond(string $couleurFond): static
    {
        $this->couleurFond = $couleurFond;

        return $this;
    }

    public function getUrlFond(): ?string
    {
        return $this->urlFond;
    }

    public function setUrlFond(string $urlFond): static
    {
        $this->urlFond = $urlFond;

        return $this;
    }

    /**
     * @return Collection<int, Base>
     */
    public function getBases(): Collection
    {
        return $this->bases;
    }

    public function addBasis(Base $basis): static
    {
        if (!$this->bases->contains($basis)) {
            $this->bases->add($basis);
            $basis->setFond($this);
        }

        return $this;
    }

    public function removeBasis(Base $basis): static
    {
        if ($this->bases->removeElement($basis)) {
            // set the owning side to null (unless already changed)
            if ($basis->getFond() === $this) {
                $basis->setFond(null);
            }
        }

        return $this;
    }
}
