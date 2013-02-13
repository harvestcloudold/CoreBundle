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
use HarvestCloud\CoreBundle\Entity\HubWindow;
use HarvestCloud\CoreBundle\Entity\HubWindowMaker;

/**
 * LoadHubWindowMakerData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-12
 */
class LoadHubWindowMakerData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
     * @since  2013-02-12
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $windowMaker = new HubWindowMaker();
        $windowMaker->setHub($this->getReference('sam-hub'));
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $manager->persist($windowMaker);
        $manager->flush();


        $windowMaker = new HubWindowMaker();
        $windowMaker->setHub($this->getReference('nick-hub'));
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $manager->persist($windowMaker);
        $manager->flush();


        $windowMaker = new HubWindowMaker();
        $windowMaker->setHub($this->getReference('ramon-hub'));
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $manager->persist($windowMaker);
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
        return 6;
    }
}
