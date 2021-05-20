<?php

namespace App\Entity;

use App\Repository\DroitDashRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DroitDashRepository::class)
 */
class DroitDash
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
     * @ORM\OneToMany(targetEntity=PossederDroitDash::class, mappedBy="droitDash")
     */
    private $possederDroitDash;

    public function __construct()
    {
        $this->possederDroitDash = new ArrayCollection();
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
     * @return Collection|PossederDroitDash[]
     */
    public function getPossederDroitDash(): Collection
    {
        return $this->possederDroitDash;
    }

    public function addPossederDroitDash(PossederDroitDash $possederDroitDash): self
    {
        if (!$this->possederDroitDash->contains($possederDroitDash)) {
            $this->possederDroitDash[] = $possederDroitDash;
            $possederDroitDash->setDroitDash($this);
        }

        return $this;
    }

    public function removePossederDroitDash(PossederDroitDash $possederDroitDash): self
    {
        if ($this->possederDroitDash->removeElement($possederDroitDash)) {
            // set the owning side to null (unless already changed)
            if ($possederDroitDash->getDroitDash() === $this) {
                $possederDroitDash->setDroitDash(null);
            }
        }

        return $this;
    }
}
