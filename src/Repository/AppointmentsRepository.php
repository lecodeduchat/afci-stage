<?php

namespace App\Repository;

use App\Entity\Cares;
use App\Entity\Appointments;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Appointments>
 *
 * @method Appointments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointments[]    findAll()
 * @method Appointments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointments::class);
    }

    public function save(Appointments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param \DateTimeInterface $date Date depuis laquelle on veut récupérer les rendez-vous
     * @return Appointments[] Retourne un tableau de rendez-vous depuis une date donnée
     */
    public function findAllSince($date): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin(Cares::class, 'c', 'WITH', 'c = a.care')
            ->andWhere('a.date >= :val')
            ->setParameter('val', $date)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        // dd($this->createQueryBuilder('a')->getQuery()->getSQL());
    }

    //    public function findOneBySomeField($value): ?Appointments
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
