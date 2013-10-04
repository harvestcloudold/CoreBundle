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

/**
 * DashboardController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-10-03
 */
class DashboardController extends Controller
{
    /**
     * open_as_buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-03
     */
    public function open_as_buyerAction()
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
                ORDER BY  o.id DESC
            ')
            ->setParameter('profile', $this->getCurrentProfile())
        ;

        $orders = $q->getResult();

        return $this->render('HarvestCloudCoreBundle:Order/Dashboard:open_as_buyer.html.twig', array(
            'orders'  => $orders,
        ));
    }
}
