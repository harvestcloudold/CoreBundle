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
use HarvestCloud\CoreBundle\Entity\Category;

/**
 * LoadCategoryData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-12
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
        $food       = new Category('Food');
        $fruit      = new Category('Fruit');
        $apples     = new Category('Apples');
        $vegetables = new Category('Vegetables');
        $tomatoes   = new Category('Tomatoes');
        $carrots    = new Category('Carrots');
        $meat       = new Category('Meat');
        $dairy      = new Category('Dairy');
        $milk       = new Category('Milk');
        $eggs       = new Category('Eggs');

        $food->addCategory($fruit);
          $fruit->addCategory($apples);
        $food->addCategory($vegetables);
          $vegetables->addCategory($tomatoes);
          $vegetables->addCategory($carrots);
        $food->addCategory($meat);
        $food->addCategory($dairy);
          $dairy->addCategory($milk);
          $dairy->addCategory($eggs);

        $manager->persist($food);
        $manager->flush();

        $this->addReference('eggs-category', $eggs);
        $this->addReference('tomatoes-category', $tomatoes);
        $this->addReference('milk-category', $milk);
        $this->addReference('apples-category', $apples);
        $this->addReference('carrots-category', $carrots);
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
        return 1;
    }
}
