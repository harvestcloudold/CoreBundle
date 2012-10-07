<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Util;


/**
 * Some debug tools
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-10-05
 */
class Debug
{
    /**
     * show()
     *
     * Display variable and optionally exit
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-05
     *
     * @param  mixed  $v
     * @param  bool   $exit
     */
    public static function show($v, $exit = true)
    {
        echo "<pre>";
        print_r($v);

        if ($exit) exit;
    }
}
