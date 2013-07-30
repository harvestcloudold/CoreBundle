<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquityAccount Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-21
 *
 * @ORM\Entity
 */
class EquityAccount extends Account
{
    /**
     * isEquity()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     * @see    Account::isEquity()
     */
    public function isEquity()
    {
        return true;
    }
}
