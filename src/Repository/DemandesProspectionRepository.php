<?php

namespace App\Repository;

use App\Entity\DemandesProspection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Func;

/**
 * @extends ServiceEntityRepository<DemandesProspection>
 *
 * Ce repository gère les requêtes vers l'entité DemandesProspection
 */
class DemandesProspectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandesProspection::class);
    }

    
}