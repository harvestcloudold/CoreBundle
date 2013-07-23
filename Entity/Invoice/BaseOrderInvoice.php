<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseOrderInvoice Entity
 *
 * Serves as base class for all Invoices that have to do with an Order
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-20
 *
 * @ORM\Entity
 */
abstract class BaseOrderInvoice extends Invoice
{
    /**
     * Set order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $order
     *
     * @return OrderInvoice
     */
    public function setOrder(\HarvestCloud\CoreBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @return \HarvestCloud\CoreBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}
