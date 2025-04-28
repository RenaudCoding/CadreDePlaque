<?php

namespace App\Entity;

use App\Repository\MarquageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarquageRepository::class)]
class Marquage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'marquages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Typo $typo = null;

    #[ORM\ManyToOne(inversedBy: 'marquages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exemplaire $exemplaire = null;

    #[ORM\Column(length: 255)]
    private ?string $texteTypo = null;

    #[ORM\Column(length: 50)]
    private ?string $couleurTypo = null;

    #[ORM\Column]
    private ?float $tailleTypo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypo(): ?Typo
    {
        return $this->typo;
    }

    public function setTypo(?Typo $typo): static
    {
        $this->typo = $typo;

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

    public function getTexteTypo(): ?string
    {
        return $this->texteTypo;
    }

    public function setTexteTypo(string $texteTypo): static
    {
        $this->texteTypo = $texteTypo;

        return $this;
    }

    public function getCouleurTypo(): ?string
    {
        return $this->couleurTypo;
    }

    public function setCouleurTypo(string $couleurTypo): static
    {
        $this->couleurTypo = $couleurTypo;

        return $this;
    }

    public function getTailleTypo(): ?float
    {
        return $this->tailleTypo;
    }

    public function setTailleTypo(float $tailleTypo): static
    {
        $this->tailleTypo = $tailleTypo;

        return $this;
    }
}
