<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DemandesProspectionRepository;

/**
 * @ORM\Entity(repositoryClass=DemandesProspectionRepository::class)
 */
class DemandesProspection
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
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut = 'en_attente';

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateApprobation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=TypeVendeur::class, inversedBy="demandesProspections")
       
     */
    private $typevendeur;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="demandesProspections")
     */
    private $agent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $montant;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

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

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): self
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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

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

    public function getAgent(): ?Utilisateurs
    {
        return $this->agent;
    }

    public function setAgent(?Utilisateurs $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }
}
