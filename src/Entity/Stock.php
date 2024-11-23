<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TypesHuile::class, inversedBy="stocks")
     */
    private $typehuile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quantiteInitiale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quantiteStockee;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="stocks")
     */
    private $nommagasinier;

    /**
     * @ORM\ManyToOne(targetEntity=TypeVendeur::class, inversedBy="stocks")
     */
    private $nomvendeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypehuile(): ?TypesHuile
    {
        return $this->typehuile;
    }

    public function setTypehuile(?TypesHuile $typehuile): self
    {
        $this->typehuile = $typehuile;

        return $this;
    }

    public function getQuantiteInitiale(): ?string
    {
        return $this->quantiteInitiale;
    }

    public function setQuantiteInitiale(string $quantiteInitiale): self
    {
        $this->quantiteInitiale = $quantiteInitiale;

        return $this;
    }

    public function getQuantiteStockee(): ?string
    {
        return $this->quantiteStockee;
    }

    public function setQuantiteStockee(string $quantiteStockee): self
    {
        $this->quantiteStockee = $quantiteStockee;

        return $this;
    }

    public function getNommagasinier(): ?Utilisateurs
    {
        return $this->nommagasinier;
    }

    public function setNommagasinier(?Utilisateurs $nommagasinier): self
    {
        $this->nommagasinier = $nommagasinier;

        return $this;
    }

    public function getNomvendeur(): ?TypeVendeur
    {
        return $this->nomvendeur;
    }

    public function setNomvendeur(?TypeVendeur $nomvendeur): self
    {
        $this->nomvendeur = $nomvendeur;

        return $this;
    }
}
