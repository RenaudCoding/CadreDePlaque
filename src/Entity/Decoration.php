<?php

namespace App\Entity;

use App\Repository\DecorationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecorationRepository::class)]
class Decoration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'decorations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Logo $logo = null;

    #[ORM\ManyToOne(inversedBy: 'decorations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exemplaire $exemplaire = null;

    #[ORM\Column]
    private ?float $tailleLogo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?Logo
    {
        return $this->logo;
    }

    public function setLogo(?Logo $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getExemplaire(): ?Exemplaire
    {
        return $this->exemplaire;
    }

    public function setExemplaire(?Exemplaire $exemplaire): static
    {
        $this->exemplaire = $exemplaire;

        return $this;
    }

    public function getTailleLogo(): ?float
    {
        return $this->tailleLogo;
    }

    public function setTailleLogo(float $tailleLogo): static
    {
        $this->tailleLogo = $tailleLogo;

        return $this;
    }
}
