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
     * @ORM\OneToMany(targetEntity="SellerHubPickupWindow", mappedBy="hubWindow")
     */
    protected $sellerHubPickupWindows;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     */
    public function __construct()
    {
        $this->sellerHubPickupWindows = new ArrayCollection();
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
     * Add sellerHubPickupWindows
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerHubPickupWindow $sellerHubPickupWindow
     */
    public function addSellerHubPickupWindow(\HarvestCloud\CoreBundle\Entity\SellerHubPickupWindow $sellerHubPickupWindow)
    {
        $this->sellerHubPickupWindows[] = $sellerHubPickupWindow;

        $sellerHubPickupWindow->setHubWindow($this);
    }

    /**
     * Get sellerHubPickupWindows
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSellerHubPickupWindows()
    {
        return $this->sellerHubPickupWindows;
    }
}
