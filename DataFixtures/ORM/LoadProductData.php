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
use HarvestCloud\CoreBundle\Entity\Product;

/**
 * LoadProductData
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-12
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
        $product = new Product();
        $product->setName('Lovely Eggs');
        $product->setCategory($this->getReference('eggs-category'));
        $product->setSeller($this->getReference('jon-seller'));
        $product->setInitialQuantity(10);
        $product->setPrice('5.00');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Delicious Eggs');
        $product->setCategory($this->getReference('eggs-category'));
        $product->setSeller($this->getReference('tom-seller'));
        $product->setInitialQuantity(8);
        $product->setPrice('5.25');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Yummy Eggs');
        $product->setCategory($this->getReference('eggs-category'));
        $product->setSeller($this->getReference('stephen-seller'));
        $product->setInitialQuantity(11);
        $product->setPrice('4.95');

        $manager->persist($product);
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
        return 5;
    }
}
