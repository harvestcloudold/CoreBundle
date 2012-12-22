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
 * HubWindow Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-24
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\HubWindowRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="delivery_type", type="string")
 * @ORM\DiscriminatorMap({
 *    "PICKUP"   = "HubPickupWindow",
 *    "DELIVERY" = "HubDeliveryWindow"
 * })
 * @ORM\Table(name="hub_window",uniqueConstraints={@ORM\UniqueConstraint(name="hub_delivery_type_start_time_idx", columns={"hub_id", "delivery_type", "start_time"})})
 */
class HubWindow implements Windowable
{
    /**
     * Delivery Types
     *
     * These should match the discriminators listed above
     *
     * @var string
     */
     const DELIVERY_TYPE_DELIVERY = 'DELIVERY';
     const DELIVERY_TYPE_PICKUP   = 'PICKUP';

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
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="hubWindows")
     * @ORM\JoinColumn(name="hub_id", referencedColumnName="id")
     */
    protected $hub;

    /**
     * @ORM\OneToMany(targetEntity="SellerWindow", mappedBy="hubWindow")
     */
    protected $sellerWindows;

    /**
     * @ORM\ManyToOne(targetEntity="HubWindowMaker", inversedBy="windows")
     * @ORM\JoinColumn(name="window_maker_id", referencedColumnName="id")
     */
    protected $windowMaker;

    /**
     * total_hub_fee_for_order_collection
     *
     * This field is not persisted to the database. It is only used as a way
     * of storing the total fee for the Buyer window selector
     *
     * @var float
     */
    protected $total_hub_fee_for_order_collection;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     */
    public function __construct()
    {
        $this->sellerWindows = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
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
     * @since  2012-10-24
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
     * @since  2012-10-24
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
     * @since  2012-10-24
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
     * @since  2012-10-24
     *
     * @return datetime
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Set hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $hub
     */
    public function setHub(\HarvestCloud\CoreBundle\Entity\Profile $hub)
    {
        $this->hub = $hub;
    }

    /**
     * Get hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->hub;
    }

    /**
     * Add sellerWindow
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerWindow $sellerWindow
     */
    public function addSellerWindow(\HarvestCloud\CoreBundle\Entity\SellerWindow $sellerWindow)
    {
        $this->sellerWindows[] = $sellerWindow;

        $sellerWindow->setHubWindow($this);
    }

    /**
     * Get sellerWindows
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSellerWindows()
    {
        return $this->sellerWindows;
    }

    /**
     * Set windowMaker
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @param HarvestCloud\CoreBundle\Entity\HubWindowMaker $windowMaker
     */
    public function setWindowMaker(\HarvestCloud\CoreBundle\Entity\HubWindowMaker $windowMaker)
    {
        $this->windowMaker = $windowMaker;
    }

    /**
     * Get windowMaker
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @return HarvestCloud\CoreBundle\Entity\HubWindowMaker
     */
    public function getWindowMaker()
    {
        return $this->windowMaker;
    }

    /**
     * getTotalHubFeeForOrderCollection()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-07
     *
     * @return float
     */
    public function getTotalHubFeeForOrderCollection()
    {
        if (!$this->total_hub_fee_for_order_collection)
        {
            throw new \Exception('total_hub_fee_for_order_collection for HubWindow #'.$this->getId().' has not been set yet');
        }

        return $this->total_hub_fee_for_order_collection;
    }

    /**
     * setTotalHubFeeForOrderCollection()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-07
     *
     * @param  OrderCollection
     */
    public function setTotalHubFeeForOrderCollection(OrderCollection $orderCollection)
    {
        foreach ($this->getSellerWindows() as $window)
        {
            $order = $orderCollection->getOrderForSeller($window->getSeller());

            if ($order)
            {
                $this->total_hub_fee_for_order_collection += $window->getTotalHubFeeForOrder($order);
            }
        }
    }

    /**
     * getSlots()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-12-22
     *
     * @return array
     */
    public static function getSlots()
    {
        $slots = array();

        foreach (array_keys(WindowMaker::getStartTimeChoices()) as $hour)
        {
            for ($i=0; $i<14; $i++)
            {
                $date = new \DateTime('+'.$i.' days');

                $slots[$hour.':00'][$date->format('Y-m-d')] = null;
            }
        }

        return $slots;
    }
}
