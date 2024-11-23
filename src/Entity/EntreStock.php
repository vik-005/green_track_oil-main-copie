<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EntreStockRepository;

/**
 * @ORM\Entity(repositoryClass=EntreStockRepository::class)
 */
class EntreStock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Vendeurs::class, inversedBy="entreStocks")
     */
    private $vendeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombrebidons;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prixunitaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnregisterement;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="entreStocks")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity=TypesHuile::class, inversedBy="entreStocks")
     */
    private $typehuile;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="entreStock")
     */
    private $nommagasinier;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $modificationCount = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastModifiedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVendeur(): ?Vendeurs
    {
        return $this->vendeur;
    }

    public function setVendeur(?Vendeurs $vendeur): self
    {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getNombrebidons(): ?string
    {
        return $this->nombrebidons;
    }

    public function setNombrebidons(string $nombrebidons): self
    {
        $this->nombrebidons = $nombrebidons;

        return $this;
    }

    public function getPrixunitaire(): ?string
    {
        return $this->prixunitaire;
    }

    public function setPrixunitaire(string $prixunitaire): self
    {
        $this->prixunitaire = $prixunitaire;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDateEnregisterement(): ?\DateTimeInterface
    {
        return $this->dateEnregisterement;
    }

    public function setDateEnregisterement(\DateTimeInterface $dateEnregisterement): self
    {
        $this->dateEnregisterement = $dateEnregisterement;

        return $this;
    }

    public function getAgent(): ?Utilisateurs
    {
        return $this->agent;
    }

    public function setAgent(?Utilisateurs $agent): self
    {
        $this->agent = $agent;

        return $this;
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

    public function getNommagasinier(): ?Utilisateurs
    {
        return $this->nommagasinier;
    }

    public function setNommagasinier(?Utilisateurs $nommagasinier): self
    {
        $this->nommagasinier = $nommagasinier;

        return $this;
    }

    public function getModificationCount(): ?int
    {
        return $this->modificationCount;
    }

    public function setModificationCount(int $count): self
    {
        $this->modificationCount = $count;

        return $this;
    }
    public function getLastModifiedBy(): ?string
    {
        return $this->lastModifiedBy;
    }

    public function setLastModifiedBy(string $lastModifiedBy): self
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    public function canShowToManager()
    {
        if ($this->typehuile->getNomTypeHuile() == 'factory') {
            return false;
        }
        return true;
    }
}