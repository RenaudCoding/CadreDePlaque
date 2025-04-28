<?php

namespace App\Entity;

use App\Repository\LogoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogoRepository::class)]
class Logo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomLogo = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $couleurLogo = null;

    #[ORM\Column(length: 255)]
    private ?string $urlLogo = null;

    /**
     * @var Collection<int, Decoration>
     */
    #[ORM\OneToMany(targetEntity: Decoration::class, mappedBy: 'logo', orphanRemoval: true)]
    private Collection $decorations;

    public function __construct()
    {
        $this->decorations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLogo(): ?string
    {
        return $this->nomLogo;
    }

    public function setNomLogo(string $nomLogo): static
    {
        $this->nomLogo = $nomLogo;

        return $this;
    }

    public function getCouleurLogo(): ?int
    {
        return $this->couleurLogo;
    }

    public function setCouleurLogo(int $couleurLogo): static
    {
        $this->couleurLogo = $couleurLogo;

        return $this;
    }

    public function getUrlLogo(): ?string
    {
        return $this->urlLogo;
    }

    public function setUrlLogo(string $urlLogo): static
    {
        $this->urlLogo = $urlLogo;

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
            $decoration->setLogo($this);
        }

        return $this;
    }

    public function removeDecoration(Decoration $decoration): static
    {
        if ($this->decorations->removeElement($decoration)) {
            // set the owning side to null (unless already changed)
            if ($decoration->getLogo() === $this) {
                $decoration->setLogo(null);
            }
        }

        return $this;
    }
}
