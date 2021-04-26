<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
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
    private $urlDoc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tmpName;

    /**
     * @ORM\OneToOne(targetEntity=ligne::class, cascade={"persist", "remove"})
     */
    private $ligne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlDoc(): ?string
    {
        return $this->urlDoc;
    }

    public function setUrlDoc(string $urlDoc): self
    {
        $this->urlDoc = $urlDoc;

        return $this;
    }

    public function getTmpName(): ?string
    {
        return $this->tmpName;
    }

    public function setTmpName(string $tmpName): self
    {
        $this->tmpName = $tmpName;

        return $this;
    }

    public function getLigne(): ?ligne
    {
        return $this->ligne;
    }

    public function setLigne(?ligne $ligne): self
    {
        $this->ligne = $ligne;

        return $this;
    }
}
