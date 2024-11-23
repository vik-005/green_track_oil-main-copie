<?php

namespace App\Service;

use App\Entity\CollectesHuile;
use Doctrine\ORM\EntityManagerInterface;

class StockService
{
    private  $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateStock(CollectesHuile $collectesHuile): void
    {
        // Logique de mise à jour du stock
        // Vous pouvez ici modifier les quantités de stock en fonction de la collecte approuvée

        // Exemple : $collectesHuile->getVolume() pour ajuster le stock
        // $stock->ajouterVolume($collectesHuile->getVolume());

        $this->entityManager->flush(); // Sauvegarde les changements
    }
}