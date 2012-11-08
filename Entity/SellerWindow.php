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
use HarvestCloud\CoreBundle\Util\Windowable;

/**
 * SellerWindow Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-28
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="delivery_type", type="string")
 * @ORM\DiscriminatorMap({
 *    "PICKUP"   = "SellerPickupWindow",
 *    "DELIVERY" = "SellerDeliveryWindow"
 * })
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\SellerWindowRepository")
 * @ORM\Table(name="seller_window",uniqueConstraints={@ORM\UniqueConstraint(name="seller_hub_ref_delivery_type_start_time_idx", columns={"seller_hub_ref_id", "delivery_type", "start_time"})})
 */
class SellerWindow implements Windowable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $start_time;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $end_time;

    /**
     * @ORM\ManyToOne(targetEntity="SellerHubRef", inversedBy="pickupWindows")
     * @ORM\JoinColumn(name="seller_hub_ref_id", referencedColumnName="id")
     */
    protected $sellerHubRef;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="pickupWindow")
     */
    protected $orders;

    /**
     * @ORM\ManyToOne(targetEntity="HubWindow", inversedBy="sellerWindows")
     * @ORM\JoinColumn(name="hub_window_id", referencedColumnName="id")
     */
    protected $hubWindow;

    /**
     * @ORM\ManyToOne(targetEntity="SellerWindowMaker", inversedBy="windows")
     * @ORM\JoinColumn(name="window_maker_id", referencedColumnName="id")
     */
    protected $windowMaker;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-19
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set start_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @param datetime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->start_time = $startTime;
    }

    /**
     * Get start_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @return datetime
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Set end_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @param datetime $endTime
     */
    public function setEndTime($endTime)
    {
        $this->end_time = $endTime;
    }

    /**
     * Get end_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @return datetime
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Set sellerHubRef
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @param SellerHubRef $sellerHubRef
     */
    public function setSellerHubRef(SellerHubRef $sellerHubRef)
    {
        $this->sellerHubRef = $sellerHubRef;
    }

    /**
     * Get sellerHubRef
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-28
     *
     * @return SellerHubRef
     */
    public function getSellerHubRef()
    {
        return $this->sellerHubRef;
    }

    /**
     * Add orders
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-19
     *
     * @param  Order $orders
     */
    public function addOrder(\HarvestCloud\CoreBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Get orders
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-19
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * getSeller()
     *
     * Proxy method
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-10
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getSeller()
    {
        return $this->getSellerHubRef()->getSeller();
    }

    /**
     * getHub()
     *
     * Proxy method
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-10
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->getSellerHubRef()->getHub();
    }

    /**
     * Set hubWindow
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-23
     *
     * @param HarvestCloud\CoreBundle\Entity\HubWindow $hubWindow
     */
    public function setHubWindow(\HarvestCloud\CoreBundle\Entity\HubWindow $hubWindow)
    {
        $this->hubWindow = $hubWindow;
    }

    /**
     * Get hubWindow
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-23
     *
     * @return HarvestCloud\CoreBundle\Entity\HubWindow
     */
    public function getHubWindow()
    {
        return $this->hubWindow;
    }

    /**
     * Set windowMaker
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-29
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerWindowMaker $windowMaker
     */
    public function setWindowMaker(\HarvestCloud\CoreBundle\Entity\SellerWindowMaker $windowMaker)
    {
        $this->windowMaker = $windowMaker;
    }

    /**
     * Get windowMaker
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-29
     *
     * @return HarvestCloud\CoreBundle\Entity\SellerWindowMaker
     */
    public function getWindowMaker()
    {
        return $this->windowMaker;
    }

    /**
     * getTotalHubFeeForOrder()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-07
     *
     * @param  HarvestCloud\CoreBundle\Entity\Order  $order
     *
     * @return float
     */
    public function getTotalHubFeeForOrder(\HarvestCloud\CoreBundle\Entity\Order $order)
    {
        return ($order->getLineItemTotal() * $this->getSellerHubRef()->getVariableFee()) + $this->getSellerHubRef()->getFixedFee();
    }
}
