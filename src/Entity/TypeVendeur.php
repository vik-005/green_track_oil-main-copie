<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeVendeurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TypeVendeurRepository::class)
 */
class TypeVendeur
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
    private $nomType;

    /**
     * @ORM\OneToMany(targetEntity=FormulairesVendeurs::class, mappedBy="typesvendeurs")
     */
    private $formulairesVendeurs;

    /**
     * @ORM\OneToMany(targetEntity=Vendeurs::class, mappedBy="typevendeur")
     */
    private $vendeurs;

    public function __construct()
    {
        $this->formulairesVendeurs = new ArrayCollection();
        $this->vendeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function setNomType(string $nomType): self
    {
        $this->nomType = $nomType;

        return $this;
    }

    /**
     * @return Collection<int, FormulairesVendeurs>
     */
    public function getFormulairesVendeurs(): Collection
    {
        return $this->formulairesVendeurs;
    }

    public function addFormulairesVendeur(FormulairesVendeurs $formulairesVendeur): self
    {
        if (!$this->formulairesVendeurs->contains($formulairesVendeur)) {
            $this->formulairesVendeurs[] = $formulairesVendeur;
            $formulairesVendeur->setTypesvendeurs($this);
        }

        return $this;
    }
    
    
    public function removeFormulairesVendeur(FormulairesVendeurs $formulairesVendeur): self
    {
        if ($this->formulairesVendeurs->removeElement($formulairesVendeur)) {
            // set the owning side to null (unless already changed)
            if ($formulairesVendeur->getTypesvendeurs() === $this) {
                $formulairesVendeur->setTypesvendeurs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vendeurs>
     */
    public function getVendeurs(): Collection
    {
        return $this->vendeurs;
    }

    public function addVendeur(Vendeurs $vendeur): self
    {
        if (!$this->vendeurs->contains($vendeur)) {
            $this->vendeurs[] = $vendeur;
            $vendeur->setTypevendeur($this);
        }

        return $this;
    }

    public function removeVendeur(Vendeurs $vendeur): self
    {
        if ($this->vendeurs->removeElement($vendeur)) {
            // set the owning side to null (unless already changed)
            if ($vendeur->getTypevendeur() === $this) {
                $vendeur->setTypevendeur(null);
            }
        }

        return $this;
    }

}
