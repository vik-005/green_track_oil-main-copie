<?php

namespace App\Entity;

use App\Repository\SortiesStockRepository;
use Doctrine\ORM\Mapping as ORM;

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
}
