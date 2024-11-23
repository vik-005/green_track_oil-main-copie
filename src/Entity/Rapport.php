<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RapportRepository;

/**
 * @ORM\Entity(repositoryClass=RapportRepository::class)
 */
class Rapport
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
    private $numresto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomrestaurant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rencontre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $superieure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Statut = 'en_attente';

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="rapports")
     */
    private $agent;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateApprobation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumresto(): ?string
    {
        return $this->numresto;
    }

    public function setNumresto(string $numresto): self
    {
        $this->numresto = $numresto;

        return $this;
    }

    public function getNomrestaurant(): ?string
    {
        return $this->nomrestaurant;
    }

    public function setNomrestaurant(string $nomrestaurant): self
    {
        $this->nomrestaurant = $nomrestaurant;

        return $this;
    }

    public function getRencontre(): ?string
    {
        return $this->rencontre;
    }

    public function setRencontre(string $rencontre): self
    {
        $this->rencontre = $rencontre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getSuperieure(): ?string
    {
        return $this->superieure;
    }

    public function setSuperieure(?string $superieure): self
    {
        $this->superieure = $superieure;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->Statut;
    }

    public function getStatutLabel(): ?string
    {
        $label = null;

        switch ($this->Statut) {
            case 'en_attente':
                $label = "En attente";
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

    public function setStatut(string $Statut): self
    {
        $this->Statut = $Statut;

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

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(?\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getDateApprobation(): ?\DateTimeInterface
    {
        return $this->dateApprobation;
    }

    public function setDateApprobation(?\DateTimeInterface $dateApprobation): self
    {
        $this->dateApprobation = $dateApprobation;

        return $this;
    }
}
