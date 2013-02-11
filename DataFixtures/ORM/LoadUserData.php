<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HarvestCloud\UserBundle\Entity\User;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\Location;

/**
 * LoadUserData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-10
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-10
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $buyer = new User();
        $buyer->setUsername('buyer@example.com');
        $buyer->setFirstname('Test');
        $buyer->setLastname('Buyer');
        $buyer->setEmail('buyer@example.com');
        $buyer->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($buyer)
        ;

        $buyer->setPassword($encoder->encodePassword('buyer', $buyer->getSalt()));

        $buyerProfile = new Profile();
        $buyerProfile->setName($buyer->getFullname());

        $buyer->addProfile($buyerProfile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('250 Main Street');
        $location->setTown('Great Barrington');
        $location->setStateCode('MA');
        $location->setPostalCode('01230');

        $buyerProfile->addLocation($location);

        $manager->persist($buyer);
        $manager->flush();
    }
}
