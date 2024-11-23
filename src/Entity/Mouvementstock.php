<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MouvementstockRepository;

/**
 * @ORM\Entity(repositoryClass=MouvementstockRepository::class)
 */
class Mouvementstock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TypesHuile::class, inversedBy="mouvementstocks")
     */
    private $typeHuile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeMouvement;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateMouvement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeHuile(): ?TypesHuile
    {
        return $this->typeHuile;
    }

    public function setTypeHuile(?TypesHuile $typeHuile): self
    {
        $this->typeHuile = $typeHuile;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getTypeMouvement(): ?string
    {
        return $this->typeMouvement;
    }

    public function setTypeMouvement(string $typeMouvement): self
    {
        $this->typeMouvement = $typeMouvement;

        return $this;
    }

    public function getDateMouvement(): ?\DateTimeInterface
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(\DateTimeInterface $dateMouvement): self
    {
        $this->dateMouvement = $dateMouvement;

        return $this;
    }
}
