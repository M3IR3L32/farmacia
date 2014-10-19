<?php


namespace Acme\DemoBundle\Service\Manager;


use Acme\DemoBundle\Entity\AcmeOrder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class OrderManager 
{
    /** @var  EntityManager */
    public $om;
    /** @var EntityRepository */
    private $repository;

    public function __construct(EntityManager $om)
    {
        $this->om = $om;
        $this->repository = $om->getRepository('AcmeDemoBundle:AcmeOrder');
    }

    /**
     * @return array
     */
    public function findAllOrders()
    {
        return $this->repository->findAllWithAdminData();
    }

    /**
     * @return array
     */
    /**
     * @param $id
     * @return mixed
     */
    public function findOrderById($id)
    {
        $orders = $this->repository->findBy(
            array("id" => $id),
            array('id' => 'ASC')
        );
        if (count($orders) > 0) {
            return $orders[0];
        }
    }

    /**
     * @param $transactionId
     * @return AcmeOrder
     */
    public function findByTransactionId($transactionId)
    {
        return $this->repository->findOneBy(array(
            'transactionToken' => $transactionId
        ));
    }

    /**
     * @param $subscriptionId
     * @return AcmeOrder
     */
    public function findBySubscriptionId($subscriptionId)
    {
        return $this->repository->findOneBy(array(
            'subscriptionToken' => $subscriptionId
        ));
    }

    /**
     * @param  AcmeOrder $order
     * @return int
     */
    public function validateOrder(AcmeOrder $order)
    {
        $value = -1;
        $status = $order->getAdminValidation();

        if ($status === false) {
            $order->setAdminValidation(true);
            $value = 1;
        }
        if ($status === true) {
            $order->setAdminValidation(false);
            $value = 0;
        }

        return $value;
    }

    /**
     * @param $transactionToken
     */
    public function setOrderAsPayedByTransactionToken($transactionToken)
    {
        /** @var AcmeOrder $order */
        $order = $this->repository->findOneBy(array(
            'transactionToken' => $transactionToken
        ));

        if ($order != null) {
            $order->setAsPayed();
            $this->persistAndFlush($order);
        }
    }

    /**
     * @param AcmeOrder $order
     */
    public function persistAndFlush(AcmeOrder $order)
    {
        $this->om->persist($order);
        $this->om->flush();
    }
} 