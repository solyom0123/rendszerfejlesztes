<?php

namespace App\Repository;

use App\Entity\FoodAllergens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FoodAllergens|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodAllergens|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodAllergens[]    findAll()
 * @method FoodAllergens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodAllergensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodAllergens::class);
    }

    // /**
    //  * @return FoodAllergens[] Returns an array of FoodAllergens objects
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
    public function findOneBySomeField($value): ?FoodAllergens
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
