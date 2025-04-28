<?php

namespace App\Entity;

use App\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaseRepository::class)]
class Base
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fond $fond = null;

    #[ORM\ManyToOne(inversedBy: 'bases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exemplaire $exemplaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFond(): ?Fond
    {
        return $this->fond;
    }

    public function setFond(?Fond $fond): static
    {
        $this->fond = $fond;

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
}
