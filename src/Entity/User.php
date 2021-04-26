<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
     * @ORM\OneToMany(targetEntity=Gerer::class, mappedBy="user")
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
}
