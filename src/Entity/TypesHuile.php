<?php

namespace App\Entity;

use App\Repository\TypesHuileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    public function __construct()
    {
        $this->collectesHuiles = new ArrayCollection();
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
}
