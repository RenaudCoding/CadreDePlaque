<?php

namespace App\Entity;

use App\Repository\TypoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypoRepository::class)]
class Typo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nomTypo = null;

    #[ORM\Column(length: 255)]
    private ?string $urlTypo = null;

    /**
     * @var Collection<int, Marquage>
     */
    #[ORM\OneToMany(targetEntity: Marquage::class, mappedBy: 'typo', orphanRemoval: true)]
    private Collection $marquages;

    public function __construct()
    {
        $this->marquages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTypo(): ?string
    {
        return $this->nomTypo;
    }

    public function setNomTypo(string $nomTypo): static
    {
        $this->nomTypo = $nomTypo;

        return $this;
    }

    public function getUrlTypo(): ?string
    {
        return $this->urlTypo;
    }

    public function setUrlTypo(string $urlTypo): static
    {
        $this->urlTypo = $urlTypo;

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
            $marquage->setTypo($this);
        }

        return $this;
    }

    public function removeMarquage(Marquage $marquage): static
    {
        if ($this->marquages->removeElement($marquage)) {
            // set the owning side to null (unless already changed)
            if ($marquage->getTypo() === $this) {
                $marquage->setTypo(null);
            }
        }

        return $this;
    }
}
