<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use HarvestCloud\CoreBundle\Entity\Invoice\BaseOrderInvoice;
use HarvestCloud\DoubleEntryBundle\Entity\InvoiceJournal;
use HarvestCloud\CoreBundle\Entity\Order;
use HarvestCloud\CoreBundle\Entity\Exchange;

/**
 * PostingFeeInvoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 */
class PostingFeeInvoice extends BaseOrderInvoice
{
    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Order", mappedBy="postingFeeInvoice")
     */
    protected $order;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order    $order
     * @param  \HarvestCloud\CoreBundle\Entity\Exchange $exchange
     */
    public function __construct(Order $order, Exchange $exchange)
    {
        $this->setOrder($order);
        $this->setVendor($exchange->getProfile());
        $this->setCustomer($order->getSeller());
        $this->setAmount($order->getPostingFee());
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     */
    public function post()
    {
        // Create Vendor Journal
        $vendorJournal = new InvoiceJournal($this);
        $vendorJournal->debit($this->getVendor()->getAccountsReceivableAccount(), $this->getAmount());
        $vendorJournal->credit($this->getVendor()->getSalesAccount(), $this->getAmount());

        // Add Vendor Journal to Invoice
        $this->addJournal($vendorJournal);

        // Create Customer Journal
        $customerJournal = new InvoiceJournal($this);
        $customerJournal->debit($this->getCustomer()->getCostOfGoodsSoldAccount(), $this->getAmount());
        $customerJournal->credit($this->getCustomer()->getAccountsPayableAccount(), $this->getAmount());

        // Add Customer Journal to Invoice
        $this->addJournal($customerJournal);

        parent::post();
    }
}
