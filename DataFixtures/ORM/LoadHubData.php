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
 * LoadHubData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-11
 */
class LoadHubData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
        $user->setEmail('sam.hub@example.com');
        $user->setFirstname('Sam');
        $user->setLastname('Hub');
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('hub', $user->getSalt()));

        $profile = new Profile();
        $profile->setName($user->getFullname());
        $profile->setSystemHubStatus(Profile::STATUS_ENABLED);
        $profile->setHubStatus(Profile::STATUS_ACTIVE);

        $user->addProfile($profile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('100 East Street');
        $location->setTown('Great Barrington');
        $location->setStateCode('MA');
        $location->setPostalCode('01230');

        $geocoder = new \HarvestCloud\GeoBundle\Util\GoogleGeocoder();
        $geocoder->geocode($location);

        $profile->addLocation($location);

        $manager->persist($user);
        $manager->flush();


        $user = new User();
        $user->setEmail('nick.hub@example.com');
        $user->setFirstname('Nick');
        $user->setLastname('Hub');
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('hub', $user->getSalt()));

        $profile = new Profile();
        $profile->setName($user->getFullname());
        $profile->setSystemHubStatus(Profile::STATUS_ENABLED);
        $profile->setHubStatus(Profile::STATUS_ACTIVE);

        $user->addProfile($profile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('100 Main Street');
        $location->setTown('Stockbridge');
        $location->setStateCode('MA');
        $location->setPostalCode('01262');

        $geocoder = new \HarvestCloud\GeoBundle\Util\GoogleGeocoder();
        $geocoder->geocode($location);

        $profile->addLocation($location);

        $manager->persist($user);
        $manager->flush();


        $user = new User();
        $user->setEmail('ramon.hub@example.com');
        $user->setFirstname('Ramon');
        $user->setLastname('Hub');
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('hub', $user->getSalt()));

        $profile = new Profile();
        $profile->setName($user->getFullname());
        $profile->setSystemHubStatus(Profile::STATUS_ENABLED);
        $profile->setHubStatus(Profile::STATUS_ACTIVE);

        $user->addProfile($profile);

        $location = new Location();
        $location->setName('Default');
        $location->setAddressLine1('10 East Street');
        $location->setTown('Lenox');
        $location->setStateCode('MA');
        $location->setPostalCode('01240');

        $geocoder = new \HarvestCloud\GeoBundle\Util\GoogleGeocoder();
        $geocoder->geocode($location);

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
        return 3;
    }
}
