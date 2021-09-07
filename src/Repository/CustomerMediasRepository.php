<?php

namespace App\Repository;

use App\Entity\CustomerMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerMedias|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerMedias|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerMedias[]    findAll()
 * @method CustomerMedias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerMedias::class);
    }

    // /**
    //  * @return CustomerMedias[] Returns an array of CustomerMedias objects
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
    public function findOneBySomeField($value): ?CustomerMedias
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
