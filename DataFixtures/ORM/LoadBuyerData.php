<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HarvestCloud\UserBundle\Entity\User;
use HarvestCloud\CoreBundle\Entity\Profile;
use HarvestCloud\CoreBundle\Entity\Location;

/**
 * LoadBuyerData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-10
 */
class LoadBuyerData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
        $geocoder = new \HarvestCloud\GeoBundle\Util\GoogleGeocoder();

        $buyer = new User();
        $buyer->setEmail('harvest.cloud@example.com');
        $buyer->setFirstname('Harvest');
        $buyer->setLastname('Cloud');
        $buyer->setEnabled(false);
        $buyer->setPassword('');

        $buyerProfile = new Profile();
        $buyerProfile->setName($buyer->getFullname());

        $buyer->addProfile($buyerProfile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('150 Main Street');
        $location->setTown('Great Barrington');
        $location->setStateCode('MA');
        $location->setPostalCode('01230');

        $geocoder->geocode($location);

        $buyerProfile->addLocation($location);

        $exchange = $this->container->get('exchange_manager')->getExchange();

        $exchange->setProfile($buyerProfile);

        $manager->persist($exchange);
        $manager->persist($buyer);
        $manager->flush();


        $buyer = new User();
        $buyer->setEmail('craig.buyer@example.com');
        $buyer->setFirstname('Craig');
        $buyer->setLastname('Buyer');
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

        $geocoder->geocode($location);

        $buyerProfile->addLocation($location);

        $manager->persist($buyer);
        $manager->flush();


        $buyer = new User();
        $buyer->setEmail('peter.buyer@example.com');
        $buyer->setFirstname('Peter');
        $buyer->setLastname('Buyer');
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
        $location->setAddressLine1('1 Main Street');
        $location->setTown('Sheffield');
        $location->setStateCode('MA');
        $location->setPostalCode('01257');

        $geocoder->geocode($location);

        $buyerProfile->addLocation($location);

        $manager->persist($buyer);
        $manager->flush();


        $buyer = new User();
        $buyer->setEmail('michael.buyer@example.com');
        $buyer->setFirstname('Michael');
        $buyer->setLastname('Buyer');
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
        $location->setAddressLine1('1 Main Street');
        $location->setTown('Lee');
        $location->setStateCode('MA');
        $location->setPostalCode('01238');

        $geocoder->geocode($location);

        $buyerProfile->addLocation($location);

        $manager->persist($buyer);
        $manager->flush();
    }

    /**
     * getOrder()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-12
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
