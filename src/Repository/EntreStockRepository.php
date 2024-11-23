<?php

namespace App\Repository;

use App\Entity\EntreStock;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<EntreStock>
 *
 * @method EntreStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntreStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntreStock[]    findAll()
 * @method EntreStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntreStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntreStock::class);
    }

    public function add(EntreStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EntreStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Retourne un tableau d'objets EntreStock selon un champ spécifique.
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // Retourne une seule entrée selon un champ spécifique.
    public function findOneBySomeField($value): ?EntreStock
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Méthode pour obtenir le total des entrées de stock par mois.
    public function findTotalEntriesPerMonth(): array
    {
        return $this->createQueryBuilder('e')
            ->select('MONTH(e.date) as month, SUM(e.volume) as totalVolume')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour obtenir le total des entrées de stock par type de produit.
    public function findEntriesPerProductType(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.productType, SUM(e.volume) as totalVolume')
            ->groupBy('e.productType')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour obtenir le volume total d'entrées de stock.
    public function getTotalVolumeEntries(): float
    {
        return (float) $this->createQueryBuilder('e')
            ->select('SUM(e.volume)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
