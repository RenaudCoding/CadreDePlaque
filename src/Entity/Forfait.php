<?php

namespace App\Entity;

use App\Repository\ForfaitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForfaitRepository::class)]
class Forfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $intitule = null;

    #[ORM\Column]
    private ?float $prixForfait = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'forfait', orphanRemoval: true)]
    private Collection $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getPrixForfait(): ?float
    {
        return $this->prixForfait;
    }

    public function setPrixForfait(float $prixForfait): static
    {
        $this->prixForfait = $prixForfait;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setForfait($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getForfait() === $this) {
                $demande->setForfait(null);
            }
        }

        return $this;
    }
}
