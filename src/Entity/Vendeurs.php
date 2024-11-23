<?php

// src/Entity/Vendeurs.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VendeursRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VendeursRepository::class)
 */
class Vendeurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du vendeur ne peut pas être vide.")
     */
    private $nomVendeur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse ne peut pas être vide.")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La ville ne peut pas être vide.")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le pays ne peut pas être vide.")
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le téléphone ne peut pas être vide.")
     * @Assert\Regex(
     *     pattern="/^\+?[0-9]{7,15}$/",
     *     message="Veuillez entrer un numéro de téléphone valide."
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'email ne peut pas être vide.")
     * @Assert\Email(message="Veuillez entrer une adresse email valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull(message="La date de création ne peut pas être nulle.")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=TypeVendeur::class, inversedBy="vendeurs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Le type de vendeur doit être sélectionné.")
     */
    private $typevendeur;

    /**
     * @ORM\OneToMany(targetEntity=CollectesHuile::class, mappedBy="vendeurs")
     */
    private $collectesHuiles;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo ;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="La latitude ne peut pas être nulle.")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="La longitude ne peut pas être nulle.")
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=EntreStock::class, mappedBy="vendeur")
     */
    private $entreStocks;

    public function __construct()
    {
        $this->collectesHuiles = new ArrayCollection();
        $this->entreStocks = new ArrayCollection();
        $this->dateCreation = new \DateTime(); // Initialiser dateCreation lors de la création d'un vendeur
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomVendeur(): ?string
    {
        return $this->nomVendeur;
    }

    public function setNomVendeur(string $nomVendeur): self
    {
        $this->nomVendeur = $nomVendeur;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Formate la date de création.
     */
    public function getFormattedDateCreation(): string
    {
        return $this->dateCreation ? $this->dateCreation->format('Y-m-d H:i:s') : '';
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function updateTimestamps(): self
    {
        $this->setUpdatedAt(new \DateTime());

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
            $collectesHuile->setVendeurs($this);
        }

        return $this;
    }

    public function removeCollectesHuile(CollectesHuile $collectesHuile): self
    {
        if ($this->collectesHuiles->removeElement($collectesHuile)) {
            // set the owning side to null (unless already changed)
            if ($collectesHuile->getVendeurs() === $this) {
                $collectesHuile->setVendeurs(null);
            }
        }

        return $this;
    }

    public function getPhoto(): ?array
    {
        return $this->photo;
    }

  
    public function setPhoto(?array $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, EntreStock>
     */
    public function getEntreStocks(): Collection
    {
        return $this->entreStocks;
    }

    public function addEntreStock(EntreStock $entreStock): self
    {
        if (!$this->entreStocks->contains($entreStock)) {
            $this->entreStocks[] = $entreStock;
            $entreStock->setVendeur($this);
        }

        return $this;
    }

    public function removeEntreStock(EntreStock $entreStock): self
    {
        if ($this->entreStocks->removeElement($entreStock)) {
            // set the owning side to null (unless already changed)
            if ($entreStock->getVendeur() === $this) {
                $entreStock->setVendeur(null);
            }
        }

        return $this;
    }
}