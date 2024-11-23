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

    /**
     * @ORM\OneToMany(targetEntity=DemandesProspection::class, mappedBy="typevendeur")
     */
    private $demandesProspections;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="nomvendeur")
     */
    private $stocks;

    public function __construct()
    {
        $this->formulairesVendeurs = new ArrayCollection();
        $this->vendeurs = new ArrayCollection();
        $this->demandesProspections = new ArrayCollection();
        $this->stocks = new ArrayCollection();
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

    /**
     * @return Collection<int, DemandesProspection>
     */
    public function getDemandesProspections(): Collection
    {
        return $this->demandesProspections;
    }

    public function addDemandesProspection(DemandesProspection $demandesProspection): self
    {
        if (!$this->demandesProspections->contains($demandesProspection)) {
            $this->demandesProspections[] = $demandesProspection;
            $demandesProspection->setTypevendeur($this);
        }

        return $this;
    }

    public function removeDemandesProspection(DemandesProspection $demandesProspection): self
    {
        if ($this->demandesProspections->removeElement($demandesProspection)) {
            // set the owning side to null (unless already changed)
            if ($demandesProspection->getTypevendeur() === $this) {
                $demandesProspection->setTypevendeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setNomvendeur($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getNomvendeur() === $this) {
                $stock->setNomvendeur(null);
            }
        }

        return $this;
    }

}
