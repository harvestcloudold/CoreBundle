<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProductSubscription Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-10-06
 *
 * @ORM\Entity
 * @ORM\Table(name="product_subscription")
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\ProductSubscriptionRepository")
 */
class ProductSubscription
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
     * @ORM\Column(type="integer")
     */
    protected $quantity = 0;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price_at_subscription;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="productSubscriptionsAsBuyer")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     */
    protected $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
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
     * @since  2013-10-06
     *
     * @param  string $name
     *
     * @return ProductSubscription
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @param  integer $quantity
     *
     * @return ProductSubscription
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price_at_subscription
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @param  float $priceAtSubscription
     *
     * @return ProductSubscription
     */
    public function setPriceAtSubscription($priceAtSubscription)
    {
        $this->price_at_subscription = $priceAtSubscription;

        return $this;
    }

    /**
     * Get price_at_subscription
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @return float
     */
    public function getPriceAtSubscription()
    {
        return $this->price_at_subscription;
    }

    /**
     * Set buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $buyer
     *
     * @return ProductSubscription
     */
    public function setBuyer(\HarvestCloud\CoreBundle\Entity\Profile $buyer = null)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set product
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Product $product
     *
     * @return ProductSubscription
     */
    public function setProduct(\HarvestCloud\CoreBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @return \HarvestCloud\CoreBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
