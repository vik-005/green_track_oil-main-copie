<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypesHuileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TypesHuileRepository::class)
 */
class TypesHuile
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
    private $nomTypeHuile;

    /**
     * @ORM\OneToMany(targetEntity=CollectesHuile::class, mappedBy="typehuile")
     */
    private $collectesHuiles;

    /**
     * @ORM\OneToMany(targetEntity=EntreStock::class, mappedBy="typehuile")
     */
    private $entreStocks;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="typehuile")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=StockHuile::class, mappedBy="typeHuile")
     */
    private $stockHuiles;

    /**
     * @ORM\OneToMany(targetEntity=Mouvementstock::class, mappedBy="typeHuile")
     */
    private $mouvementstocks;

    /**
     * @ORM\OneToMany(targetEntity=SortiesStock::class, mappedBy="typehuile")
     */
    private $sortiesStocks;

    public function __construct()
    {
        $this->collectesHuiles = new ArrayCollection();
        $this->entreStocks = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->stockHuiles = new ArrayCollection();
        $this->mouvementstocks = new ArrayCollection();
        $this->sortiesStocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTypeHuile(): ?string
    {
        return $this->nomTypeHuile;
    }

    public function setNomTypeHuile(string $nomTypeHuile): self
    {
        $this->nomTypeHuile = $nomTypeHuile;

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
            $collectesHuile->setTypehuile($this);
        }

        return $this;
    }

    public function removeCollectesHuile(CollectesHuile $collectesHuile): self
    {
        if ($this->collectesHuiles->removeElement($collectesHuile)) {
            // set the owning side to null (unless already changed)
            if ($collectesHuile->getTypehuile() === $this) {
                $collectesHuile->setTypehuile(null);
            }
        }

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
            $entreStock->setTypehuile($this);
        }

        return $this;
    }

    public function removeEntreStock(EntreStock $entreStock): self
    {
        if ($this->entreStocks->removeElement($entreStock)) {
            // set the owning side to null (unless already changed)
            if ($entreStock->getTypehuile() === $this) {
                $entreStock->setTypehuile(null);
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
            $stock->setTypehuile($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getTypehuile() === $this) {
                $stock->setTypehuile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StockHuile>
     */
    public function getStockHuiles(): Collection
    {
        return $this->stockHuiles;
    }

    public function addStockHuile(StockHuile $stockHuile): self
    {
        if (!$this->stockHuiles->contains($stockHuile)) {
            $this->stockHuiles[] = $stockHuile;
            $stockHuile->setTypeHuile($this);
        }

        return $this;
    }

    public function removeStockHuile(StockHuile $stockHuile): self
    {
        if ($this->stockHuiles->removeElement($stockHuile)) {
            // set the owning side to null (unless already changed)
            if ($stockHuile->getTypeHuile() === $this) {
                $stockHuile->setTypeHuile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mouvementstock>
     */
    public function getMouvementstocks(): Collection
    {
        return $this->mouvementstocks;
    }

    public function addMouvementstock(Mouvementstock $mouvementstock): self
    {
        if (!$this->mouvementstocks->contains($mouvementstock)) {
            $this->mouvementstocks[] = $mouvementstock;
            $mouvementstock->setTypeHuile($this);
        }

        return $this;
    }

    public function removeMouvementstock(Mouvementstock $mouvementstock): self
    {
        if ($this->mouvementstocks->removeElement($mouvementstock)) {
            // set the owning side to null (unless already changed)
            if ($mouvementstock->getTypeHuile() === $this) {
                $mouvementstock->setTypeHuile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SortiesStock>
     */
    public function getSortiesStocks(): Collection
    {
        return $this->sortiesStocks;
    }

    public function addSortiesStock(SortiesStock $sortiesStock): self
    {
        if (!$this->sortiesStocks->contains($sortiesStock)) {
            $this->sortiesStocks[] = $sortiesStock;
            $sortiesStock->setTypehuile($this);
        }

        return $this;
    }

    public function removeSortiesStock(SortiesStock $sortiesStock): self
    {
        if ($this->sortiesStocks->removeElement($sortiesStock)) {
            // set the owning side to null (unless already changed)
            if ($sortiesStock->getTypehuile() === $this) {
                $sortiesStock->setTypehuile(null);
            }
        }

        return $this;
    }
}
