<?php

namespace App\Entity;

use App\Repository\CarreRepository;
use App\Repository\ColonneRepository;
use App\Repository\DashboardRepository;
use App\Repository\LigneRepository;
use App\Repository\TravaillerSurRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity(repositoryClass=DashboardRepository::class)
 */
class Dashboard
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Colonne::class, mappedBy="dashboard")
     */
    private $colonnes;

    /**
     * @ORM\OneToMany(targetEntity=Ligne::class, mappedBy="dashboard")
     */
    private $lignes;

    /**
     * @ORM\OneToMany(targetEntity=PossederDroitDash::class, mappedBy="dashboard")
     */
    private $possederDroitDashes;

    /**
     * @ORM\OneToMany(targetEntity=TravaillerSur::class, mappedBy="Dashboard")
     */
    private $travaillerSurs;

    public function __construct()
    {
        $this->colonnes = new ArrayCollection();
        $this->lignes = new ArrayCollection();
        $this->possederDroitDashes = new ArrayCollection();
        $this->travaillerSurs = new ArrayCollection();
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

    /**
     * @return Collection|Colonne[]
     */
    public function getColonnes(): Collection
    {
        return $this->colonnes;
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $em
     */
    public function addUser(User $user, ObjectManager $em){
        $travaillerSur = new TravaillerSur();
        $travaillerSur->setUser($user);
        $travaillerSur->setDashboard($this);
        $em->persist($travaillerSur);

    }

    public function addColonne(Colonne $colonne): self
    {
        if (!$this->colonnes->contains($colonne)) {
            $this->colonnes[] = $colonne;
            $colonne->setDashboard($this);
        }

        return $this;
    }

    public function removeColonne(Colonne $colonne): self
    {
        if ($this->colonnes->removeElement($colonne)) {
            // set the owning side to null (unless already changed)
            if ($colonne->getDashboard() === $this) {
                $colonne->setDashboard(null);
            }
        }

        return $this;
    }
    public function requestToEntity(Request $request, $nbCol, ColonneRepository $colRepo,$idDash, DashboardRepository $dashRepo,CarreRepository $caseRepo):array
    {
            $form = $request->request->all();
            $lignes= array();
            $i =0;

            foreach ($form as $unForm){
                unset($form[array_search("", $form, true)]);
            }


            $caseExistantes= array();
            $arrayCle=array_keys($form);


            foreach($arrayCle as $uneCle){

                $derniereLetre = substr($uneCle,-1);
                dump($derniereLetre);
                if ($derniereLetre === 'a'){

                    $tab=explode ( "?",$uneCle);
                    $caseExistantes[] = ['valeur'=>$form[$uneCle],'id'=> $tab[0]];

                    dump($form[$uneCle]);
                    unset($form[$uneCle]);
                }
            }
            dump($caseExistantes);
        $arrayCle=array_keys($form);

            foreach ($caseExistantes as $uneCase){

                $caseBDD = $caseRepo->find((int)$uneCase['id']);


                $caseBDD->setValeur($uneCase['valeur']);
                $lignes[]= $caseBDD;
                dump($lignes);
            }


            $uneligne= new Ligne();

            for($i=0, $iMax = count($form); $i< $iMax; $i++) {

                $uneCle = $arrayCle[$i];


                $nomCol = substr($uneCle, 0, -1);
                $tabs = explode('?',$nomCol);

                $uneCase = new Carre();

                $chaine=str_replace ( '_', ' ' , $tabs[0]);

                $uneCase->setColonne($colRepo->findOneByName($chaine));

                $uneCase->setValeur($form[$uneCle]);
                $uneligne->addCarre($uneCase);


                if( fmod(($i+1) , $nbCol ) == 0){

                    $uneligne->setDashboard($dashRepo->find($idDash));
                    $lignes[]= $uneligne;

                    $uneligne = new Ligne();
                }





            }
        dump($lignes);
        return $lignes;

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
            $ligne->setDashboard($this);
        }

        return $this;
    }
    public function getUsers(TravaillerSurRepository $travaillerSurRepository, UserRepository $userRepository){

        $users= array();
        $travaillers=$travaillerSurRepository->findByDashboard($this->getId());
        foreach ($travaillers as $unTravailleur){
            $users[]=$userRepository->find($unTravailleur->getUser()->getId());
        }
        return $users;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getDashboard() === $this) {
                $ligne->setDashboard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PossederDroitDash[]
     */
    public function getPossederDroitDashes(): Collection
    {
        return $this->possederDroitDashes;
    }

    public function addPossederDroitDash(PossederDroitDash $possederDroitDash): self
    {
        if (!$this->possederDroitDashes->contains($possederDroitDash)) {
            $this->possederDroitDashes[] = $possederDroitDash;
            $possederDroitDash->setDashboard($this);
        }

        return $this;
    }

    public function removePossederDroitDash(PossederDroitDash $possederDroitDash): self
    {
        if ($this->possederDroitDashes->removeElement($possederDroitDash)) {
            // set the owning side to null (unless already changed)
            if ($possederDroitDash->getDashboard() === $this) {
                $possederDroitDash->setDashboard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TravaillerSur[]
     */
    public function getTravaillerSurs(): Collection
    {
        return $this->travaillerSurs;
    }

    public function addTravaillerSur(TravaillerSur $travaillerSur): self
    {
        if (!$this->travaillerSurs->contains($travaillerSur)) {
            $this->travaillerSurs[] = $travaillerSur;
            $travaillerSur->setDashboard($this);
        }

        return $this;
    }

    public function removeTravaillerSur(TravaillerSur $travaillerSur): self
    {
        if ($this->travaillerSurs->removeElement($travaillerSur)) {
            // set the owning side to null (unless already changed)
            if ($travaillerSur->getDashboard() === $this) {
                $travaillerSur->setDashboard(null);
            }
        }

        return $this;
    }

}
