<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\MarketPlace\SellerBundle\Controller;

use HarvestCloud\MarketPlace\SellerBundle\Controller\SellerController as Controller;
use Symfony\Component\HttpFoundation\Request;
use HarvestCloud\CoreBundle\Entity\SellerHubPickupWindow;
use HarvestCloud\CoreBundle\Form\SellerHubPickupWindowType;

/**
 * SellerHubRefController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-29
 */
class SellerHubRefController extends Controller
{
    /**
     * add pickup window
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     * @todo   Reduce number of queries on this page
     * @todo   Make sure we don't create a PickupWindow when the Hub is closed
     * @todo   Refcator to model
     *
     * @param  Request $request
     */
    public function add_pickup_windowAction(Request $request)
    {
        $id = $request->get('id');

        $hub    = $this->getRepo('Profile')->find($id);
        $seller = $this->getUser()->getCurrentProfile();

        if (!$hub)
        {
            throw $this->createNotFoundException('No hub found for id '.$id);
        }

        $sellerHubRef = $this->getRepo('SellerHubRef')->findOneBySellerAndHub($seller, $hub);

        if (!$sellerHubRef)
        {
            throw $this->createNotFoundException('No SellerHubRef found for Seller/Hub combination');
        }

        $pickupWindow = new SellerHubPickupWindow();
        $pickupWindow->setSellerHubRef($sellerHubRef);

        $form = $this->createForm(new SellerHubPickupWindowType(), $pickupWindow);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($pickupWindow);
                $em->flush();

                return $this->redirect($this->generateUrl('Seller_hub_show', array(
                    'id' => $hub->getId(),
                )));
            }
        }

        return $this->render('HarvestCloudMarketPlaceSellerBundle:SellerHubRef:add_pickup_window.html.twig', array(
            'hub'  => $hub,
            'form' => $form->createView(),
        ));
    }
}
