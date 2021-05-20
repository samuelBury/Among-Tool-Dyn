<?php

namespace App\Entity;

use App\Repository\TravaillerSurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TravaillerSurRepository::class)
 */
class TravaillerSur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="travaillerSurs")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Dashboard::class, inversedBy="travaillerSurs")
     */
    private $Dashboard;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDashboard(): ?Dashboard
    {
        return $this->Dashboard;
    }

    public function setDashboard(?Dashboard $Dashboard): self
    {
        $this->Dashboard = $Dashboard;

        return $this;
    }
}
