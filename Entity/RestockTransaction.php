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
 * RestockTransaction Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-01-21
 *
 * @ORM\Entity
 */
class RestockTransaction extends BaseStockTransaction
{
    /**
     * Get type label
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-21
     *
     * @see    BaseStockTransaction::getTypeLabel()
     */
    public function getTypeLabel()
    {
        return 'Restock';
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-21
     */
    public function post()
    {
        $this->getProduct()->adjustQuantity($this->getQuantity());
    }
}
