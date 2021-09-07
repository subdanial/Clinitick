<?php

namespace App\Repository;

use App\Entity\TransactionDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionDetails[]    findAll()
 * @method TransactionDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionDetails::class);
    }

    // /**
    //  * @return TransactionDetails[] Returns an array of TransactionDetails objects
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
    public function findOneBySomeField($value): ?TransactionDetails
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
