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
 * PaymentJournal
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-02-04
 *
 * @ORM\Entity
 */
class PaymentJournal extends Journal
{
    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Payment\Payment", inversedBy="journals")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     */
    protected $payment;

    /**
     * Get description
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Payment';
    }

    /**
     * Set payment
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-25
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Payment\Payment $payment
     *
     * @return PaymentJournal
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
     * @since  2013-03-25
     *
     * @return \HarvestCloud\CoreBundle\Entity\Payment\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
