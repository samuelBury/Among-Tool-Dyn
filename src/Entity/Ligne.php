<?php

namespace App\Entity;

use App\Repository\LigneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneRepository::class)
 */
class Ligne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;





    /**
     * @ORM\OneToMany(targetEntity=Carre::class, mappedBy="ligne")
     */
    private $carre;

    /**
     * @ORM\ManyToOne(targetEntity=Dashboard::class, inversedBy="lignes")
     */
    private $dashboard;

    public function __construct()
    {
        $this->carre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }





    /**
     * @return Collection|Carre[]
     */
    public function getCarre(): Collection
    {
        return $this->carre;
    }

    public function addCarre(Carre $carre): self
    {
        if (!$this->carre->contains($carre)) {
            $this->carre[] = $carre;
            $carre->setLigne($this);
        }

        return $this;
    }

    public function removeCarre(Carre $carre): self
    {
        if ($this->carre->removeElement($carre)) {
            // set the owning side to null (unless already changed)
            if ($carre->getLigne() === $this) {
                $carre->setLigne(null);
            }
        }

        return $this;
    }

    public function getDashboard(): ?Dashboard
    {
        return $this->dashboard;
    }

    public function setDashboard(?Dashboard $dashboard): self
    {
        $this->dashboard = $dashboard;

        return $this;
    }
}
