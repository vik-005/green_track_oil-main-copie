<?php

namespace App\Repository;

use App\Entity\CollectesHuile;
use App\Entity\TypesHuile;
use App\Entity\Utilisateurs;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\Request;

class CollectesHuileRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectesHuile::class);
    }

    /**
     * Ajouter une nouvelle collecte d'huile
     */
    public function add(CollectesHuile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprimer une collecte d'huile
     */
    public function remove(CollectesHuile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    /**
     * Requête 2 : Quantité d'huile collectée par agent
     * @return array
     */
    public function findOilCollectedPerAgent(): array
    {
        return $this->createQueryBuilder('c')
            ->select('a.nom as agentName, SUM(c.volume) as totalVolume')
            ->join('c.agent', 'a')
            ->groupBy('agentName')
            ->orderBy('totalVolume', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Requête 3 : Quantité d'huile collectée par type de vendeur
     * (restaurant, hôtel, industrie)
     * @return array
     */
    public function findOilCollectedPerVendorType(): array
    {
        return $this->createQueryBuilder('c')
            ->select('t.libelle as vendorType, SUM(c.volume) as totalVolume')
            ->join('c.vendeur', 'v')
            ->join('v.type', 't')
            ->groupBy('vendorType')
            ->orderBy('totalVolume', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Requête 4 : Volume d'huile collectée par année
     * @return array
     */
    public function findTotalOilCollectedPerYear(): array
    {
        return $this->createQueryBuilder('c')
            ->select('YEAR(c.dateCollection) as year, SUM(c.volume) as totalVolume')
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Requête 5 : Nombre total de collectes effectuées par mois
     * @return array
     */
    public function findTotalCollectionsPerMonth(): array
    {
        return $this->createQueryBuilder('c')
            ->select('MONTH(c.dateCollection) as month, COUNT(c.id) as totalCollections')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Compter le volume total collecté
    public function getTotalVolumeCollected(): float
    {
        return (float) $this->createQueryBuilder('c')
            ->select('SUM(c.volume)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Obtenir les clients principaux par nombre de collectes
    public function getTopClients(): array
    {
        return $this->createQueryBuilder('c')
            ->select('v.nom AS client, COUNT(c.id) AS total_collectes')
            ->join('c.vendeur', 'v')
            ->groupBy('v.id')
            ->orderBy('total_collectes', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }




    // Requête pour le total d'huile collectée par mois
    public function findMonthlyCollectionVolumes(): array
    {
        return $this->createQueryBuilder('c')
            ->select('MONTH(c.dateCollecte) AS month, SUM(c.volume) AS totalVolume')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Requête pour le total d'huile collectée par type
    public function findCollectionByOilType(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.typehuile', 't')
            ->select('t.nom AS oilType, SUM(c.volume) AS totalVolume')
            ->groupBy('oilType')
            ->orderBy('totalVolume', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Requête pour le total d'huile collectée par vendeur
    public function findCollectionByVendorType(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.typevendeur', 'tv')
            ->select('tv.nom AS vendorType, SUM(c.volume) AS totalVolume')
            ->groupBy('vendorType')
            ->orderBy('totalVolume', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CollectesHuile[]
     */
    public function searchQuery(Request $request, ?Utilisateurs $utilisateurs = null)
    {
        $qb = $this->createQueryBuilder('ch');

        if ($filter = $request->query->get('filter')) {
            $qb->andWhere('ch.statut = :var_1')
                ->setParameter('var_1', $filter);
        }
        
        if ($dateStart = $request->query->get('date_start')) {
            $qb->andWhere('ch.dateCollecte >= :var_2')
                ->setParameter('var_2',  new DateTime($dateStart));
        }
        if ($dateend = $request->query->get('date_end')) {
            $qb->andWhere('ch.dateCollecte <= :var_3')
                ->setParameter('var_3', new DateTime($dateend));
        }
        if ($typeHuileId = $request->query->get('typehuile')) {
            $qb->join(TypesHuile::class, 'th', Join::WITH, 'th = ch.typehuile')
                ->andWhere('th.id = :var_4')
                ->setParameter('var_4',  $typeHuileId);
        }
        if ($utilisateurs) {
            $qb->andWhere('ch.utilisateurs = :var_5')
                ->setParameter('var_5', $utilisateurs);
        }
        
        return $qb->getQuery()->getResult();
    }
}