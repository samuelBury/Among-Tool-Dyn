<?php

namespace App\Entity;

use App\Repository\DroitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DroitRepository::class)
 */
class Droit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Gerer::class, mappedBy="droit")
     */
    private $gerer;

    public function __construct()
    {
        $this->gerer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Gerer[]
     */
    public function getGerer(): Collection
    {
        return $this->gerer;
    }

    public function addGerer(Gerer $gerer): self
    {
        if (!$this->gerer->contains($gerer)) {
            $this->gerer[] = $gerer;
            $gerer->setDroit($this);
        }

        return $this;
    }

    public function removeGerer(Gerer $gerer): self
    {
        if ($this->gerer->removeElement($gerer)) {
            // set the owning side to null (unless already changed)
            if ($gerer->getDroit() === $this) {
                $gerer->setDroit(null);
            }
        }

        return $this;
    }
}
