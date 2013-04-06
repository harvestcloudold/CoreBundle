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
 * SavedCreditCard Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-04-02
 *
 * @ORM\Entity
 * @ORM\Table(name="saved_credit_card")
 */
class SavedCreditCard
{
    /**
     * types
     *
     * @var string
     */
    const
        TYPE_VISA     = 'Visa',
        TYPE_MC       = 'MasterCard',
        TYPE_AMEX     = 'American Express',
        TYPE_DISCOVER = 'Discover',
        TYPE_DINERS   = 'Diner\'s Club',
        TYPE_JCB      = 'JCB'
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="savedCreditCards")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $payment_service_key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $payment_service_token;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expiry_date;

    /**
     * @ORM\Column(type="string", length=4)
     */
    protected $last_four_digits;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name_on_card;

    /**
     * __toString()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getType().' '.$this->getLastFourDigits().' '.$this->getExpiryDate()->format('m/y');
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set payment_service_key
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  string $paymentServiceKey
     *
     * @return SavedCreditCard
     */
    public function setPaymentServiceKey($paymentServiceKey)
    {
        $this->payment_service_key = $paymentServiceKey;

        return $this;
    }

    /**
     * Get payment_service_key
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return string
     */
    public function getPaymentServiceKey()
    {
        return $this->payment_service_key;
    }

    /**
     * Set payment_service_token
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  string $paymentServiceToken
     *
     * @return SavedCreditCard
     */
    public function setPaymentServiceToken($paymentServiceToken)
    {
        $this->payment_service_token = $paymentServiceToken;

        return $this;
    }

    /**
     * Get payment_service_token
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return string
     */
    public function getPaymentServiceToken()
    {
        return $this->payment_service_token;
    }

    /**
     * Set type
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  string $type
     *
     * @return SavedCreditCard
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set expiry_date
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  \DateTime $expiryDate
     *
     * @return SavedCreditCard
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiry_date = $expiryDate;

        return $this;
    }

    /**
     * Get expiry_date
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiry_date;
    }

    /**
     * setExpiryDateFromYearAndMonth()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  string  $year
     * @param  string  $month
     *
     * @return SavedCreditCard
     */
    public function setExpiryDateFromYearAndMonth($year, $month)
    {
        if (!in_array((string) $month, array(
            '01', '02', '03', '04',
            '05', '06', '07', '08',
            '09', '10', '11', '12',
        ))) {
            throw new \InvalidArgumentException('Invalid month '.$month);
        }

        $day = date('t', strtotime($year.'-'.$month.'-01'));

        $this->setExpiryDate(new \DateTime($year.'-'.$month.'-'.$day));

        return $this;
    }

    /**
     * Set last_four_digits
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  string $lastFourDigits
     *
     * @return SavedCreditCard
     */
    public function setLastFourDigits($lastFourDigits)
    {
        $this->last_four_digits = $lastFourDigits;

        return $this;
    }

    /**
     * Get last_four_digits
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return string
     */
    public function getLastFourDigits()
    {
        return $this->last_four_digits;
    }

    /**
     * Set name_on_card
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  string $nameOnCard
     *
     * @return SavedCreditCard
     */
    public function setNameOnCard($nameOnCard)
    {
        $this->name_on_card = $nameOnCard;

        return $this;
    }

    /**
     * Get name_on_card
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return string
     */
    public function getNameOnCard()
    {
        return $this->name_on_card;
    }

    /**
     * Set profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $profile
     *
     * @return SavedCreditCard
     */
    public function setProfile(\HarvestCloud\CoreBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-04-02
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
