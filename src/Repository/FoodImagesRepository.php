<?php

namespace App\Repository;

use App\Entity\FoodImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FoodImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodImages[]    findAll()
 * @method FoodImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodImages::class);
    }

    public function findByRestaurant($value)
    {
        return $this->createQueryBuilder('c')
            ->join('c.restaurant','e')
            ->andWhere('e = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return FoodImages[] Returns an array of FoodImages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FoodImages
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
