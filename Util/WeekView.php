<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Util;

use HarvestCloud\CoreBundle\Entity\WindowMaker;
use HarvestCloud\CoreBundle\Util\WeekViewObjectInteface;

/**
 * A utility class to represent a WeekView of some objects
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2013-11-03
 */
class WeekView
{
    /**
     * startDate
     *
     * @var \DateTime
     */
    protected $startDate;

    /**
     * startDate
     *
     * @var \DateTime
     */
    protected $endDate;

    /**
     * slots
     *
     * @var array
     */
     protected $slots = array();

    /**
     * days
     *
     * @var array
     */
     protected $days = array();

    /**
     * day_format
     *
     * @var string
     */
    protected $day_format = 'D';

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-05
     *
     * @param  \DateTime $startDate
     * @param  \DateTime $endDate
     * @param  string    $day_format
     */
    public function __construct(\DateTime $startDate, \DateTime $endDate = null, $day_format = 'D')
    {
        $this->startDate  = $startDate;

        if ($endDate)
        {
            $this->endDate = $endDate;
        }
        else
        {
            $this->endDate = clone $this->startDate;
            $this->endDate->add(new \DateInterval('P6D'));
        }

        $this->day_format = $day_format;

        $this->generateDays();
        $this->generateSlots();
    }

    /**
     * generateDays()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-06
     */
    public function generateDays()
    {
        $interval = $this->startDate->diff($this->endDate);

        $day = clone $this->startDate;

        for ($i=0; $i<$interval->days+1; $i++)
        {
            if ($i)
            {
                $day->add(new \DateInterval('P1D'));
            }

            $this->days[] = array(
                'label' => $day->format($this->day_format),
                'class' => strtolower($day->format('l')),
            );
        }
    }

    /**
     * generateSlots()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-06
     */
    public function generateSlots()
    {
        $interval = $this->startDate->diff($this->endDate);

        foreach (array_keys(WindowMaker::getStartTimeChoices()) as $hour)
        {
            $time = array(
                'label'     => $hour.':00 - '.($hour+2).':00',
                'time_key'  => $hour.':00',
                'days'      => array(),
            );

            $day = new \DateTime($this->startDate->format('Y-m-d '.$time['time_key']));;

            for ($i=0; $i<$interval->days+1; $i++)
            {
                if ($i)
                {
                    $day->add(new \DateInterval('P1D'));
                }

                $time['days'][] = array(
                    'dateTime' => clone $day,
                    'objects'  => array(),
                );
            }

            $this->slots['times'][] = $time;
        }

        return $this->slots;
    }

    /**
     * addObject()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-06
     *
     * @param  WeekViewObjectInteface $object
     */
    public function addObject(WeekViewObjectInteface $object)
    {
        foreach ($this->slots['times'] as $i => $time)
        {
            foreach ($time['days'] as $j => $day)
            {
                if ($object->getDateTimeForWeekView()->format(\DateTime::ATOM) == $day['dateTime']->format(\DateTime::ATOM))
                {
                    $this->slots['times'][$i]['days'][$j]['objects'][] = $object;
                }
            }
        }
    }

    /**
     * getDays()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-05
     *
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * getTimes()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-06
     *
     * @return array
     */
    public function getTimes()
    {
        return $this->slots['times'];
    }
}
