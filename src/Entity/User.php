<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    public function __construct()
    {
        $this->gerer = new ArrayCollection();
        $this->possederDroitDashes = new ArrayCollection();

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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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
}
