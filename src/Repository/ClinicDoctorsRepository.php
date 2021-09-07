<?php

namespace App\Repository;

use App\Entity\ClinicDoctors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClinicDoctors|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClinicDoctors|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClinicDoctors[]    findAll()
 * @method ClinicDoctors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClinicDoctorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClinicDoctors::class);
    }

    // /**
    //  * @return ClinicUsers[] Returns an array of ClinicUsers objects
    //  */

    public function findClinicAndDoctorPair($dentist, $clinic)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.clinic = :clinic')
            ->andWhere('c.doctor = :dentist')
            ->setParameter('dentist', $dentist)
            ->setParameter('clinic', $clinic)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ClinicUsers
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
