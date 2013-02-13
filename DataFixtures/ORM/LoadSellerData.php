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
 * LoadSellerData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-11
 */
class LoadSellerData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
     * @since  2013-02-11
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('jon.seller@example.com');
        $user->setFirstname('Jon');
        $user->setLastname('Seller');
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('seller', $user->getSalt()));

        $profile = new Profile();
        $profile->setName($user->getFullname());

        $user->addProfile($profile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('100 Main Street');
        $location->setTown('Lee');
        $location->setStateCode('MA');
        $location->setPostalCode('01238');

        $profile->addLocation($location);

        $manager->persist($user);
        $manager->flush();


        $user = new User();
        $user->setEmail('tom.seller@example.com');
        $user->setFirstname('Tom');
        $user->setLastname('Seller');
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('seller', $user->getSalt()));

        $profile = new Profile();
        $profile->setName($user->getFullname());

        $user->addProfile($profile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('100 Main Street');
        $location->setTown('Great Barrington');
        $location->setStateCode('MA');
        $location->setPostalCode('01230');

        $profile->addLocation($location);

        $manager->persist($user);
        $manager->flush();


        $user = new User();
        $user->setEmail('stephen.seller@example.com');
        $user->setFirstname('Stephen');
        $user->setLastname('Seller');
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('seller', $user->getSalt()));

        $profile = new Profile();
        $profile->setName($user->getFullname());

        $user->addProfile($profile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('100 Main Street');
        $location->setTown('Lenox');
        $location->setStateCode('MA');
        $location->setPostalCode('01240');

        $profile->addLocation($location);

        $manager->persist($user);
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
        return 4;
    }
}
