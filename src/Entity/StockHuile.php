<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StockHuileRepository;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=StockHuileRepository::class)
 */

class StockHuile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private  $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private  $quantite;

    /**
     * @ORM\Column(type="datetime")
     */
    private  $dateMaj;
     /**
     * @ORM\ManyToOne(targetEntity=TypesHuile::class, inversedBy="stockHuiles")
     */

    
    private  $typeHuile;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getDateMaj(): ?\DateTimeInterface
    {
        return $this->dateMaj;
    }

    public function setDateMaj(\DateTimeInterface $dateMaj): self
    {
        $this->dateMaj = $dateMaj;
        return $this;
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
}
