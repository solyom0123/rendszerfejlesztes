<?php

namespace App\Repository;

use App\Entity\MenuCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuCategory[]    findAll()
 * @method MenuCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuCategory::class);
    }
    public function findByRestaurant($value)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.menus','m')
            ->andWhere('m.restaurant = :val')
            ->orWhere('m is null')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return MenuCategory[] Returns an array of MenuCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
