<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\DoubleEntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpenseAccount Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-21
 *
 * @ORM\Entity
 */
class ExpenseAccount extends Account
{
    /**
     * isExpense()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     * @see    Account::isExpense()
     */
    public function isExpense()
    {
        return true;
    }
}
