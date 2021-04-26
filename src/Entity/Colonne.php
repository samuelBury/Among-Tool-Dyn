<?php

namespace App\Entity;

use App\Repository\ColonneRepository;
use App\Repository\DashboardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity(repositoryClass=ColonneRepository::class)
 */
class Colonne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Dashboard::class, inversedBy="colonnes")
     */
    private $dashboard;

    /**
     * @ORM\OneToMany(targetEntity=Gerer::class, mappedBy="colonne")
     */
    private $gerer;

    /**
     * @ORM\OneToMany(targetEntity=Ligne::class, mappedBy="Colonne")
     */
    private $lignes;

    /**
     * @ORM\Column(type="integer")
     */
    private $rank;

    /**
     * @ORM\OneToMany(targetEntity=Carre::class, mappedBy="colonne")
     */
    private $carre;

    public function __construct()
    {
        $this->gerer = new ArrayCollection();
        $this->lignes = new ArrayCollection();
        $this->carre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $gerer->setColonne($this);
        }

        return $this;
    }

    public function removeGerer(Gerer $gerer): self
    {
        if ($this->gerer->removeElement($gerer)) {
            // set the owning side to null (unless already changed)
            if ($gerer->getColonne() === $this) {
                $gerer->setColonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ligne[]
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(Ligne $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes[] = $ligne;
            $ligne->setColonne($this);
        }

        return $this;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getColonne() === $this) {
                $ligne->setColonne(null);
            }
        }

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }
    public function requestToEntity(Request $request,int $dash, DashboardRepository $repo){
        $nbColonne= count($request->request->all())/3;
        $i=0;
        $inputName ="columnName";
        $inputType="columnType";
        $inputRank ="inputRank";
        $colonnes = array();


        while ($i<$nbColonne){
            $colonne = new Colonne();
            if($request->request->get($inputName . (string)$i)!="" && (int)$request->request->get($inputRank . (string)$i)!=null){
                $colonne->setName($request->request->get($inputName . (string)$i));
                $colonne->setType($request->request->get($inputType . (string)$i));
                $colonne->setRank((int)$request->request->get($inputRank . (string)$i));
                $colonne->setDashboard($repo->find($dash));
                $colonnes[]=$colonne;
            }

            $i++;
        }


        return $colonnes;
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
            $carre->setColonne($this);
        }

        return $this;
    }

    public function removeCarre(Carre $carre): self
    {
        if ($this->carre->removeElement($carre)) {
            // set the owning side to null (unless already changed)
            if ($carre->getColonne() === $this) {
                $carre->setColonne(null);
            }
        }

        return $this;
    }
}
