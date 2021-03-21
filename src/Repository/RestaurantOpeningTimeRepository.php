<?php

namespace App\Repository;

use App\Entity\RestaurantOpeningTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantOpeningTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantOpeningTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantOpeningTime[]    findAll()
 * @method RestaurantOpeningTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantOpeningTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantOpeningTime::class);
    }

    // /**
    //  * @return RestaurantOpeningTime[] Returns an array of RestaurantOpeningTime objects
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
    public function findOneBySomeField($value): ?RestaurantOpeningTime
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
