<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CollectesHuileRepository;

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
     * @ORM\Column(type="json")
     */
    private $photoBidons = [];

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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut = 'en_attente';

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="Collectes")
     */
    private $nomMagasinier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaireMagasinier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaireSuperieur;

    /**
     * @ORM\ManyToOne(targetEntity=TypeVendeur::class, inversedBy="collectesHuiles")
     */
    private $typevendeur;

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

    public function getPhotoBidons(): array
    {
        return $this->photoBidons;
    }

    public function setPhotoBidons(array $photoBidons): self
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

    public function getTotal(): ?float
    {

        if (!$this->volume || !$this->prixAchat) {
            return null;
        }
        return $this->volume * $this->prixAchat;
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
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function getStatutLabel(): ?string
    {
        $label = null;

        switch ($this->statut) {
            case 'en_attente':
                $label = "en_attente";
                break;
            case 'approuve':
                $label = "Approuvé";
                break;
            case 'rejete':
                $label = "Rejeté";
                break;
            default:
                $label = "Indisponible";
                break;
        }

        return $label;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getNomMagasinier(): ?Utilisateurs
    {
        return $this->nomMagasinier;
    }

    public function setNomMagasinier(?Utilisateurs $nomMagasinier): self
    {
        $this->nomMagasinier = $nomMagasinier;

        return $this;
    }

    public function getCommentaireMagasinier(): ?string
    {
        return $this->commentaireMagasinier;
    }

    public function setCommentaireMagasinier(?string $commentaireMagasinier): self
    {
        $this->commentaireMagasinier = $commentaireMagasinier;

        return $this;
    }

    public function getCommentaireSuperieur(): ?string
    {
        return $this->commentaireSuperieur;
    }

    public function setCommentaireSuperieur(?string $commentaireSuperieur): self
    {
        $this->commentaireSuperieur = $commentaireSuperieur;

        return $this;
    }

    public function getTypevendeur(): ?TypeVendeur
    {
        return $this->typevendeur;
    }

    public function setTypevendeur(?TypeVendeur $typevendeur): self
    {
        $this->typevendeur = $typevendeur;

        return $this;
    }
}