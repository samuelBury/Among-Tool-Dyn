<?php

namespace App\Entity;

use App\Repository\CarreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarreRepository::class)
 */
class Carre
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
    private $Valeur;

    /**
     * @ORM\ManyToOne(targetEntity=Ligne::class, inversedBy="carre")
     */
    private $ligne;

    /**
     * @ORM\ManyToOne(targetEntity=Colonne::class, inversedBy="carre")
     */
    private $colonne;

    /**
     * @ORM\OneToOne(targetEntity=Doc::class, mappedBy="carre", cascade={"persist", "remove"})
     */
    private $doc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->Valeur;
    }

    public function setValeur(string $Valeur): self
    {
        $this->Valeur = $Valeur;

        return $this;
    }

    public function getLigne(): ?Ligne
    {
        return $this->ligne;
    }

    public function setLigne(?Ligne $ligne): self
    {
        $this->ligne = $ligne;

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

    public function getDoc(): ?Doc
    {
        return $this->doc;
    }

    public function setDoc(Doc $doc): self
    {
        // set the owning side of the relation if necessary
        if ($doc->getCarre() !== $this) {
            $doc->setCarre($this);
        }

        $this->doc = $doc;

        return $this;
    }
}
