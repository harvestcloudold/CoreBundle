<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Seller;

use HarvestCloud\CoreBundle\Controller\Seller\SellerController as Controller;

/**
 * RegisterController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-17
 */
class RegisterController extends Controller
{
    /**
     * landing
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-17
     */
    public function landingAction()
    {
        if ($this->getCurrentProfile()->hasActiveSellerStatus())
        {
            return $this->redirect($this->generateUrl('Seller_homepage'));
        }

        return $this->render('HarvestCloudCoreBundle:Seller/Register:landing.html.twig', array(
          'seller' => $this->getCurrentProfile(),
        ));
    }
}
