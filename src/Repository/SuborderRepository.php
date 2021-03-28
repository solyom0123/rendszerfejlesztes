<?php

namespace App\Repository;

use App\Entity\Suborder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suborder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suborder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suborder[]    findAll()
 * @method Suborder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuborderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suborder::class);
    }

    // /**
    //  * @return Suborder[] Returns an array of Suborder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Suborder
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
