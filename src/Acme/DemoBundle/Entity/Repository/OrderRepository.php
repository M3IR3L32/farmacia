<?php


namespace Acme\DemoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends EntityRepository
{

    /**
     * @return \Doctrine\ORM\Query
     */
    public function findAllOrdersByMonthAndYear()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
            ->select('o AS mOrder,SUBSTRING(o.createdAt, 6, 2) AS month,SUBSTRING(o.createdAt, 1, 4) AS year')
            ->from('OrderBundle:Order', 'o')
            ->andWhere("o.status='payed' AND o.paymentStatus='payed' ")
            ->orderBy('year,month');
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findAllPlanOrdersByMonthAndYear()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
            ->select('o AS mOrder, SUBSTRING(o.createdAt, 6, 2) AS month,SUBSTRING(o.createdAt, 1, 4) AS year')
            ->from('OrderBundle:Order', 'o')
            ->where("o.type='order_plan' ")
            ->andWhere("o.status='payed' AND o.paymentStatus='payed' ")
            ->orderBy('year,month');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findAllFeaturesOrdersByMonthAndYear()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
            ->select('o AS mOrder, SUBSTRING(o.createdAt, 6, 2) AS month,SUBSTRING(o.createdAt, 1, 4) AS year')
            ->from('OrderBundle:Order', 'o')
            ->where("o.type='order_guest_feature'  OR o.type='order_feature' ")
            ->andWhere("o.status='payed' AND o.paymentStatus='payed' ")
            ->orderBy('year,month');


        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findAllComissionOrdersByMonthAndYear()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
            ->select('o AS mOrder, SUBSTRING(o.createdAt, 6, 2) AS month,SUBSTRING(o.createdAt, 1, 4) AS year')
            ->from('OrderBundle:Order', 'o')
            ->where("o.type='order_commission' ")
            ->andWhere("o.status='payed' AND o.paymentStatus='payed' ")
            ->orderBy('year,month');


        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findAllWithAdminData()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder
            ->select('o')
            ->addSelect('a')
            ->addSelect('i')
            ->addSelect('p')
            ->addSelect('u')
            ->addSelect('dp')
            ->from('OrderBundle:Order', 'o')
            ->leftJoin('o.items','i')
            ->leftJoin('o.adjustments','a')
            ->leftJoin('o.user','u')
            ->leftJoin('i.product','p')
            ->leftJoin('u.developerPlan','dp');


        return $queryBuilder->getQuery()->getResult();

    }

}
