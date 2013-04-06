<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity\Payment;

use Doctrine\ORM\Mapping as ORM;
use HarvestCloud\CoreBundle\Entity\Payment\Payment;
use HarvestCloud\CoreBundle\Entity\Payment\InvoicePaymentLineItem;

/**
 * InvoicePayment Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-24
 *
 * @ORM\Entity
 */
class InvoicePayment extends Payment
{
    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @array  $invoices
     */
    public function __construct(array $invoices)
    {
        if (!count($invoices)) {
            throw new \InvalidArgumentException('You must pass at least one invoice');
        }

        foreach ($invoices as $invoice) {
            $this->addLineItem(new InvoicePaymentLineItem($invoice));
        }

        $this->setVendor($invoices[0]->getVendor());
        $this->setCustomer($invoices[0]->getCustomer());
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     */
    public function post()
    {
        // Add Journal Postings
        parent::post();
    }
}
