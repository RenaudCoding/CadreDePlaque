<?php

namespace App\Entity;

use App\Repository\ExemplaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExemplaireRepository::class)]
class Exemplaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Panier>
     */
    #[ORM\OneToMany(targetEntity: Panier::class, mappedBy: 'exemplaire', orphanRemoval: true)]
    private Collection $paniers;

    /**
     * @var Collection<int, Base>
     */
    #[ORM\OneToMany(targetEntity: Base::class, mappedBy: 'exemplaire', orphanRemoval: true, cascade: ["persist"])]
    private Collection $bases;

    /**
     * @var Collection<int, Decoration>
     */
    #[ORM\OneToMany(targetEntity: Decoration::class, mappedBy: 'exemplaire', orphanRemoval: true, cascade: ["persist"])]
    private Collection $decorations;

    /**
     * @var Collection<int, Marquage>
     */
    #[ORM\OneToMany(targetEntity: Marquage::class, mappedBy: 'exemplaire', orphanRemoval: true, cascade: ["persist"])]
    private Collection $marquages;

    #[ORM\ManyToOne(inversedBy: 'exemplaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'exemplaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(length: 100)]
    private ?string $nomExemplaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
        $this->bases = new ArrayCollection();
        $this->decorations = new ArrayCollection();
        $this->marquages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setExemplaire($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getExemplaire() === $this) {
                $panier->setExemplaire(null);
            }
        }

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
            $basis->setExemplaire($this);
        }

        return $this;
    }

    public function removeBasis(Base $basis): static
    {
        if ($this->bases->removeElement($basis)) {
            // set the owning side to null (unless already changed)
            if ($basis->getExemplaire() === $this) {
                $basis->setExemplaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Decoration>
     */
    public function getDecorations(): Collection
    {
        return $this->decorations;
    }

    public function addDecoration(Decoration $decoration): static
    {
        if (!$this->decorations->contains($decoration)) {
            $this->decorations->add($decoration);
            $decoration->setExemplaire($this);
        }

        return $this;
    }

    public function removeDecoration(Decoration $decoration): static
    {
        if ($this->decorations->removeElement($decoration)) {
            // set the owning side to null (unless already changed)
            if ($decoration->getExemplaire() === $this) {
                $decoration->setExemplaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Marquage>
     */
    public function getMarquages(): Collection
    {
        return $this->marquages;
    }

    public function addMarquage(Marquage $marquage): static
    {
        if (!$this->marquages->contains($marquage)) {
            $this->marquages->add($marquage);
            $marquage->setExemplaire($this);
        }

        return $this;
    }

    public function removeMarquage(Marquage $marquage): static
    {
        if ($this->marquages->removeElement($marquage)) {
            // set the owning side to null (unless already changed)
            if ($marquage->getExemplaire() === $this) {
                $marquage->setExemplaire(null);
            }
        }

        return $this;
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getNomExemplaire(): ?string
    {
        return $this->nomExemplaire;
    }

    public function setNomExemplaire(string $nomExemplaire): static
    {
        $this->nomExemplaire = $nomExemplaire;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nomExemplaire;
    }
}
