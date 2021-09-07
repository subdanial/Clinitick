<?php

namespace App\Repository;

use App\Entity\ClinicAssistants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClinicAssistants|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClinicAssistants|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClinicAssistants[]    findAll()
 * @method ClinicAssistants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClinicAssistantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClinicAssistants::class);
    }

    // /**
    //  * @return ClinicAssistants[] Returns an array of ClinicAssistants objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClinicAssistants
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
