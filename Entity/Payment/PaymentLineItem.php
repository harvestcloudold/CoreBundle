<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity\Payment;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentLineItem Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-24
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *    "invoice" = "InvoicePaymentLineItem"
 * })
 * @ORM\Table(name="payment_line_item")
 */
abstract class PaymentLineItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Payment", inversedBy="lineItems", cascade={"persist"})
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     */
    protected $payment;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $amount = 0;

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
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  float $amount
     *
     * @return PaymentLineItem
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set payment
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Payment\Payment $payment
     *
     * @return PaymentLineItem
     */
    public function setPayment(\HarvestCloud\CoreBundle\Entity\Payment\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \HarvestCloud\CoreBundle\Entity\Payment\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     */
    public function post()
    {
    }
}
