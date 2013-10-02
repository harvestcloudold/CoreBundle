<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Seller;

use HarvestCloud\CoreBundle\Controller\Seller\SellerController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HarvestCloud\NotifierBundle\Events\OrderAcceptedBySellerEvent;
use HarvestCloud\NotifierBundle\Events\OrderDispatchedBySellerEvent;
use HarvestCloud\CoreBundle\Entity\Order;
use HarvestCloud\CoreBundle\Entity\Profile;

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
        $seller = $this->getCurrentProfile();

        $orders = $this->getRepo('Order')
            ->findOpenForSeller($seller)
        ;

        if (count($orders)) {
            $order = $orders[0];
        } else {
            $order = null;
        }

        return $this->render('HarvestCloudCoreBundle:Seller/Order:index.html.twig', array(
          'orders' => $orders,
          'order'  => $order,
        ));
    }
}
