<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Order;

use HarvestCloud\CoreBundle\Controller\Order\OrderController as Controller;
use HarvestCloud\CoreBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HarvestCloud\NotifierBundle\Events\OrderAcceptedBySellerEvent;
use HarvestCloud\NotifierBundle\Events\OrderDispatchedBySellerEvent;

/**
 * DefaultController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-09-30
 */
class DefaultController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-29
     */
    public function indexAction()
    {
        $q  = $this->get('doctrine')
            ->getManager()
            ->createQuery('
                SELECT    o
                FROM      HarvestCloudCoreBundle:Order o
                LEFT JOIN o.buyer b
                LEFT JOIN o.seller s
                LEFT JOIN o.hub h
                WHERE     :profile = o.buyer
                OR        :profile = o.seller
                OR        :profile = o.hub
                ORDER BY  o.id DESC
            ')
            ->setParameter('profile', $this->getCurrentProfile())
        ;

        $orders = $q->getResult();

        return $this->render('HarvestCloudCoreBundle:Order/Default:index.html.twig', array(
            'profile' => $this->getCurrentProfile(),
            'orders'  => $orders,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-29
     *
     * @param  string $id
     */
    public function showAction($id)
    {
        $q  = $this->get('doctrine')
            ->getManager()
            ->createQuery('
                SELECT    o
                FROM      HarvestCloudCoreBundle:Order o
                LEFT JOIN o.buyer b
                LEFT JOIN o.seller s
                LEFT JOIN o.hub h
                WHERE     o.id = :id
            ')
            ->setParameter('id', $id)
        ;

        $order = $q->getOneOrNullResult();

        return $this->render('HarvestCloudCoreBundle:Order/Default:show.html.twig', array(
            'profile' => $this->getCurrentProfile(),
            'order'   => $order,
        ));
    }

    /**
     * accept
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-13
     *
     * @Route("/{id}/accept")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function acceptAction(Order $order)
    {
        $order->acceptBySeller();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        $this->get('notifier')->notify(new OrderAcceptedBySellerEvent($order), $this->getUser());

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * reject
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/{id}/reject")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function rejectAction(Order $order)
    {
        $order->rejectBySeller();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * pick
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/{id}/pick")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function pickAction(Order $order)
    {
        $order->pickBySeller();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * mark ready for dispatch
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/{id}/mark-ready-for-dispatch")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function mark_ready_for_dispatchAction(Order $order)
    {
        $order->markReadyForDispatchBySeller();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * dispatch
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     * @todo   Use better method to getAmountForPaymentGateway()
     *
     * @Route("/{id}/dispatch")
     * @ParamConverter("order", class="HarvestCloudCoreBundle:Order")
     *
     * @param  Order  $order
     */
    public function dispatchAction(Order $order)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $order->dispatchBySeller($this->get('exchange_manager')->getExchange());

        $em->persist($order);
        $em->flush();

        $this->get('notifier')->notify(new OrderDispatchedBySellerEvent($order), $this->getUser());

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * cancel
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/{id}/cancel")
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

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * rate
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-28
     *
     * @Route("/{id}/rate/{rating}")
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

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * receive
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/{id}/receive")
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

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * mark as ready to pickup
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @Route("/{id}/mark-as-ready-to-pickup")
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

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }

    /**
     * release
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @Route("/{id}/release")
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

        return $this->redirect($this->generateUrl('Order_show', array(
            'id' => $order->getNumberForPath()
        )));
    }
}
