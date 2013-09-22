<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\MarketPlace\SellerBundle\Controller;

use HarvestCloud\MarketPlace\SellerBundle\Controller\SellerController as Controller;
use HarvestCloud\CoreBundle\Entity\SellerHubRef;

/**
 * (Seller)HubController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-28
 */
class HubController extends Controller
{
    /**
     * nearby
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     */
    public function nearbyAction()
    {
        $origin = $this->getUser()->getCurrentProfile()->getDefaultLocation();
        $hubs   = $this->getRepo('Profile')->findNearbyHubs($origin);

        return $this->render('HarvestCloudMarketPlaceSellerBundle:Hub:nearby.html.twig', array(
          'hubs'   => $hubs,
          'origin' => $origin,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     * @todo   Reduce number of queries on this page
     *
     * @param  int  $id
     */
    public function showAction($id)
    {
        $hub   = $this->getRepo('Profile')->find($id);
        $seller = $this->getUser()->getCurrentProfile();

        if (!$hub)
        {
            throw $this->createNotFoundException('No hub found for id '.$id);
        }

        $sellerHubRef = $this->getRepo('SellerHubRef')->findOneBySellerAndHub($seller, $hub);

        return $this->render('HarvestCloudMarketPlaceSellerBundle:Hub:show.html.twig', array(
          'hub'          => $hub,
          'sellerHubRef' => $sellerHubRef,
        ));
    }

    /**
     * add (as SellerHubRef)
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @param  int  $id
     */
    public function addAction($id)
    {
        $hub    = $this->getRepo('Profile')->find($id);
        $seller = $this->getUser()->getCurrentProfile();

        if (!$hub)
        {
            throw $this->createNotFoundException('No hub found for id '.$id);
        }

        $sellerHubRef = $this->getRepo('SellerHubRef')
            ->findOneBySellerAndHub($seller, $hub);

        if (!$sellerHubRef)
        {
            $sellerHubRef = new SellerHubRef();
            $sellerHubRef->setHub($hub);

            $seller->addSellerHubRefAsSeller($sellerHubRef);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($sellerHubRef);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Seller_hub_show', array(
            'id' => $hub->getId(),
        )));
    }
}
