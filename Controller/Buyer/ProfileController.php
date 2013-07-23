<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;

/**
 * ProfileController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-21
 */
class ProfileController extends Controller
{
    /**
     * index
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-21
     */
    public function indexAction()
    {
        $profiles = $this->getDoctrine()
            ->getRepository('HarvestCloudCoreBundle:Profile')
            ->findAll();

        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Profile:index.html.twig', array(
          'profiles' => $profiles,
        ));
    }

    /**
     * current
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     */
    public function currentAction()
    {
        $user    = $this->getUser();
        $profile = $this->getRepo('Profile')->findCurrentWithLocation($user->getId());

        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Profile:current.html.twig', array(
          'profile' => $profile,
        ));
    }
}
