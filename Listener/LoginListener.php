<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\HttpFoundation\Session\Session as Session;


/**
 * LoginListener
 *
 * Peform logic after login
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-17
 */
class LoginListener
{
    /**
     * doctrine
     *
     * @var Doctrine
     */
    protected $doctrine;

    /**
     * session
     *
     * @var Session
     */
    protected $session;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-17
     *
     * @param  Doctrine  $doctrine
     * @param  Session   $session
     */
    public function __construct(Doctrine $doctrine, Session $session)
    {
        $this->doctrine = $doctrine;
        $this->session  = $session;
    }

    /**
     * postLogin()
     *
     * Called after a successful login
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-17
     *
     * @param  InteractiveLoginEvent $event
     */
    public function postLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if($user) {
            $buyer = $user->getCurrentProfile();

            // check if we have a cart in the session
            if ($this->session->get('cart_id')) {
                $orderCollection = $this->doctrine
                    ->getRepository('HarvestCloudCoreBundle:OrderCollection')
                    ->find($this->session->get('cart_id'));

                // Check if a cart really exists
                if ($orderCollection) {
                    // Assign Buyer to OrderCollection
                    $orderCollection->setBuyer($buyer);

                    $em = $this->doctrine->getEntityManager();
                    $em->flush();
                }
            }
        }
    }
}
