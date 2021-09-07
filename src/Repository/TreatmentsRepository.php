<?php

namespace App\Repository;

use App\Entity\Treatments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Treatments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Treatments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Treatments[]    findAll()
 * @method Treatments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreatmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Treatments::class);
    }

    // /**
    //  * @return Treatments[] Returns an array of Treatments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Treatments
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
