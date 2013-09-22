<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\MarketPlace\SellerBundle\Controller;

use HarvestCloud\MarketPlace\SellerBundle\Controller\SellerController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use HarvestCloud\CoreBundle\Entity\SellerWindow;

/**
 * SellerWindowController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-10
 */
class SellerWindowController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-10
     */
    public function indexAction()
    {
        $windows = $this->getRepo('SellerWindow')
            ->findUpcomingForSeller($this->getCurrentProfile())
        ;

        return $this->render('HarvestCloudMarketPlaceSellerBundle:SellerWindow:index.html.twig', array(
          'windows' => $windows,
        ));
    }
}
