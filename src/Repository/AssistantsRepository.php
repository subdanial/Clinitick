<?php

namespace App\Repository;

use App\Entity\Assistants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Assistants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assistants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assistants[]    findAll()
 * @method Assistants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssistantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assistants::class);
    }

    // /**
    //  * @return Assistants[] Returns an array of Assistants objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Assistants
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
