<?php

namespace App\Repository;

use App\Entity\Reminders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reminders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reminders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reminders[]    findAll()
 * @method Reminders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemindersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reminders::class);
    }

    // /**
    //  * @return Reminders[] Returns an array of Reminders objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reminders
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
