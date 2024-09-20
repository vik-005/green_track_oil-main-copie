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
    private $role = [];

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
    private $prenomutilisateur;

    /**
     * @ORM\OneToMany(targetEntity=CollectesHuile::class, mappedBy="utilisateurs")
     */
    private $collectesHuiles;

    public function __construct()
    {
        $this->role = ['ROLE_USER'];
        $this->dateCreation = new \DateTime();
        $this->collectesHuiles = new ArrayCollection();
    }

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
        $roles = $this->role;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->role = $roles;

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

    public function getPrenomutilisateur(): ?string
    {
        return $this->prenomutilisateur;
    }

    public function setPrenomutilisateur(string $prenomutilisateur): self
    {
        $this->prenomutilisateur = $prenomutilisateur;

        return $this;
    }

    public function eraseCredentials()
    {
        // Nettoyer les données sensibles
    }

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
     * Méthode magique __toString()
     * Permet de retourner une représentation en chaîne de l'utilisateur.
     */
    public function __toString(): string
    {
        return $this->email; // Par exemple, retourner l'email comme représentation en chaîne
    }
}
