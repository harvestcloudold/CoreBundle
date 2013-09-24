<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Controller\Profile;

use HarvestCloud\CoreBundle\Controller\Profile\ProfileController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * StripeController
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-31
 */
class StripeController extends Controller
{
    /**
     * connect
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-31
     *
     * @param  Request $request
     */
    public function connectAction(Request $request)
    {
        if ($code = $request->query->get('code')) {
            // Make request to Stripe to complete connection
            $requestor = new \Stripe_ApiRequestor($this->container->getParameter('payment.stripe.secret_key'));
            $response  = $requestor->request(
                'post',
                'https://connect.stripe.com/oauth/token',
                array(
                    'client_secret' => $this->container->getParameter('payment.stripe.secret_key'),
                    'grant_type'    => 'authorization_code',
                    'client_id'     => $this->container->getParameter('payment.stripe.client_id'),
                    'code'          => $code,
                )
            );

            $this->getCurrentProfile()->setStripeUserId($response[0]['stripe_user_id']);
            $this->getCurrentProfile()->setStripePublishableKey($response[0]['stripe_publishable_key']);
            $this->getCurrentProfile()->setStripeAccessToken($response[0]['access_token']);

            $em = $this->get('doctrine')->getEntityManager();
            $em->persist($this->getCurrentProfile());
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Awesome! You have successfully connected your Stripe account');
        }

        return $this->redirect($this->generateUrl('Profile_homepage'));
    }
}
