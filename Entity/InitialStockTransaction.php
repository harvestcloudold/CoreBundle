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
 * InitialStockTransaction Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-11
 *
 * @ORM\Entity
 */
class InitialStockTransaction extends BaseStockTransaction
{
    /**
     * Get type label
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-12
     *
     * @see    BaseStockTransaction::getTypeLabel()
     */
    public function getTypeLabel()
    {
        return 'Initial quantity';
    }
}
