<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Util;

/**
 * A utility class to represent Days of the Week
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2012-10-02
 */
class DayOfWeek
{
    /**
     * Day of Week numbers
     *
     * @var integer
     */
    const
        MON = 1,
        TUE = 2,
        WED = 3,
        THU = 4,
        FRI = 5,
        SAT = 6,
        SUN = 7
    ;

    /**
     * Day of week number (e.g. 1 = Mon, 7 = Sun)
     *
     * @var integer
     */
    protected $day_of_week_number;

    /**
     * __constuct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @param  integer  $day_of_week_number
     */
    public function __construct($day_of_week_number)
    {
        if (!in_array((int) $day_of_week_number, range(1,7)))
        {
            throw new Exception('Incorrect parameter');
        }

        $this->day_of_week_number = $day_of_week_number;
    }

    /**
     * getFirstLetter()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-03
     *
     * @return string
     */
    public function getFirstLetter()
    {
        switch ($this->day_of_week_number)
        {
            case self::MON: return 'M';
            case self::TUE: return 'T';
            case self::WED: return 'W';
            case self::THU: return 'T';
            case self::FRI: return 'F';
            case self::SAT: return 'S';
            case self::SUN: return 'S';
        }
    }

    /**
     * getShortName()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @return string
     */
    public function getShortName()
    {
        switch ($this->day_of_week_number)
        {
            case self::MON: return 'Mon';
            case self::TUE: return 'Tue';
            case self::WED: return 'Wed';
            case self::THU: return 'Thu';
            case self::FRI: return 'Fri';
            case self::SAT: return 'Sat';
            case self::SUN: return 'Sun';
        }
    }

    /**
     * getLongName()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-02
     *
     * @return string
     */
    public function getLongName()
    {
        switch ($this->day_of_week_number)
        {
            case self::MON: return 'Monday';
            case self::TUE: return 'Tuesday';
            case self::WED: return 'Wednesday';
            case self::THU: return 'Thursday';
            case self::FRI: return 'Friday';
            case self::SAT: return 'Saturday';
            case self::SUN: return 'Sunday';
        }
    }

    /**
     * getChoices()
     *
     * Used for FormTypes
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @param  $format  e.g. Mon or Monday or M
     * @param  $day_of_week_numbers
     *
     * @return array
     */
    public static function getChoices($format = 'ShortName', $day_of_week_numbers = array(1,2,3,4,5,6,7))
    {
        switch ($format)
        {
            case 'ShortName':
            case 'FirstLetter':
            case 'LongName':

                $method = 'get'.$format;
        }

        $choices = array();

        foreach ($day_of_week_numbers as $day_of_week_number)
        {
            $dayOfWeek = new self($day_of_week_number);

            $choices[$day_of_week_number] = $dayOfWeek->$method();
        }

        return $choices;
    }
}
