<?php

namespace App\Entity;

use App\Repository\GererRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GererRepository::class)
 */
class Gerer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Droit::class, inversedBy="gerer")
     */
    private $droit;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gerer")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Colonne::class, inversedBy="gerer")
     */
    private $colonne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDroit(): ?Droit
    {
        return $this->droit;
    }

    public function setDroit(?Droit $droit): self
    {
        $this->droit = $droit;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getColonne(): ?Colonne
    {
        return $this->colonne;
    }

    public function setColonne(?Colonne $colonne): self
    {
        $this->colonne = $colonne;

        return $this;
    }
}
