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
use HarvestCloud\CoreBundle\Util\Debug;

/**
 * SellerHubRef Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-26
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\SellerHubRefRepository")
 * @ORM\Table(name="seller_hub_ref",indexes={@ORM\index(name="seller_hub_idx", columns={"seller_id", "hub_id"})})
 */
class SellerHubRef
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="sellerHubRefsAsSeller")
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    protected $seller;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="sellerHubRefsAsHub")
     * @ORM\JoinColumn(name="hub_id", referencedColumnName="id")
     */
    protected $hub;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_default = false;

    /**
     * The fixed part (in dollars) of the fee that a Buyer is charged by a
     * Seller for an Order to be delivered to a Hub
     *
     *   e.g. $0.10 per order (see caculation below)
     *
     * @ORM\Column(type="decimal", scale="2")
     */
    protected $fixed_fee = 0.25;

    /**
     * The variable part (%) of the fee that a Buyer is charged by a Seller
     * for an Order to be delivered to a Hub
     *
     *   e.g. Order with total of $20
     *
     *     Fixed fee @ $0.20      $0.20
     *     Variable @ 4.00%       $0.80
     *                            -----
     *     Total fee              $1.00
     *
     * @ORM\Column(type="decimal", scale="3")
     */
    protected $variable_fee = 5;

    /**
     * @ORM\OneToMany(targetEntity="SellerHubPickupWindow", mappedBy="sellerHubRef", cascade={"persist"})
     */
    protected $pickupWindows;

    /**
     * @ORM\OneToMany(targetEntity="SellerHubPickupWindowMaker", mappedBy="sellerHubRef", cascade={"persist"})
     */
    protected $windowMakers;

    /**
     * __construct
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     */
    public function __construct()
    {
        $this->pickupWindows = new ArrayCollection();
        $this->windowMakers  = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set is_default
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param boolean $is_default
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;
    }

    /**
     * Get is_default
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Proxy for getIsDefault()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->getIsDefault();
    }

    /**
     * Set seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param  Profile $seller
     */
    public function setSeller(Profile $seller)
    {
        $this->seller = $seller;
    }

    /**
     * Get seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return Profile
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param  Profile $hub
     */
    public function setHub(Profile $hub)
    {
        $this->hub = $hub;
    }

    /**
     * Get hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return Profile
     */
    public function getHub()
    {
        return $this->hub;
    }

    /**
     * getHubName()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-09
     *
     * @return string
     */
    public function getHubName()
    {
        return $this->hub->getName();
    }

    /**
     * Add pickupWindow
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @param SellerHubPickupWindow $pickupWindow
     */
    public function addSellerHubPickupWindow(SellerHubPickupWindow $pickupWindow)
    {
        $this->pickupWindows[] = $pickupWindow;

        $pickupWindow->setSellerHubRef($this);
    }

    /**
     * Get pickupWindows
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPickupWindows()
    {
        return $this->pickupWindows;
    }

    /**
     * getPickupWindowsIndexedByStartTime()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-05
     *
     * @return array
     */
    public function getPickupWindowsIndexedByStartTime()
    {
        $windows = array();

        foreach ($this->getPickupWindows() as $window)
        {
            $windows[$window->getStartTime()->format('Y-m-d H:i:s')] = $window;
        }

        return $windows;
    }

    /**
     * Set fixed_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @param  decimal $fixedFee
     */
    public function setFixedFee($fixedFee)
    {
        $this->fixed_fee = $fixedFee;
    }

    /**
     * Get fixed_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @return decimal
     */
    public function getFixedFee()
    {
        return $this->fixed_fee;
    }

    /**
     * Set variable_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @param  decimal $variableFee
     */
    public function setVariableFee($variableFee)
    {
        $this->variable_fee = $variableFee;
    }

    /**
     * Get variable_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @return decimal
     */
    public function getVariableFee()
    {
        return $this->variable_fee;
    }

    /**
     * addWindowMaker()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-04
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerHubPickupWindowMaker $windowMaker
     */
    public function addWindowMaker(\HarvestCloud\CoreBundle\Entity\SellerHubPickupWindowMaker $windowMaker)
    {
        $this->windowMakers[] = $windowMaker;

        $windowMaker->setSellerHubRef($this);
    }

    /**
     * getWindowMakers()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-04
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getWindowMakers()
    {
        return $this->windowMakers;
    }

    /**
     * hasWindowAtThisTime()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-07
     *
     * @param  string $date_time
     */
     public function hasWindowAtThisTime($date_time)
     {
        if (array_key_exists($date_time, $this->getPickupWindowsIndexedByStartTime()))
        {
            return true;
        }

        return false;
     }
}
