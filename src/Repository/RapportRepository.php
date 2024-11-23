<?php

namespace App\Repository;

use App\Entity\Rapport;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Rapport>
 *
 * @method Rapport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rapport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rapport[]    findAll()
 * @method Rapport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rapport::class);
    }

    public function add(Rapport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Rapport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find rapports by status.
     *
     * @param string $status
     * @return Rapport[]
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.Statut = :status')
            ->setParameter('status', $status)
            ->orderBy('r.dateDemande', 'DESC') // Sort by date of request
            ->getQuery()
            ->getResult();
    }

    /**
     * Find rapports by agent.
     *
     * @param int $agentId
     * @return Rapport[]
     */
    public function findByAgent(int $agentId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.agent = :agentId')
            ->setParameter('agentId', $agentId)
            ->orderBy('r.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Count rapports by status.
     *
     * @param string $status
     * @return int
     */
    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.Statut = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get all rapports with pagination.
     *
     * @param int $limit
     * @param int $offset
     * @return Rapport[]
     */
    public function findAllWithPagination(int $limit, int $offset): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.dateDemande', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
