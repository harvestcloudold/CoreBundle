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
     * getSeller()
     *
     * Proxy method
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
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
     * @since  2012-10-03
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->getSellerHubRef()->getHub();
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
}
