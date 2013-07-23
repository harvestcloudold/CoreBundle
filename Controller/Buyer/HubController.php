<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Buyer\BuyerController as Controller;
use HarvestCloud\CoreBundle\Entity\BuyerHubRef;

/**
 * HubController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-25
 */
class HubController extends Controller
{
    /**
     * nearby
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     */
    public function nearbyAction()
    {
        $origin = $this->getUser()->getCurrentProfile()->getDefaultLocation();
        $hubs   = $this->getRepo('Profile')->findNearbyHubs($origin);

        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Hub:nearby.html.twig', array(
          'hubs'   => $hubs,
          'origin' => $origin,
        ));
    }

    /**
     * show
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     * @todo   Reduce number of queries on this page
     *
     * @param  int  $id
     */
    public function showAction($id)
    {
        $hub     = $this->getRepo('Profile')->find($id);
        $origin  = $hub->getDefaultLocation();
        $buyer   = $this->getUser()->getCurrentProfile();

        if (!$hub)
        {
            throw $this->createNotFoundException('No hub found for id '.$id);
        }

        $buyerHubRef = $this->getRepo('BuyerHubRef')->findOneByBuyerAndHub($buyer, $hub);

        return $this->render('HarvestCloudMarketPlaceBuyerBundle:Hub:show.html.twig', array(
          'hub'         => $hub,
          'buyerHubRef' => $buyerHubRef,
          'origin'      => $origin,
        ));
    }

    /**
     * add (as BuyerHubRef)
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-27
     *
     * @param  int  $id
     */
    public function addAction($id)
    {
        $hub   = $this->getRepo('Profile')->find($id);
        $buyer = $this->getUser()->getCurrentProfile();

        if (!$hub)
        {
            throw $this->createNotFoundException('No hub found for id '.$id);
        }

        $buyerHubRef = $this->getRepo('BuyerHubRef')
            ->findOneByBuyerAndHub($buyer, $hub);

        if (!$buyerHubRef)
        {
            $buyerHubRef = new BuyerHubRef();
            $buyerHubRef->setHub($hub);

            $buyer->addBuyerHubRefAsBuyer($buyerHubRef);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($buyerHubRef);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Buyer_hub_show', array(
            'id' => $hub->getId(),
        )));
    }
}
