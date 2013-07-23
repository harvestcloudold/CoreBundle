<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;
use HarvestCloud\CoreBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * OrderController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-14
 */
class OrderController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-11
     */
    public function indexAction()
    {
        $buyer = $this->getCurrentProfile();

        $orders = $this->getRepo('Order')
            ->findForBuyer($buyer)
        ;

        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Order:index.html.twig', array(
          'orders' => $orders,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @Route("/order/{id}")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function showAction(Order $order)
    {
        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Order:show.html.twig', array(
          'order' => $order,
        ));
    }

    /**
     * cancel
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/order/{id}/cancel")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function cancelAction(Order $order)
    {
        $order->cancelByBuyer();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Buyer_order'));
    }

    /**
     * rate
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-28
     *
     * @Route("/order/{id}/rate/{rating}")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function rateAction(Order $order, $rating)
    {
        $order->rate($rating);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Buyer_order'));
    }
}
