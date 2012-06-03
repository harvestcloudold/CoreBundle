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
 * OrderStockTransaction Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-11
 *
 * @ORM\Entity
 */
class OrderStockTransaction extends BaseStockTransaction
{
    /**
     * @ORM\OneToOne(targetEntity="OrderLineItem", mappedBy="stockTransaction")
     */
    protected $lineItem;

    /**
     * Set lineItem
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param HarvestCloud\CoreBundle\Entity\OrderLineItem $lineItem
     */
    public function setLineItem(\HarvestCloud\CoreBundle\Entity\OrderLineItem $lineItem)
    {
        $this->lineItem = $lineItem;
        $lineItem->setStockTransaction($this);
    }

    /**
     * Get lineItem
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return HarvestCloud\CoreBundle\Entity\OrderLineItem
     */
    public function getLineItem()
    {
        return $this->lineItem;
    }

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
        return 'Order Item';
    }
}
