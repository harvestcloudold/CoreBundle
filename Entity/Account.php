<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\DoubleEntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use HarvestCloud\CoreBundle\Entity\Profile;

/**
 * Account Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-03
 *
 * @ORM\Entity
 * @Gedmo\Tree(type="nested")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *    "root"      = "RootAccount",
 *    "asset"     = "AssetAccount",
 *    "liability" = "LiabilityAccount",
 *    "income"    = "IncomeAccount",
 *    "expense"   = "ExpenseAccount",
 *    "equity"    = "EquityAccount"
 * })
 * @ORM\Table(name="double_entry_account")
 * @ORM\Entity(repositoryClass="HarvestCloud\DoubleEntryBundle\Repository\AccountRepository")
 */
abstract class Account
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Profile", inversedBy="accounts")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @ORM\OneToMany(targetEntity="Posting", mappedBy="account")
     */
    protected $postings;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $balance = 0;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=50, unique=false)
     */
    private $slug;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @param  string $name
     */
    public function __construct($name)
    {
        $this->setName($name);

        $this->postings = new ArrayCollection();
    }

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
     * Set profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @param  Profile $profile
     */
    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add posting
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @param  Posting $posting
     */
    public function addPosting(Posting $posting)
    {
        $this->postings[] = $posting;
    }

    /**
     * Get postings
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-03
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPostings()
    {
        return $this->postings;
    }

    /**
     * Set balance
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-04
     *
     * @param  decimal $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * Get balance
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-04
     *
     * @return decimal
     */
    public function getBalance()
    {
        return $this->balance;
    }


    /**
     * Get account name suffix
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-21
     *
     * @return string
     */
    public static function getAccountNameSuffix($type_code)
    {
        switch ($type_code)
        {
            case Account::TYPE_ACCOUNTS_RECEIVABLE: return ' A/R';
            case Account::TYPE_ACCOUNTS_PAYABLE:    return ' A/P';
            case Account::TYPE_SALES:               return ' Sales';
            case Account::TYPE_BANK:                return ' Bank';

            default:

                throw new \Exception('Incorrect type_code: '.$type_code);
        }
    }

    /**
     * Set lft
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @param integer $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * Get lft
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @param integer $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * Get lvl
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @param integer $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * Get rgt
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @param integer $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * Get root
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @param HarvestCloud\DoubleEntryBundle\Entity\Account $parent
     */
    public function setParent(\HarvestCloud\DoubleEntryBundle\Entity\Account $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return HarvestCloud\DoubleEntryBundle\Entity\Account
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @param HarvestCloud\DoubleEntryBundle\Entity\Account $children
     */
    public function addAccount(\HarvestCloud\DoubleEntryBundle\Entity\Account $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
        $child->setProfile($this->getProfile());
    }

    /**
     * Get children
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set slug
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-06
     *
     * @param string $slug
     *
     * @return Account
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-06
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * isAsset()
     *
     * Whether or not this Account is an Asset account
     *
     * We return false here and then return true in the relevant subclass
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @return boolean
     */
    public function isAsset()
    {
        return false;
    }

    /**
     * isLiability()
     *
     * Whether or not this Account is a Liability account
     *
     * We return false here and then return true in the relevant subclass
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @return boolean
     */
    public function isLiability()
    {
        return false;
    }

    /**
     * isExpense()
     *
     * Whether or not this Account is an Expense account
     *
     * We return false here and then return true in the relevant subclass
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @return boolean
     */
    public function isExpense()
    {
        return false;
    }

    /**
     * isIncome()
     *
     * Whether or not this Account is an Income account
     *
     * We return false here and then return true in the relevant subclass
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @return boolean
     */
    public function isIncome()
    {
        return false;
    }

    /**
     * isEquity()
     *
     * Whether or not this Account is an Equity account
     *
     * We return false here and then return true in the relevant subclass
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-21
     *
     * @return boolean
     */
    public function isEquity()
    {
        return false;
    }
}
