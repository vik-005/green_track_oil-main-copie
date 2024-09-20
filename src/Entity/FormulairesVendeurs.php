<?php

namespace App\Entity;

use App\Repository\FormulairesVendeursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormulairesVendeursRepository::class)
 */
class FormulairesVendeurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=TypeVendeur::class, inversedBy="formulairesVendeurs")
     */
    private $typesvendeurs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getTypesvendeurs(): ?TypeVendeur
    {
        return $this->typesvendeurs;
    }

    public function setTypesvendeurs(?TypeVendeur $typesvendeurs): self
    {
        $this->typesvendeurs = $typesvendeurs;

        return $this;
    }
}
