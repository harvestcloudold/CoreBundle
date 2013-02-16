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
use HarvestCloud\CoreBundle\Entity\SellerHubRef;
use HarvestCloud\CoreBundle\Entity\SellerWindowMaker;

/**
 * LoadSellerWindowMakerData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-13
 */
class LoadSellerWindowMakerData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
     * @since  2013-02-13
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('jon-seller'));
        $sellerHubRef->setHub($this->getReference('sam-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.5);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('jon-seller'));
        $sellerHubRef->setHub($this->getReference('nick-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.6);
        $sellerHubRef->setVariableFee(0.2);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('jon-seller'));
        $sellerHubRef->setHub($this->getReference('ramon-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.55);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();




        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('stephen-seller'));
        $sellerHubRef->setHub($this->getReference('sam-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.5);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('stephen-seller'));
        $sellerHubRef->setHub($this->getReference('nick-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.6);
        $sellerHubRef->setVariableFee(0.2);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('stephen-seller'));
        $sellerHubRef->setHub($this->getReference('ramon-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.55);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();




        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('jo-seller'));
        $sellerHubRef->setHub($this->getReference('sam-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.5);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('jo-seller'));
        $sellerHubRef->setHub($this->getReference('nick-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.6);
        $sellerHubRef->setVariableFee(0.2);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('jo-seller'));
        $sellerHubRef->setHub($this->getReference('ramon-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.55);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();




        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('sterling-seller'));
        $sellerHubRef->setHub($this->getReference('sam-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.5);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('sterling-seller'));
        $sellerHubRef->setHub($this->getReference('nick-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.6);
        $sellerHubRef->setVariableFee(0.2);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('sterling-seller'));
        $sellerHubRef->setHub($this->getReference('ramon-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.55);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();




        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('tom-seller'));
        $sellerHubRef->setHub($this->getReference('sam-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.5);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('tom-seller'));
        $sellerHubRef->setHub($this->getReference('nick-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.6);
        $sellerHubRef->setVariableFee(0.2);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
        $manager->flush();


        $sellerHubRef = new SellerHubRef();
        $sellerHubRef->setSeller($this->getReference('tom-seller'));
        $sellerHubRef->setHub($this->getReference('ramon-hub'));
        $sellerHubRef->setIsDefault(true);
        $sellerHubRef->setFixedFee(0.55);
        $sellerHubRef->setVariableFee(0.25);

        $windowMaker = new SellerWindowMaker();
        $windowMaker->setDeliveryType(HubWindow::DELIVERY_TYPE_PICKUP);
        $windowMaker->setDayOfWeekNumbers(array(6));
        $windowMaker->setStartTime('09:00');
        $windowMaker->setEndTime('13:00');

        $sellerHubRef->addWindowMaker($windowMaker);

        $manager->persist($sellerHubRef);
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
        return 7;
    }
}
