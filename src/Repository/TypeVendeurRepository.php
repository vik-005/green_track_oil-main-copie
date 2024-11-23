<?php

namespace App\Repository;

use App\Entity\TypeVendeur;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<TypeVendeur>
 *
 * @method TypeVendeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeVendeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeVendeur[]    findAll()
 * @method TypeVendeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeVendeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeVendeur::class);
    }

    public function add(TypeVendeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeVendeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TypeVendeur[] Returns an array of TypeVendeur objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :val')  // Remplacez 'name' par le champ que vous souhaitez filtrer
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return TypeVendeur|null Returns a TypeVendeur object or null
     */
    public function findOneBySomeField($value): ?TypeVendeur
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :val')  // Remplacez 'name' par le champ que vous souhaitez filtrer
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne le nombre total de types de vendeurs.
     */
    public function countTotalTypes(): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Retourne les types de vendeurs triés par nom.
     * 
     * @return TypeVendeur[] Returns an array of TypeVendeur objects sorted by name
     */
    public function findAllSortedByName(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les types de vendeurs qui contiennent une chaîne de caractères dans leur nom.
     * 
     * @param string $searchTerm
     * @return TypeVendeur[]
     */
    public function findByNameContains(string $searchTerm): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
