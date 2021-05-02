<?php

namespace App\Repository;

use App\Entity\CourierData;
use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method CourierData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourierData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourierData[]    findAll()
 * @method CourierData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourierDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourierData::class);
    }
    public function test(SessionInterface $session,RestaurantRepository $rrepository){
        }
    /**
     * @return CourierData[] Returns an array of CompanyData objects
     */
    public function findByUser($value)
    {
        $builder= $this->createQueryBuilder('c')
            ->where('c.user = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()

            ;

            return $builder->getResult();
    }
    // /**
    //  * @return CourierData[] Returns an array of CourierData objects
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
    public function findOneBySomeField($value): ?CourierData
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
