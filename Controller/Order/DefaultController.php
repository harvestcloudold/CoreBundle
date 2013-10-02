<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Order;

use HarvestCloud\CoreBundle\Controller\Order\OrderController as Controller;

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
}
