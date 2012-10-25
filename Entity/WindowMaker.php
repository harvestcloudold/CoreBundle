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
use HarvestCloud\CoreBundle\Util\Debug;

/**
 * WindowMaker Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-01
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\WindowMakerRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="window_type", type="string")
 * @ORM\DiscriminatorMap({
 *    "HUB"        = "HubWindowMaker",
 *    "SELLER_HUB" = "SellerHubPickupWindowMaker"
 * })
 * @ORM\Table(name="window_maker")
 */
class WindowMaker
{
    /**
     * delivery_type options
     *
     * @var string
     */
     const
        DELIVERY_TYPE_DELIVERY = 'DELIVERY',
        DELIVERY_TYPE_PICKUP   = 'PICKUP'
    ;


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * delivery_type
     *
     * @ORM\Column(type="string", length=8)
     */
    protected $delivery_type = self::DELIVERY_TYPE_PICKUP;

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
     * @ORM\Column(type="datetime")
     */
    protected $last_run_at;

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
     * We accept the following:
     *
     *   * \DateTime object
     *   * 07:00
     *   * 07
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @param  mixed $endTime string or \DateTime
     */
    public function setStartTime($startTime)
    {
        if ($startTime instanceof \DateTime)
        {
            $startTime = $startTime->format('H:i');
        }

        // deal with "hour-only" input
        if (2 == strlen($startTime))
        {
            $startTime .= ':00';
        }

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
     * getStartTimeWithSeconds()
     *
     * e.g. 12:00:00
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-05
     *
     * @return string
     */
    public function getStartTimeWithSeconds()
    {
        return $this->getStartTime().':00';
    }

    /**
     * getStartTimeObject
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-05
     *
     * @param  string  $date_string
     *
     * @return \DateTime
     */
    public function getStartTimeObject($date_string = null)
    {
        if (null == $date_string)
        {
            $date_string = date('Y-m-d');
        }

        return new \DateTime($date_string.' '.$this->getStartTimeWithSeconds());
    }

    /**
     * Set end_time
     *
     * We accept the following:
     *
     *   * \DateTime object
     *   * 07:00
     *   * 07
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @param  mixed $endTime string or \DateTime
     */
    public function setEndTime($endTime)
    {
        if ($endTime instanceof \DateTime)
        {
            $endTime = $endTime->format('H:i');
        }

        // deal with "hour-only" input
        if (2 == strlen($endTime))
        {
            $endTime .= ':00';
        }

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

    /**
     * getDaysOfWeekAsString()
     *
     * e.g. M-T-FS-
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
     *
     * @return string
     */
    public function getDaysOfWeekAsString()
    {
        $string = '';

        for ($i=1; $i<8; $i++)
        {
            if (in_array($i, $this->getDayOfWeekNumbers()))
            {
                $dayOfWeek = new DayOfWeek($i);
                $string .= $dayOfWeek->getFirstLetter();
            }
            else
            {
                $string .= '-';
            }
        }

        return $string;
    }

    /**
     * getDateAdjustedStartTimes()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
     *
     * @param  \DateTime  $startDate
     * @param  integer    $period
     *
     * @return array
     */
    public function getDateAdjustedStartTimes(\DateTime $startDate, $period)
    {
        $array = array();

        for ($i=0; $i<$period; $i++)
        {
            $startDate->add(\DateInterval::createFromDateString('+1 day'));
            if (in_array($startDate->format('N'), $this->getDayOfWeekNumbers()))
            {
                $dayOfWeek = new DayOfWeek($startDate->format('N'));

                $startTime = $this->getStartTimeObject($startDate->format('Y-m-d'));

                while ($startTime->format('H:i') < $this->getEndTime())
                {
                    $date_time_string  = $startTime->format('Y-m-d H:i:s');

                    $array[] = $date_time_string;

                    $startTime->add(\DateInterval::createFromDateString('+2 hours'));
                }
            }
        }

        return $array;
    }

    /**
     * Set delivery_type
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param string $deliveryType
     */
    public function setDeliveryType($deliveryType)
    {
        if (!in_array($deliveryType, array(
            self::DELIVERY_TYPE_PICKUP,
            self::DELIVERY_TYPE_DELIVERY,
        )))
        {
            throw new \InvalidArgumentException('Invalid value for delivery_type');
        }

        $this->delivery_type = $deliveryType;
    }

    /**
     * Get delivery_type
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return string
     */
    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    /**
     * Set last_run_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param datetime $lastRunAt
     */
    public function setLastRunAt($lastRunAt)
    {
        $this->last_run_at = $lastRunAt;
    }

    /**
     * Get last_run_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return datetime
     */
    public function getLastRunAt()
    {
        return $this->last_run_at;
    }
}