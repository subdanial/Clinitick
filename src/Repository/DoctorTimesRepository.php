<?php

namespace App\Repository;

use App\Entity\DoctorTimes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctorTimes|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorTimes|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorTimes[]    findAll()
 * @method DoctorTimes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorTimesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorTimes::class);
    }

    // /**
    //  * @return DoctorTimes[] Returns an array of DoctorTimes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DoctorTimes
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
