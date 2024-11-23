<?php

namespace App\Repository;

use App\Entity\StockHuile;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<StockHuile>
 *
 * @method StockHuile|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockHuile|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockHuile[]    findAll()
 * @method StockHuile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockHuileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockHuile::class);
    }

    /**
     * Enregistre une nouvelle instance de StockHuile
     */
    public function save(StockHuile $stockHuile, bool $flush = false): void
    {
        $this->_em->persist($stockHuile);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Supprime une instance de StockHuile
     */
    public function remove(StockHuile $stockHuile, bool $flush = false): void
    {
        $this->_em->remove($stockHuile);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Exemples de méthodes de recherche personnalisées.
     */

    // Trouve les stocks d'huile par type d'huile
    public function findByTypeHuile($typeHuile): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.typeHuile = :typeHuile')
            ->setParameter('typeHuile', $typeHuile)
            ->orderBy('s.dateMaj', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Trouve les stocks d'huile modifiés récemment
    public function findRecentUpdates(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateMaj > :date')
            ->setParameter('date', $date)
            ->orderBy('s.dateMaj', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
