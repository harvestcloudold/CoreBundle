<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity\Payment;

use Doctrine\ORM\Mapping as ORM;
use HarvestCloud\CoreBundle\Entity\Invoice\Invoice;

/**
 * InvoicePaymentLineItem Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-24
 *
 * @ORM\Entity
 */
class InvoicePaymentLineItem extends PaymentLineItem
{
    /**
     * @ORM\ManyToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Invoice\Invoice", inversedBy="invoicePaymentLineItems", cascade={"persist"})
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    protected $invoice;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\Invoice\Entity\Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->setInvoice($invoice);
        $this->setAmount($invoice->getAmountDue());
    }

    /**
     * Set invoice
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoice
     *
     * @return InvoicePaymentLineItem
     */
    public function setInvoice(\HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \HarvestCloud\CoreBundle\Entity\Invoice\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     */
    public function post()
    {
        $this->getInvoice()->setAmountDue(
            $this->getInvoice()->getAmountDue() - $this->getAmount()
        );

        parent::post();
    }
}
