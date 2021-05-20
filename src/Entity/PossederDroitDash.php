<?php

namespace App\Entity;

use App\Repository\PossederDroitDashRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PossederDroitDashRepository::class)
 */
class PossederDroitDash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="possederDroitDashes")
     */
    private $user;



    /**
     * @ORM\ManyToOne(targetEntity=DroitDash::class, inversedBy="possederDroitDash")
     */
    private $droitDash;

    public function getId(): ?int
    {
        return $this->id;
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


    public function setDashboard(?Dashboard $dashboard): self
    {
        $this->dashboard = $dashboard;

        return $this;
    }

    public function getDroitDash(): ?DroitDash
    {
        return $this->droitDash;
    }

    public function setDroitDash(?DroitDash $droitDash): self
    {
        $this->droitDash = $droitDash;

        return $this;
    }
}
