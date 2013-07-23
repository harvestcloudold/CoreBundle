<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;
use HarvestCloud\CoreBundle\Entity\Profile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * SellerController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-02
 */
class SellerController extends Controller
{
    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     * @todo   Find a way to reduce queries, maybe create a new ParamConverter
     *
     * @Route("/seller/{id}")
     * @ParamConverter("seller", class="HarvestCloudCoreBundle:Profile")
     *
     * @param  Profile $seller
     */
    public function showAction(Profile $seller)
    {
        $origin  = $seller->getDefaultLocation();

        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Seller:show.html.twig', array(
          'seller' => $seller,
          'origin' => $origin,
        ));
    }
}
