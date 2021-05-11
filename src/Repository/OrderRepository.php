<?php

namespace App\Repository;

use App\Entity\CourierData;
use App\Entity\Order;
use App\Entity\Suborder;
use App\Entity\User;
use App\Enums\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @method Suborder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suborder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suborder[]    findAll()
 * @method Suborder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suborder::class);
    }

    /** @return Collection|Suborder[] */
    public function findByRestaurant($value)
    {
        $alma = $this->createQueryBuilder('c')
            ->andWhere('c.restaurant = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery();
        return $alma->getResult();
    }

    public function countByFoodAndSuborder($value, $value2)
    {
        $sql = 'select count(*) from suborder_food where food_id = ' . $value . ' and suborder_id = ' . $value2;
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);

        $statement->execute();
        $results = $statement->fetchColumn();
        return $results;
    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.parentOrder', 'o')
            ->where('o.customer = :customer')
            ->orderBy('c.id')
            ->setParameter('customer', $user)
            ->getQuery()
            ->getResult();
    }

    public function findAvailableOrders(CourierData $cd)
    {
        $q = $this->createQueryBuilder('q')
            ->where('q.status = :status')
            ->andWhere('q.courier IS NULL')
            ->setParameter('status', OrderStatus::$DONE);


        $splitAddress = explode(' ', $cd->getLocation());

        $filter = '';

        foreach ($splitAddress as $sa) {
            $filter .= '%' . $sa . '%' . ' ';
        }

        $filter = trim($filter);

        $filterSplit = explode(' ',$filter);

        $ors = '';

        foreach($filterSplit as $k=>$f){
            $ors.='LOWER(q.address) LIKE LOWER(?'.($k+1).') OR ';
        }

        $ors = substr($ors,0,strlen($ors)-4);

        $q->andWhere($ors);

        foreach($filterSplit as $k=>$f){
            $q->setParameter(($k+1),$f);
        }

        return $q
            ->getQuery()
            ->getResult();
    }

    public function findAssignedOrders(User $courier)
    {
        return $this->createQueryBuilder('q')
            ->where('q.courier = :courier')
            ->setParameter('courier', $courier)
            ->orderBy('q.displayorder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
