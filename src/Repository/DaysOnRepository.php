<?php

namespace App\Repository;

use App\Entity\DaysOn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DaysOn>
 *
 * @method DaysOn|null find($id, $lockMode = null, $lockVersion = null)
 * @method DaysOn|null findOneBy(array $criteria, array $orderBy = null)
 * @method DaysOn[]    findAll()
 * @method DaysOn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DaysOnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DaysOn::class);
    }

    public function save(DaysOn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DaysOn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return DaysOn[] Returns an array of DaysOn objects
     */
    public function findAllSince($date): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.date >= :val')
            ->setParameter('val', $date)
            ->orderBy('d.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?DaysOn
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
