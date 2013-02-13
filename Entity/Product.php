<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HarvestCloud\GeoBundle\Util\Geolocatable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product Entity
 *
 * The Product should be Geolocatable so that we can place it on a map, but it
 * should NOT be Geocodable since any location info should flow from the
 * Location and "cached" on the Product
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-07
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\ProductRepository")
 */
class Product implements Geolocatable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $short_description;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    protected $long_description;

    /**
     * @ORM\Column(type="integer")
     */
    protected $initial_quantity = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity_on_hold = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity_available = 0;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $initial_price;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="productsAsSeller")
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    protected $seller;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="products")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * @ORM\OneToMany(targetEntity="OrderLineItem", mappedBy="product")
     */
    protected $orderLineItems;

    /**
     * @ORM\OneToMany(
     *    targetEntity="BaseStockTransaction",
     *    mappedBy="product",
     *    cascade={"persist"}
     * )
     */
    protected $stockTransactions;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @Doctrine\ORM\Mapping\Column(length=255)
     */
    protected $category_path;

    /**
     * quantity_in_cart
     *
     * This is not persisted to the database
     *
     * @param integer
     */
    protected $quantity_in_cart = 0;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     */
    public function __construct()
    {
        $this->orderLineItems    = new ArrayCollection();
        $this->stockTransactions = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set short_description
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    }

    /**
     * Get short_description
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set long_description
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param string $longDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->long_description = $longDescription;
    }

    /**
     * Get long_description
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->long_description;
    }

    /**
     * Set latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param decimal $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return decimal
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param decimal $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return decimal
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set location
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param HarvestCloud\CoreBundle\Entity\Location $location
     */
    public function setLocation(\HarvestCloud\CoreBundle\Entity\Location $location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return HarvestCloud\CoreBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * getMapLabel
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getMapLabel()
    {
        if (!$this->name)
        {
            return 'Product #'.$this->getId();
        }

        return $this->name;
    }

    /**
     * prePersist
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        // To make it easier to search for Products geographically, we cache
        // the latitude and longitude on the Product
        $this->setLatitude($this->getLocation()->getLatitude());
        $this->setLongitude($this->getLocation()->getLongitude());

        // Initially, the quantity_available will be the initial_quantity
        $this->setQuantityAvailable($this->getInitialQuantity());

        // Set the initial_price
        $this->setInitialPrice($this->getPrice());

        // Create initial stock transaction
        $stockTransaction = new InitialStockTransaction();
        $stockTransaction->setQuantity($this->getInitialQuantity());
        $stockTransaction->setStatusCode(OrderStockTransaction::STATUS_COMPLETE);

        $this->addStockTransaction($stockTransaction);
    }

    /**
     * Add orderLineItems
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param HarvestCloud\CoreBundle\Entity\OrderLineItem $orderLineItems
     */
    public function addOrderLineItem(\HarvestCloud\CoreBundle\Entity\OrderLineItem $orderLineItems)
    {
        $this->orderLineItems[] = $orderLineItems;
    }

    /**
     * Get orderLineItems
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOrderLineItems()
    {
        return $this->orderLineItems;
    }

    /**
     * Add stockTransaction
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param HarvestCloud\CoreBundle\Entity\BaseStockTransaction $stockTransactions
     */
    public function addStockTransaction(\HarvestCloud\CoreBundle\Entity\BaseStockTransaction $stockTransaction)
    {
        $this->stockTransactions[] = $stockTransaction;
        $stockTransaction->setProduct($this);
    }

    /**
     * Get stockTransactions
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getStockTransactions()
    {
        return $this->stockTransactions;
    }

    /**
     * Set initial_quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param integer $initialQuantity
     */
    public function setInitialQuantity($initialQuantity)
    {
        $this->initial_quantity = $initialQuantity;
    }

    /**
     * Get initial_quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return integer
     */
    public function getInitialQuantity()
    {
        return $this->initial_quantity;
    }

    /**
     * Set quantity_on_hold
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param integer $quantityOnHold
     */
    public function setQuantityOnHold($quantityOnHold)
    {
        $this->quantity_on_hold = $quantityOnHold;
    }

    /**
     * Get quantity_on_hold
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return integer
     */
    public function getQuantityOnHold()
    {
        return $this->quantity_on_hold;
    }

    /**
     * Set quantity_available
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param integer $quantityAvailable
     */
    public function setQuantityAvailable($quantityAvailable)
    {
        $this->quantity_available = $quantityAvailable;
    }

    /**
     * Get quantity_available
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return integer
     */
    public function getQuantityAvailable()
    {
        return $this->quantity_available;
    }

    /**
     * Set price
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-16
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-16
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set initial_price
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-16
     *
     * @param decimal $initialPrice
     */
    public function setInitialPrice($initialPrice)
    {
        $this->initial_price = $initialPrice;
    }

    /**
     * Get initial_price
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-16
     *
     * @return decimal
     */
    public function getInitialPrice()
    {
        return $this->initial_price;
    }

    /**
     * Doctrine generates this method each time I run doctrine:generate:entities
     * I have no intention of using it, but I can't be bothered to delete it
     * each time.
     *
     * Use Product::addStockTransaction() instead
     */
    public function addBaseStockTransaction(BaseStockTransaction $stockTransactions)
    {
        throw new Exception('Not implemented');
    }

    /**
     * Set category
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-18
     *
     * @param HarvestCloud\CoreBundle\Entity\Category $category
     */
    public function setCategory(\HarvestCloud\CoreBundle\Entity\Category $category)
    {
        $this->category = $category;

        // copy over the path
        $this->setCategoryPath($category->getPath());
    }

    /**
     * Get category
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-18
     *
     * @return HarvestCloud\CoreBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set categoryPath
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @param string $categoryPath
     */
    public function setCategoryPath($categoryPath)
    {
        $this->category_path = $categoryPath;
    }

    /**
     * Get categoryPath
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @return string
     */
    public function getCategoryPath()
    {
        return $this->category_path;
    }

    /**
     * Set seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  Profile $seller
     */
    public function setSeller(Profile $seller)
    {
        $this->seller = $seller;
        $this->setLocation($seller->getDefaultLocation());
    }

    /**
     * Get Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Profile
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * setQuantityInCart()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-12-23
     *
     * @param  int  $quantity_in_cart
     */
    public function setQuantityInCart($quantity_in_cart)
    {
        $this->quantity_in_cart = (int) $quantity_in_cart;
    }

    /**
     * getQuantityInCart()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-12-23
     *
     * @return int
     */
    public function getQuantityInCart()
    {
        return $this->quantity_in_cart;
    }

    /**
     * adjustQuantity()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-21
     *
     * @param  integer $quantity
     */
    public function adjustQuantity($quantity)
    {
      $this->setQuantityAvailable($this->getQuantityAvailable()+$quantity);
    }
}
