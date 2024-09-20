<?php

namespace App\Repository;

use App\Entity\FormulairesVendeurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormulairesVendeurs>
 *
 * @method FormulairesVendeurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulairesVendeurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulairesVendeurs[]    findAll()
 * @method FormulairesVendeurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulairesVendeursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulairesVendeurs::class);
    }

    public function add(FormulairesVendeurs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FormulairesVendeurs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FormulairesVendeurs[] Returns an array of FormulairesVendeurs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FormulairesVendeurs
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
