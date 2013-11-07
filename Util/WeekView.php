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
     * first_day_of_week
     *
     * @var int
     */
    protected $first_day_of_week;

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
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-05
     *
     * @param  mixed $firstDay
     */
    public function __construct($firstDay = DayOfWeek::MON)
    {
        $this->generateDays($firstDay);
        $this->generateSlots($firstDay);
    }

    /**
     * generateDays()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-06
     *
     * @param  mixed $firstDay
     */
    public function generateDays($firstDay)
    {
        // Assume we start on Monday
        for ($i=1; $i<8; $i++)
        {
            $dayOfWeek = new DayOfWeek($i);
            $this->days[] = array(
                'label' => $dayOfWeek->getShortName(),
                'class' => $dayOfWeek->getClassName(),
            );
        }

        if (is_int($firstDay))
        {
            $first_day = $firstDay;
        }
        else
        {
            $first_day = $firstDay->format('N');
        }

        // Now, reorder for start day
        for ($i=1; $i<$first_day; $i++)
        {
            array_push($this->days, array_shift($this->days));
        }
    }

    /**
     * generateSlots()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-06
     *
     * @param  mixed $firstDay
     */
    public function generateSlots($firstDay)
    {
        foreach (array_keys(WindowMaker::getStartTimeChoices()) as $hour)
        {
            $time = array(
                'label'     => $hour.':00 - '.($hour+2).':00',
                'time_key'  => $hour.':00',
                'days'      => array(),
            );

            for ($i=1; $i<8; $i++)
            {
                $time['days'][] = array(
                    'day_of_week_number' => $i,
                    'objects'            => array(),
                );
            }

            if (is_int($firstDay))
            {
                $first_day = $firstDay;
            }
            else
            {
                $first_day = $firstDay->format('N');
            }

            // Now, reorder for start day
            for ($i=1; $i<$first_day; $i++)
            {
                array_push($time['days'], array_shift($time['days']));
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
     * @param  int                    $day_of_week_number
     * @param  string                 $time_key
     * @param  WeekViewObjectInteface $object
     */
    public function addObject($day_of_week_number, $time_key, WeekViewObjectInteface $object)
    {
        foreach ($this->slots['times'] as $i => $time)
        {
            foreach ($time['days'] as $j => $day)
            {
                if ($time_key == $time['time_key'] && $day_of_week_number == $day['day_of_week_number'])
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
