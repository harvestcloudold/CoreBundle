<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Buyer;

use HarvestCloud\CoreBundle\Controller\Core\CoreController as Controller;
use HarvestCloud\CoreBundle\Entity\OrderCollection;

/**
 * BuyerController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-21
 */
class BuyerController extends Controller
{
    /**
     * getCurrentCart()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-20
     *
     * @return OrderCollection
     */
    public function getCurrentCart()
    {
        // Grab the session
        $session = $this->getRequest()->getSession();

        // Start with an null OrderCollection
        $orderCollection = null;

        // Check if we have a reference to the cart in the session
        if ($session->get('cart_id')) {
            $orderCollection = $this->getRepo('OrderCollection')
                ->find($session->get('cart_id'));
        }

        // If we still don't have an OrderCollection, create one and persist it
        if (!$orderCollection) {
            $orderCollection = new OrderCollection();

            // Persist new so that we get a 'cart_id'
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($orderCollection);
            $em->flush();

            // Save new cart to session
            $session->set('cart_id', $orderCollection->getId());
        }

        // If the current User is fully authenticated, set the current Profile
        // as the Buyer. If the user is not yet fully authenticated, then the
        // Buyer will be assigned at login
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $orderCollection->setBuyer($this->get('profile_handler')->getCurrent());
        }

        return $orderCollection;
    }
}
