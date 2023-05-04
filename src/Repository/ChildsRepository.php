<?php

namespace App\Repository;

use App\Entity\Users;
use App\Entity\Childs;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Childs>
 *
 * @method Childs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Childs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Childs[]    findAll()
 * @method Childs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Childs::class);
    }

    public function save(Childs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Childs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Childs[] Returns an array of Childs objects
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin(Users::class, 'u', 'WITH', 'u = c.parent1 OR u = c.parent2')
            ->andWhere('c.parent1 = :val OR c.parent2 = :val')
            ->setParameter('val', $user)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLastChildByUser($user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin(Users::class, 'u', 'WITH', 'u = c.parent1 OR u = c.parent2')
            ->andWhere('c.parent1 = :val OR c.parent2 = :val')
            ->setParameter('val', $user)
            ->orderBy('c.id', 'Desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
