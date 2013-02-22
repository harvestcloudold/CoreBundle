<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderLineItem Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-07
 *
 * @ORM\Entity
 * @ORM\Table(name="order_line_item")
 */
class OrderLineItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="lineItems", cascade={"persist"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderLineItems", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;


    /**
     * @ORM\OneToOne(targetEntity="OrderStockTransaction", inversedBy="lineItem", cascade={"persist"})
     * @ORM\JoinColumn(name="stock_transaction_id", referencedColumnName="id")
     */
    protected $stockTransaction;

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     *
     * @param HarvestCloud\CoreBundle\Entity\Order $order
     */
    public function setOrder(\HarvestCloud\CoreBundle\Entity\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     *
     * @return HarvestCloud\CoreBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     *
     * @param HarvestCloud\CoreBundle\Entity\Product $product
     */
    public function setProduct(\HarvestCloud\CoreBundle\Entity\Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get product
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     *
     * @return HarvestCloud\CoreBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-10
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-10
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set stockTransaction
     *
     * @param HarvestCloud\CoreBundle\Entity\OrderStockTransaction $stockTransaction
     */
    public function setStockTransaction(\HarvestCloud\CoreBundle\Entity\OrderStockTransaction $stockTransaction)
    {
        $this->stockTransaction = $stockTransaction;
    }

    /**
     * Get stockTransaction
     *
     * @return HarvestCloud\CoreBundle\Entity\OrderStockTransaction 
     */
    public function getStockTransaction()
    {
        return $this->stockTransaction;
    }

    /**
     * Set price
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
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
     * @since  2012-04-22
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * getSubTotal()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-20
     *
     * @return decimal
     */
    public function getSubTotal()
    {
      return $this->getQuantity() * $this->getPrice();
    }

    /**
     * getUnit()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-21
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->getProduct()->getUnitForNumber($this->getQuantity());
    }
}
