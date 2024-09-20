<?php

namespace App\Entity;

use App\Repository\CollectesHuileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollectesHuileRepository::class)
 */
class CollectesHuile
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
     * @ORM\Column(type="string", length=255)
     */
    private $photoBidons;

    /**
     * @ORM\Column(type="float")
     */
    private $prixAchat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCollecte;

    /**
     * @ORM\ManyToOne(targetEntity=Vendeurs::class, inversedBy="collectesHuiles")
     */
    private $vendeurs;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="collectesHuiles")
     */
    private $utilisateurs;

    /**
     * @ORM\ManyToOne(targetEntity=TypesHuile::class, inversedBy="collectesHuiles")
     */
    private $typehuile;

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

    public function getPhotoBidons(): ?string
    {
        return $this->photoBidons;
    }

    public function setPhotoBidons(string $photoBidons): self
    {
        $this->photoBidons = $photoBidons;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(float $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getDateCollecte(): ?\DateTimeInterface
    {
        return $this->dateCollecte;
    }

    public function setDateCollecte(\DateTimeInterface $dateCollecte): self
    {
        $this->dateCollecte = $dateCollecte;

        return $this;
    }

    public function getVendeurs(): ?Vendeurs
    {
        return $this->vendeurs;
    }

    public function setVendeurs(?Vendeurs $vendeurs): self
    {
        $this->vendeurs = $vendeurs;

        return $this;
    }

    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateurs $utilisateurs): self
    {
        $this->utilisateurs = $utilisateurs;

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
}
