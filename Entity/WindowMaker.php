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
use HarvestCloud\CoreBundle\Util\WeekViewObjectInteface;
use HarvestCloud\CoreBundle\Entity\HubWindow;

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
 *    "SELLER_HUB" = "SellerWindowMaker"
 * })
 * @ORM\Table(name="window_maker")
 */
class WindowMaker implements WeekViewObjectInteface
{
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
    protected $delivery_type = HubWindow::DELIVERY_TYPE_PICKUP;

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
     * @ORM\Column(type="datetime", nullable=true)
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

        // Let's go ahead and set the default end_time
        $end_time = $this->getEndTimeFromStartTime($this->getStartTimeObject())
            ->format('H:i')
        ;
        $this->setEndTime($end_time);
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
     * getEndTimeWithSeconds()
     *
     * e.g. 12:00:00
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-05
     *
     * @return string
     */
    public function getEndTimeWithSeconds()
    {
        return $this->getEndTime().':00';
    }

    /**
     * getEndTimeObject
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-05
     *
     * @param  string  $date_string
     *
     * @return \DateTime
     */
    public function getEndTimeObject($date_string = null)
    {
        if (null == $date_string)
        {
            $date_string = date('Y-m-d');
        }

        return new \DateTime($date_string.' '.$this->getEndTimeWithSeconds());
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
     * @param  integer    $num_days
     *
     * @return array
     */
    public function getDateAdjustedStartTimes(\DateTime $startDate, $num_days)
    {
        $array = array();

        for ($i=0; $i<$num_days; $i++)
        {
            $startDate->add(\DateInterval::createFromDateString('+1 day'));

            if (in_array($startDate->format('N'), $this->getDayOfWeekNumbers()))
            {
                $dayOfWeek = new DayOfWeek($startDate->format('N'));

                $startTime = $this->getStartTimeObject($startDate->format('Y-m-d'));

                while ($startTime->format('H:i') < $this->getEndTime())
                {
                    $array[] = clone $startTime;

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
            HubWindow::DELIVERY_TYPE_PICKUP,
            HubWindow::DELIVERY_TYPE_DELIVERY,
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

    /**
     * getStartTimeChoices()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @return array
     */
    public static function getStartTimeChoices()
    {
        return array(
            '07' => '7am',
            '09' => '9am',
            '11' => '11am',
            '13' => '1pm',
            '15' => '3pm',
            '17' => '5pm',
            '19' => '7pm',
        );
    }

    /**
     * getEndTimeChoices()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @return array
     */
    public static function getEndTimeChoices()
    {
        return array(
            '09' => '9am',
            '11' => '11am',
            '13' => '1pm',
            '15' => '3pm',
            '17' => '5pm',
            '19' => '7pm',
            '21' => '9pm',
        );
    }

    /**
     * getEndTimeFromStartTime()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @param  \DateTime  $startTime
     *
     * @return \DateTime
     */
    public function getEndTimeFromStartTime(\DateTime $startTime)
    {
        $endTime  = clone $startTime;
        $endTime->add(\DateInterval::createFromDateString('+2 hour'));

        return $endTime;
    }
}
