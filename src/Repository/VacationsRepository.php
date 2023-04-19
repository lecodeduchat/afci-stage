<?php

namespace App\Repository;

use App\Entity\Vacations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vacations>
 *
 * @method Vacations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacations[]    findAll()
 * @method Vacations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vacations::class);
    }

    public function save(Vacations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vacations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Vacations[] Returns an array of Vacations objects
     */
    public function findByDate($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.date = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Vacations
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
