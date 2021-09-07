<?php

namespace App\Repository;

use App\Entity\DoctorCustomers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctorCustomers|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorCustomers|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorCustomers[]    findAll()
 * @method DoctorCustomers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorCustomersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorCustomers::class);
    }

    // /**
    //  * @return DoctorCustomers[] Returns an array of DoctorCustomers objects
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
    public function findOneBySomeField($value): ?DoctorCustomers
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
