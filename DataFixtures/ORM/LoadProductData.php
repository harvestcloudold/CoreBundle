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
        $product->setName('Tomatoes');
        $product->setCategory($this->getReference('tomatoes-category'));
        $product->setSeller($this->getReference('jon-seller'));
        $product->setInitialQuantity(7);
        $product->setPrice('3.85');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Suh-weet Potatoes');
        $product->setCategory($this->getReference('sweet-potatoes-category'));
        $product->setSeller($this->getReference('tom-seller'));
        $product->setInitialQuantity(12);
        $product->setPrice('2.75');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Tom\'s Toms');
        $product->setCategory($this->getReference('tomatoes-category'));
        $product->setSeller($this->getReference('tom-seller'));
        $product->setInitialQuantity(8);
        $product->setPrice('4.75');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Apples');
        $product->setCategory($this->getReference('apples-category'));
        $product->setSeller($this->getReference('jo-seller'));
        $product->setInitialQuantity(13);
        $product->setPrice('5.05');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Sweet Potatoes');
        $product->setCategory($this->getReference('sweet-potatoes-category'));
        $product->setSeller($this->getReference('jo-seller'));
        $product->setInitialQuantity(13);
        $product->setPrice('2.65');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Carrots');
        $product->setCategory($this->getReference('carrots-category'));
        $product->setSeller($this->getReference('jo-seller'));
        $product->setInitialQuantity(15);
        $product->setPrice('2.95');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Onions');
        $product->setCategory($this->getReference('onions-category'));
        $product->setSeller($this->getReference('sterling-seller'));
        $product->setInitialQuantity(11);
        $product->setPrice('2.95');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Ca-ca-carrots');
        $product->setCategory($this->getReference('carrots-category'));
        $product->setSeller($this->getReference('sterling-seller'));
        $product->setInitialQuantity(11);
        $product->setPrice('2.90');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Apples');
        $product->setCategory($this->getReference('apples-category'));
        $product->setSeller($this->getReference('sterling-seller'));
        $product->setInitialQuantity(11);
        $product->setPrice('4.15');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Them Apples');
        $product->setCategory($this->getReference('apples-category'));
        $product->setSeller($this->getReference('tom-seller'));
        $product->setInitialQuantity(11);
        $product->setPrice('4.25');

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


        $product = new Product();
        $product->setName('Milk');
        $product->setCategory($this->getReference('milk-category'));
        $product->setSeller($this->getReference('jon-seller'));
        $product->setInitialQuantity(8);
        $product->setPrice('3.55');

        $manager->persist($product);
        $manager->flush();


        $product = new Product();
        $product->setName('Milk');
        $product->setCategory($this->getReference('milk-category'));
        $product->setSeller($this->getReference('stephen-seller'));
        $product->setInitialQuantity(10);
        $product->setPrice('3.45');

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
