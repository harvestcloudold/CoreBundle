<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Util;

/**
 * An interface for classes that represent a time window
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2012-04-28
 */
interface Windowable
{
    /**
     * getStartTime
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com
     * @since  2012-04-28
     *
     * @return datetime
     */
    public function getStartTime();

    /**
     * getEndTime
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com
     * @since  2012-04-28
     *
     * @return datetime
     */
    public function getEndTime();
}
