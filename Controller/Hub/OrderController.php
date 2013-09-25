<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Hub;

use HarvestCloud\CoreBundle\Controller\Buyer\HubController as Controller;
use HarvestCloud\CoreBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * OrderController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-11
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
        $hub = $this->getCurrentProfile();

        $orders = $this->getRepo('Order')
            ->findOpenForHub($hub)
        ;

        return $this->render('HarvestCloudCoreBundle:Hub/Order:index.html.twig', array(
          'orders' => $orders,
        ));
    }

    /**
     * receive
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/order/{id}/receive")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function receiveAction(Order $order)
    {
        $order->receiveByHub();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Hub_order'));
    }

    /**
     * mark as ready to pickup
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @Route("/order/{id}/mark-as-ready-to-pickup")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function mark_as_ready_to_pickupAction(Order $order)
    {
        $order->markReadyForPickupFromHub();

        $message = \Swift_Message::newInstance()
            ->setSubject('Your order is ready to be picked up')
            ->setFrom(array('no-reply@harvestcloud.com' => 'Harvest Cloud'))
            ->setTo(array('tom@templestreetmedia.com' => 'Tom Haskins-Vaughan'))
            ->setBody($this->renderView(
                'HarvestCloudEmailBundle:Buyer:order_ready_to_pickup.txt.twig', array(
                    'order' => $order
            )))
        ;

        $this->get('mailer')->send($message);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Hub_order'));
    }

    /**
     * release
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/order/{id}/release")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function releaseAction(Order $order)
    {
        $order->releaseToBuyer();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Hub_order'));
    }
}
