<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcomme_demo")
     */
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */

        $acmeOrderManager = $this->get('acme.order_manager');
//        $orders = $acmeOrderManager->findAllOrders();

        return $this->render('AcmeDemoBundle:Welcome:index.html.twig');
    }
}
