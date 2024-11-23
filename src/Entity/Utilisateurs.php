<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateurs implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomUtilisateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomUtilisateur;

    /**
     * @ORM\OneToMany(targetEntity=CollectesHuile::class, mappedBy="utilisateurs")
     */
    private $collectesHuiles;

    /**
     * @ORM\OneToMany(targetEntity=DemandesProspection::class, mappedBy="utilisateur")
     */
    private $demandesProspections;

    /**
     * @ORM\OneToMany(targetEntity=EntreStock::class, mappedBy="agent")
     */
    private $entreStocks;

    /**
     * @ORM\OneToMany(targetEntity=SortiesStock::class, mappedBy="nommag")
     */
    private $sortiesStocks;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="nommagasinier")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=Rapport::class, mappedBy="agent")
     */
    private $rapports;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->dateCreation = new \DateTime();
        $this->collectesHuiles = new ArrayCollection();
        $this->demandesProspections = new ArrayCollection();
        $this->entreStocks = new ArrayCollection();
        $this->sortiesStocks = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->rapports = new ArrayCollection();
    }

    // Getters and Setters for each attribute

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(?string $nomUtilisateur): self
    {
        $this->nomUtilisateur = $nomUtilisateur;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
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

    public function getPrenomUtilisateur(): ?string
    {
        return $this->prenomUtilisateur;
    }

    public function setPrenomUtilisateur(string $prenomUtilisateur): self
    {
        $this->prenomUtilisateur = $prenomUtilisateur;
        return $this;
    }

    public function eraseCredentials() {}

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return Collection<int, CollectesHuile>
     */
    public function getCollectesHuiles(): Collection
    {
        return $this->collectesHuiles;
    }

    public function addCollectesHuile(CollectesHuile $collectesHuile): self
    {
        if (!$this->collectesHuiles->contains($collectesHuile)) {
            $this->collectesHuiles[] = $collectesHuile;
            $collectesHuile->setUtilisateurs($this);
        }
        return $this;
    }

    public function removeCollectesHuile(CollectesHuile $collectesHuile): self
    {
        if ($this->collectesHuiles->removeElement($collectesHuile)) {
            if ($collectesHuile->getUtilisateurs() === $this) {
                $collectesHuile->setUtilisateurs(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, DemandesProspection>
     */
    public function getDemandesProspections(): Collection
    {
        return $this->demandesProspections;
    }

  

  

    // Similar getter and setter methods for entreStocks, sortiesStocks, stocks, and rapports

    public function __toString(): string
    {
        return $this->email;
    }
}
