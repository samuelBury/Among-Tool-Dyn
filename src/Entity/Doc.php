<?php

namespace App\Entity;

use App\Repository\DocRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocRepository::class)
 */
class Doc
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
     * @ORM\OneToOne(targetEntity=Carre::class, inversedBy="doc", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $carre;

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

    public function getCarre(): ?Carre
    {
        return $this->carre;
    }

    public function setCarre(Carre $carre): self
    {
        $this->carre = $carre;

        return $this;
    }
}
