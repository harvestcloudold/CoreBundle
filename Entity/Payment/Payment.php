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
 * Payment Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-03-24
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *    "Invoice" = "InvoicePayment"
 * })
 * @ORM\Table(name="payment")
 */
abstract class Payment
{
    /**
     * Statuses
     *
     * @var int
     */
    const
        STATUS_POSTED   = 1
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="invoicesAsVendor")
     * @ORM\JoinColumn(name="vendor_id", referencedColumnName="id")
     */
    protected $vendor;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="invoicesAsCustomer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $amount = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status_code = self::STATUS_POSTED;

    /**
     * @ORM\OneToMany(targetEntity="PaymentLineItem", mappedBy="payment", cascade={"persist"})
     */
    protected $lineItems;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\DoubleEntryBundle\Entity\PaymentJournal", mappedBy="payment", cascade={"persist"})
     */
    protected $journals;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $posted_at;

    /**
     * Constructor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     */
    public function __construct()
    {
        $this->journals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Payment
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
     * Set status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param integer $statusCode
     *
     * @return Payment
     */
    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;

        return $this;
    }

    /**
     * Get status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Set posted_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \DateTime $postedAt
     *
     * @return Payment
     */
    public function setPostedAt($postedAt)
    {
        $this->posted_at = $postedAt;

        return $this;
    }

    /**
     * Get posted_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->posted_at;
    }

    /**
     * Set vendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $vendor
     *
     * @return Payment
     */
    public function setVendor(\HarvestCloud\CoreBundle\Entity\Profile $vendor = null)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set customer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $customer
     *
     * @return Payment
     */
    public function setCustomer(\HarvestCloud\CoreBundle\Entity\Profile $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add journals
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\DoubleEntryBundle\Entity\PaymentJournal $journal
     *
     * @return Payment
     */
    public function addJournal(\HarvestCloud\DoubleEntryBundle\Entity\PaymentJournal $journal)
    {
        $this->journals[] = $journal;

        return $this;
    }

    /**
     * Remove journal
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\DoubleEntryBundle\Entity\PaymentJournal $journal
     */
    public function removeJournal(\HarvestCloud\DoubleEntryBundle\Entity\PaymentJournal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Add lineItems
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Payment\PaymentLineItem $lineItems
     *
     * @return InvoicePayment
     */
    public function addLineItem(\HarvestCloud\CoreBundle\Entity\Payment\PaymentLineItem $lineItem)
    {
        $this->lineItems[] = $lineItem;

        $lineItem->setPayment($this);

        $this->recalculateAmount();

        return $this;
    }

    /**
     * Remove lineItems
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Payment\PaymentLineItem $lineItems
     */
    public function removeLineItem(\HarvestCloud\CoreBundle\Entity\Payment\PaymentLineItem $lineItem)
    {
        $this->lineItems->removeElement($lineItem);
    }

    /**
     * Get lineItems
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     */
    public function post()
    {
        $this->setPostedAt(new \DateTime());

        // Post each line
        foreach ($this->getLineItems() as $lineItem) {
            $lineItem->post();
        }
    }

    /**
     * recalculateAmount()
     *
     * Calculate based on sum of lines
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-24
     */
    public function recalculateAmount()
    {
        $amount = 0;

        foreach ($this->getLineItems() as $lineItem) {
            $amount += $lineItem->getAmount();
        }

        $this->setAmount($amount);
    }
}
