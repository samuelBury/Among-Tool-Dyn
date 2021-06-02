<?php

namespace App\Entity;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\AuthentificationManager;
use App\Repository\GererRepository;
use App\Repository\PossederDroitDashRepository;
use App\Repository\TravaillerSurRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Gerer::class, mappedBy="user")
     */
    private $gerer;

    /**
     * @ORM\OneToMany(targetEntity=PossederDroitDash::class, mappedBy="user")
     */
    private $possederDroitDashes;

    /**
     * @ORM\OneToMany(targetEntity=TravaillerSur::class, mappedBy="User")
     */
    private $travaillerSurs;

    private $reelPassword;



    public function __construct(AuthentificationManager $authManage, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->gerer = new ArrayCollection();
        $this->possederDroitDashes = new ArrayCollection();
        $this->travaillerSurs = new ArrayCollection();
        $password =$authManage->chaine_aleatoire(8);
        $this->reelPassword =$password;
        $this->password =$passwordEncoder->encodePassword($this,$password );

    }
    public function getReelPassword(): ?string
    {
        return $this->reelPassword;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getDashboard(TravaillerSurRepository $posRepo){
        $posseders=$posRepo->findByUser($this->getId());
        $dashboards= array();
        foreach ($posseders as $pos){
            $dashboards[]= $pos->getDashboard();
        }
        return $dashboards;

    }
    public function getDroitDashBoard(PossederDroitDashRepository $posRepo){
        $posseders = $posRepo->findByUser($this->getId());
        $droits = array();
        foreach ($posseders as $pos){
            $droits[]= $pos->getDroitDash()->getLibelle();
        }
        return $droits;
    }
    public function getDroitColonne(GererRepository $gererRepo){
        $gerers = $gererRepo->findByUser($this->getId());
        $droits = array();
        foreach ($gerers as $ger){
            $droits[]= $ger->getDroit();
        }
        return $droits;
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
            $gerer->setUser($this);
        }

        return $this;
    }

    public function removeGerer(Gerer $gerer): self
    {
        if ($this->gerer->removeElement($gerer)) {
            // set the owning side to null (unless already changed)
            if ($gerer->getUser() === $this) {
                $gerer->setUser(null);
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
            $possederDroitDash->setUser($this);
        }

        return $this;
    }

    public function removePossederDroitDash(PossederDroitDash $possederDroitDash): self
    {
        if ($this->possederDroitDashes->removeElement($possederDroitDash)) {
            // set the owning side to null (unless already changed)
            if ($possederDroitDash->getUser() === $this) {
                $possederDroitDash->setUser(null);
            }
        }

        return $this;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
            $travaillerSur->setUser($this);
        }

        return $this;
    }

    public function removeTravaillerSur(TravaillerSur $travaillerSur): self
    {
        if ($this->travaillerSurs->removeElement($travaillerSur)) {
            // set the owning side to null (unless already changed)
            if ($travaillerSur->getUser() === $this) {
                $travaillerSur->setUser(null);
            }
        }

        return $this;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getDroit(Dashboard $dashboard, GererRepository $gererRepository){
        $droit = array();
        $gerers=$gererRepository->findByUser($this);
        foreach ($dashboard->getColonnes() as $uneColonne){
            foreach ($gerers as $gerer){
                if ($gerer->getColonne() === $uneColonne){
                    $droit[]= $uneColonne->getName().$gerer->getDroit()->getLibelle();
                }

            }

        }
        return $droit;
    }
}
