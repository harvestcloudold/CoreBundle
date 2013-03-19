<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Tests\Util;

use HarvestCloud\CoreBundle\Util\DayOfWeek;

/**
 * Tests for DayOfWeek
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2013-03-18
 */
class DayOfWeekTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConstructorException()
     *
     * @expectedException InvalidArgumentException
     *
     * @author            Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since             2013-03-18
     */
    public function testConstructorException()
    {
        $dayOfWeek = new DayOfWeek(8);
    }

    /**
     * testDayOfWeekNumberConstants()
     *
     * @dataProvider dayOfWeekNumberProvider
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-18
     */
    public function testDayOfWeekNumberConstants($day_of_week_number, $constant_number)
    {
        $this->assertEquals($day_of_week_number, $constant_number);
    }

    /**
     * testGetFirstLetter()
     *
     * @dataProvider dayOfWeekFirstLetterProvider
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-18
     */
    public function testGetFirstLetter($day_of_week_number, $first_letter)
    {
        $dayOfWeek = new DayOfWeek($day_of_week_number);

        $this->assertEquals($first_letter, $dayOfWeek->getFirstLetter());
    }

    /**
     * dayOfWeekNumberProvider()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-18
     *
     * @return array
     */
    public function dayOfWeekNumberProvider()
    {
        return array(
            array(DayOfWeek::MON, 1),
            array(DayOfWeek::TUE, 2),
            array(DayOfWeek::WED, 3),
            array(DayOfWeek::THU, 4),
            array(DayOfWeek::FRI, 5),
            array(DayOfWeek::SAT, 6),
            array(DayOfWeek::SUN, 7),
        );
    }

    /**
     * dayOfWeekFirstLetterProvider()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-18
     *
     * @return array
     */
    public function dayOfWeekFirstLetterProvider()
    {
        return array(
            array(DayOfWeek::MON, 'M'),
            array(DayOfWeek::TUE, 'T'),
            array(DayOfWeek::WED, 'W'),
            array(DayOfWeek::THU, 'T'),
            array(DayOfWeek::FRI, 'F'),
            array(DayOfWeek::SAT, 'S'),
            array(DayOfWeek::SUN, 'S'),
        );
    }
}
