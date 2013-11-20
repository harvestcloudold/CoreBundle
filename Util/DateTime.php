<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Util;

/**
 * A Harvest Cloud implemtation of \DateTime
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2013-11-08
 */
class DateTime extends \DateTime
{
    /**
     * getForStartOfWeek()
     *
     * @author Tom Hasins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-19
     *
     * @return DateTime
     */
    public static function getForStartOfWeek()
    {
        return new \DateTime(date('Y-m-d', strtotime('last monday')).' 00:00:00');
    }

    /**
     * getForEndOfWeek()
     *
     * @author Tom Hasins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-08
     *
     * @return DateTime
     */
    public static function getForEndOfWeek()
    {
        return new \DateTime(date('Y-m-d', strtotime('Sunday')).' 23:59:59');
    }
}
