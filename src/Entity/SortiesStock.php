<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SortiesStockRepository;

/**
 * @ORM\Entity(repositoryClass=SortiesStockRepository::class)
 */
class SortiesStock
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
    private $volume;

    /**
     * @ORM\Column(type="float")
     */
    private $prixVente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSorti;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nummat;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="sortiesStocks")
     */
    private $nommag;
    /**
     * @ORM\ManyToOne(targetEntity=TypesHuile::class, inversedBy="sortiesStocks")
     */


    private  $typeHuile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getDateSorti(): ?\DateTimeInterface
    {
        return $this->dateSorti;
    }

    public function setDateSorti(\DateTimeInterface $dateSorti): self
    {
        $this->dateSorti = $dateSorti;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getNummat(): ?string
    {
        return $this->nummat;
    }

    public function setNummat(string $nummat): self
    {
        $this->nummat = $nummat;

        return $this;
    }

    public function getNommag(): ?Utilisateurs
    {
        return $this->nommag;
    }

    public function setNommag(?Utilisateurs $nommag): self
    {
        $this->nommag = $nommag;

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
