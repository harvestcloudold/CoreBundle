<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\DoubleEntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Posting Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-03
 *
 * @ORM\Entity
 * @ORM\Table(name="double_entry_posting")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="HarvestCloud\DoubleEntryBundle\Repository\PostingRepository")
 */
class Posting
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="postings", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\ManyToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Profile", inversedBy="postings")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\DoubleEntryBundle\Entity\Journal", inversedBy="postings")
     * @ORM\JoinColumn(name="journal_id", referencedColumnName="id")
     */
    protected $journal;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $amount;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $balance_afterwards;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $posted_at;

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
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
     * @since  2012-05-03
     *
     * @param  decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @return decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @param  Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Get account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set journal
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @param  \HarvestCloud\DoubleEntryBundle\Entity\Journal $journal
     */
    public function setJournal(\HarvestCloud\DoubleEntryBundle\Entity\Journal $journal)
    {
        $this->journal = $journal;
    }

    /**
     * Get journal
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set posted_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \DateTime $postedAt
     *
     * @return Invoice
     */
    public function setPostedAt(\DateTime $postedAt)
    {
        $this->posted_at = $postedAt;

        return $this;
    }

    /**
     * Get posted_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->posted_at;
    }


    /**
     * Update Account balance
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-21
     */
    public function updateAccountBalance()
    {
        $this->getAccount()->setBalance($this->getAccount()->getBalance()+$this->getAmount());
        $this->setBalanceAfterwards($this->getAccount()->getBalance());
    }

    /**
     * getOffsetAccount()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-06
     *
     * @return Account
     */
    public function getOffsetAccount()
    {
        foreach ($this->getJournal()->getPostings() as $posting)
        {
            if ($posting->getAccount()->getId() != $this->getAccount()->getId())
            {
                return $posting->getAccount();
            }
        }
    }

    /**
     * Set balance_afterwards
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @param  float $balanceAfterwards
     *
     * @return Posting
     */
    public function setBalanceAfterwards($balanceAfterwards)
    {
        $this->balance_afterwards = $balanceAfterwards;

        return $this;
    }

    /**
     * Get balance_afterwards
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @return float
     */
    public function getBalanceAfterwards()
    {
        return $this->balance_afterwards;
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     */
    public function post()
    {
        $this->setPostedAt(new \DateTime());
        $this->updateAccountBalance();
    }

    /**
     * Set profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $profile
     *
     * @return Posting
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
     * @since  2013-03-27
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
