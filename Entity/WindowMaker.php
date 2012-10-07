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
use HarvestCloud\CoreBundle\Util\DayOfWeek;

/**
 * WindowMaker Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-01
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\WindowMakerRepository")
 * @ORM\Table(name="window_maker")
 */
class WindowMaker
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SellerHubRef", inversedBy="windowMakers")
     * @ORM\JoinColumn(name="seller_hub_ref_id", referencedColumnName="id")
     */
    protected $sellerHubRef;

    /**
     * @ORM\Column(type="array", name="day_of_week_numbers")
     */
    protected $dayOfWeekNumbers;

    /**
     * Start time in format HH:MM
     *
     * @ORM\Column(type="string", length=5)
     */
    protected $start_time;

    /**
     * End time in format HH:MM
     *
     * @ORM\Column(type="string", length=5)
     */
    protected $end_time;

    /**
     * getId()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-01
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setSellerHubRef()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-01
     *
     * @param HarvestCloud\CoreBundle\Entity\SellerHubRef $sellerHubRef
     */
    public function setSellerHubRef(\HarvestCloud\CoreBundle\Entity\SellerHubRef $sellerHubRef)
    {
        $this->sellerHubRef = $sellerHubRef;
    }

    /**
     * getSellerHubRef()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-01
     *
     * @return HarvestCloud\CoreBundle\Entity\SellerHubRef
     */
    public function getSellerHubRef()
    {
        return $this->sellerHubRef;
    }

    /**
     * Get daysOfWeek
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @return array of DayOfWeek objects
     */
    public function getDaysOfWeekAsObjects()
    {
        $daysOfWeeks = array();

        foreach ($this->getDayOfWeekNumbers() as $day_of_week_number)
        {
            $daysOfWeeks[] = new DayOfWeek($day_of_week_number);
        }

        return $daysOfWeeks;
    }

    /**
     * Set start_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @param string $startTime
     */
    public function setStartTime($startTime)
    {
        $this->start_time = $startTime;
    }

    /**
     * Get start_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Set end_time
     *
     * @author Tom Haskins-Vaughan <tomhv@janeiredale.com>
     * @since  2012-10-02
     *
     * @param string $endTime
     */
    public function setEndTime($endTime)
    {
        $this->end_time = $endTime;
    }

    /**
     * Get end_time
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @return string
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Set dayOfWeekNumbers
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @param array $dayOfWeekNumbers
     */
    public function setDayOfWeekNumbers($dayOfWeekNumbers)
    {
        $this->dayOfWeekNumbers = $dayOfWeekNumbers;
    }

    /**
     * Get dayOfWeekNumbers
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @return array
     */
    public function getDayOfWeekNumbers()
    {
        return $this->dayOfWeekNumbers;
    }
}
