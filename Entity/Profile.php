<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use HarvestCloud\GeoBundle\Util\Geolocatable;
use HarvestCloud\CoreBundle\Entity\Account;

/**
 * Profile Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-05
 *
 * @ORM\Entity
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\ProfileRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Profile implements Geolocatable
{
    /**
     * status options
     *
     * @var string
     */
    const
        STATUS_ENABLED   = 'enabled',
        STATUS_DISABLED  = 'disabled',
        STATUS_ACTIVE    = 'active',
        STATUS_INACTIVE  = 'inactive',
        STATUS_SUSPENDED = 'suspended'
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true, length=50)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="\HarvestCloud\UserBundle\Entity\User", mappedBy="profiles")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\UserBundle\Entity\User", mappedBy="defaultProfile")
     */
    protected $usersAsDefaultProfile;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\UserBundle\Entity\User", mappedBy="currentProfile")
     */
    protected $usersAsCurrentProfile;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="seller", cascade={"persist"})
     */
    protected $productsAsSeller;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="profile", cascade={"persist"})
     */
    protected $locations;

    /**
     * @ORM\OneToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="default_location_id", referencedColumnName="id")
     */
    private $defaultLocation;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="root_account_id", referencedColumnName="id")
     */
    private $rootAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="cost_of_goods_sold_account_id", referencedColumnName="id")
     */
    private $costOfGoodsSoldAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="sales_account_id", referencedColumnName="id")
     */
    private $salesAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="expense_account_id", referencedColumnName="id")
     */
    private $expenseAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="purchases_account_id", referencedColumnName="id")
     */
    private $purchasesAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="ar_account_id", referencedColumnName="id")
     */
    private $accountsReceivableAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="ap_account_id", referencedColumnName="id")
     */
    private $accountsPayableAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="paypal_account_id", referencedColumnName="id")
     */
    private $payPalAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="ar_prepay_account_id", referencedColumnName="id")
     */
    private $arPrePaymentAccount;

    /**
     * @ORM\OneToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(name="ap_prepay_account_id", referencedColumnName="id")
     */
    private $apPrePaymentAccount;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $system_buyer_status = self::STATUS_ENABLED;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $buyer_status = self::STATUS_INACTIVE;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $system_seller_status = self::STATUS_DISABLED;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $seller_status = self::STATUS_INACTIVE;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $system_hub_status = self::STATUS_DISABLED;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $hub_status = self::STATUS_INACTIVE;

    /**
     * @ORM\OneToMany(targetEntity="BuyerHubRef", mappedBy="buyer")
     */
    protected $buyerHubRefsAsBuyer;

    /**
     * @ORM\OneToMany(targetEntity="BuyerHubRef", mappedBy="hub")
    */
    protected $buyerHubRefsAsHub;

    /**
     * @ORM\OneToMany(targetEntity="SellerHubRef", mappedBy="seller")
     */
    protected $sellerHubRefsAsSeller;

    /**
     * @ORM\OneToMany(targetEntity="SellerHubRef", mappedBy="hub")
     */
    protected $sellerHubRefsAsHub;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $pay_pal_account_email;

    /**
     * @ORM\OneToMany(targetEntity="OrderCollection", mappedBy="buyer")
     */
    protected $orderCollectionsAsBuyer;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="buyer")
     */
    protected $ordersAsBuyer;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="seller")
     */
    protected $ordersAsSeller;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="hub")
     */
    protected $ordersAsHub;

    /**
     * @ORM\OneToMany(targetEntity="HarvestCloud\CoreBundle\Entity\Account", mappedBy="profile")
     */
    protected $accounts;

    /**
     * @ORM\OneToMany(targetEntity="HarvestCloud\CoreBundle\Entity\Journal", mappedBy="profile")
     */
    protected $journals;

    /**
     * @ORM\OneToMany(targetEntity="HarvestCloud\CoreBundle\Entity\Posting", mappedBy="profile")
     */
    protected $postings;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $stripe_user_id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $stripe_access_token;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $stripe_publishable_key;

    /**
     * The fixed part (in dollars) of the fee that a Seller is charged by the
     * system for each Order that is completed
     *
     *   e.g. $0.10 per order (see caculation below)
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $fixed_order_fee = 0.25;

    /**
     * The variable part (%) of the fee that a Seller is charged by the system
     * for each Order that is completed
     *
     *   e.g. Order with total of $20
     *
     *     Fixed fee @ $0.40      $0.40
     *     Variable @ 8.00%       $1.60
     *                            -----
     *     Total fee              $2.00
     *
     * @ORM\Column(type="decimal", scale=3)
     */
    protected $variable_order_fee = 5;

    /**
     * Small-scale/home-based Sellers may not want their home to appear on maps
     *
     * @ORM\Column(type="boolean")
     */
    protected $as_seller_display_on_map = true;

    /**
     * @ORM\OneToMany(targetEntity="HubWindowMaker", mappedBy="hub", cascade={"persist"})
     */
    protected $hubWindowMakers;

    /**
     * @ORM\OneToMany(targetEntity="HubWindow", mappedBy="hub", cascade={"persist"})
     */
    protected $hubWindows;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\CoreBundle\Entity\Invoice\Invoice", mappedBy="vendor")
     */
    protected $invoicesAsVendor;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\CoreBundle\Entity\Invoice\Invoice", mappedBy="customer")
     */
    protected $invoicesAsCustomer;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard", mappedBy="profile", cascade={"persist"})
     */
    protected $savedCreditCards;

    /**
     * @ORM\OneToMany(targetEntity="ProductSubscription", mappedBy="buyer")
     */
    protected $productSubscriptionsAsBuyer;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     */
    public function __construct()
    {
        $this->usersAsDefaultProfile       = new ArrayCollection();
        $this->users                       = new ArrayCollection();
        $this->locations                   = new ArrayCollection();
        $this->buyerHubRefsAsBuyer         = new ArrayCollection();
        $this->buyerHubRefsAsHub           = new ArrayCollection();
        $this->sellerHubRefsAsSeller       = new ArrayCollection();
        $this->sellerHubRefsAsHub          = new ArrayCollection();
        $this->ordersAsBuyer               = new ArrayCollection();
        $this->ordersAsSeller              = new ArrayCollection();
        $this->ordersAsHub                 = new ArrayCollection();
        $this->orderCollectionsAsBuyer     = new ArrayCollection();
        $this->productsAsSeller            = new ArrayCollection();
        $this->accounts                    = new ArrayCollection();
        $this->hubWindows                  = new ArrayCollection();
        $this->hubFeeInvoicesAsHub         = new ArrayCollection();
        $this->hubFeeInvoicesAsSeller      = new ArrayCollection();
        $this->productSubscriptionsAsBuyer = new ArrayCollection();
    }

    /**
     * __toString()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @return string
     */
     public function __toString()
     {
        return $this->getName();
     }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-05
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-27
     *
     * @param  string $slug
     *
     * @return Profile
     */
    public function setSlug($slug)
    {
        if (in_array($slug, array(
            'buy',
            'buyer',
            'sell',
            'seller',
            'hub',
            'profile',
        )))
        {
            throw new \Exception('Protected keyword');
        }

        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-09-27
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-05
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
     * @since  2012-04-05
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add usersAsDefaultProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param HarvestCloud\UserBundle\Entity\User $usersAsDefaultProfile
     */
    public function addUserAsDefaultProfile(\HarvestCloud\UserBundle\Entity\User $usersAsDefaultProfile)
    {
        $this->usersAsDefaultProfile[] = $usersAsDefaultProfile;
    }

    /**
     * Get usersAsDefaultProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getUsersAsDefaultProfile()
    {
        return $this->usersAsDefaultProfile;
    }

    /**
     * Get users
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add users
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param HarvestCloud\UserBundle\Entity\User $users
     */
    public function addUser(\HarvestCloud\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    }

    /**
     * Add location
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-20
     *
     * @param HarvestCloud\CoreBundle\Entity\Location $location
     */
    public function addLocation(Location $location)
    {
        // If this is the first Location, we can assume it can be the default
        if (!count($this->locations))
        {
            $this->setDefaultLocation($location);
        }

        $this->locations[] = $location;
        $location->setProfile($this);
    }

    /**
     * Get locations
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-20
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Set defaultLocation
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-20
     *
     * @param HarvestCloud\CoreBundle\Entity\Location $defaultLocation
     */
    public function setDefaultLocation(Location $defaultLocation)
    {
        $this->defaultLocation = $defaultLocation;
        $this->setLatitude($defaultLocation->getLatitude());
        $this->setLongitude($defaultLocation->getLongitude());
    }

    /**
     * Get defaultLocation
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-20
     *
     * @return HarvestCloud\CoreBundle\Entity\Location
     */
    public function getDefaultLocation()
    {
        return $this->defaultLocation;
    }

    /**
     * getMapLabel
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @return string
     */
    public function getMapLabel()
    {
        return $this->name;
    }

    /**
     * Set latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @param decimal $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @return decimal
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @param decimal $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-22
     *
     * @return decimal
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Add Product as Seller
     *
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->productsAsSeller[] = $product;
    }

    /**
     * Set system_buyer_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @param string $systemBuyerStatus
     */
    public function setSystemBuyerStatus($systemBuyerStatus)
    {
        $this->system_buyer_status = $systemBuyerStatus;
    }

    /**
     * Get system_buyer_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @return string 
     */
    public function getSystemBuyerStatus()
    {
        return $this->system_buyer_status;
    }

    /**
     * Set buyer_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @param string $buyerStatus
     */
    public function setBuyerStatus($buyerStatus)
    {
        $this->buyer_status = $buyerStatus;
    }

    /**
     * Get buyer_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @return string 
     */
    public function getBuyerStatus()
    {
        return $this->buyer_status;
    }

    /**
     * Get usersAsCurrentProfile
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-24
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsersAsCurrentProfile()
    {
        return $this->usersAsCurrentProfile;
    }

    /**
     * Set system_hub_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @param string $systemHubStatus
     */
    public function setSystemHubStatus($systemHubStatus)
    {
        $this->system_hub_status = $systemHubStatus;
    }

    /**
     * Get system_hub_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @return string
     */
    public function getSystemHubStatus()
    {
        return $this->system_hub_status;
    }

    /**
     * Set hub_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @param string $hubStatus
     */
    public function setHubStatus($hubStatus)
    {
        $this->hub_status = $hubStatus;
    }

    /**
     * Get hub_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-25
     *
     * @return string
     */
    public function getHubStatus()
    {
        return $this->hub_status;
    }

    /**
     * Autogenerated incorrectly
     */
    public function addBuyerHubRef(BuyerHubRef $buyerHubRef)
    {
        throw new Exception('Not implemented');
    }

    /**
     * Add BuyerHubRef as Buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param BuyerHubRef $buyerHubRef
     */
    public function addBuyerHubRefAsBuyer(BuyerHubRef $buyerHubRef)
    {
        if (!count($this->buyerHubRefsAsBuyer))
        {
            $buyerHubRef->setIsDefault(true);
        }

        $this->buyerHubRefsAsBuyer[] = $buyerHubRef;
        $buyerHubRef->setBuyer($this);
    }

    /**
     * Add BuyerHubRef as Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param BuyerHubRef $buyerHubRef
     */
    public function addBuyerHubRefAsHub(BuyerHubRef $buyerHubRef)
    {
        $this->buyerHubRefsAsHub[] = $buyerHubRef;
        $buyerHubRef->setHub($this);
    }

    /**
     * Get buyerHubRefsAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBuyerHubRefsAsBuyer()
    {
        return $this->buyerHubRefsAsBuyer;
    }

    /**
     * Get buyerHubRefsAsHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBuyerHubRefsAsHub()
    {
        return $this->buyerHubRefsAsHub;
    }

    /**
     * Autogenerated incorrectly
     */
    public function addSellerHubRef(SellerHubRef $sellerHubRef)
    {
        throw new Exception('Not implemented');
    }

    /**
     * Add SellerHubRef as Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param SellerHubRef $sellerHubRef
     */
    public function addSellerHubRefAsSeller(SellerHubRef $sellerHubRef)
    {
        if (!count($this->sellerHubRefsAsSeller))
        {
            $sellerHubRef->setIsDefault(true);
        }

        $this->sellerHubRefsAsSeller[] = $sellerHubRef;
        $sellerHubRef->setSeller($this);
    }

    /**
     * Add SellerHubRef as Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @param SellerHubRef $sellerHubRef
     */
    public function addSellerHubRefAsHub(SellerHubRef $sellerHubRef)
    {
        $this->sellerHubRefsAsHub[] = $sellerHubRef;
        $sellerHubRef->setHub($this);
    }

    /**
     * Get sellerHubRefsAsSeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSellerHubRefsAsSeller()
    {
        return $this->sellerHubRefsAsSeller;
    }

    /**
     * Get sellerHubRefsAsHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-26
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSellerHubRefsAsHub()
    {
        return $this->sellerHubRefsAsHub;
    }

    /**
     * Set system_seller_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @param string $systemSellerStatus
     */
    public function setSystemSellerStatus($systemSellerStatus)
    {
        $this->system_seller_status = $systemSellerStatus;
    }

    /**
     * Get system_seller_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @return string
     */
    public function getSystemSellerStatus()
    {
        return $this->system_seller_status;
    }

    /**
     * Set seller_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @param string $sellerStatus
     */
    public function setSellerStatus($sellerStatus)
    {
        $this->seller_status = $sellerStatus;
    }

    /**
     * Get seller_status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @return string
     */
    public function getSellerStatus()
    {
        return $this->seller_status;
    }

    /**
     * Set pay_pal_account_email
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @param string $payPalAccountEmail
     */
    public function setPayPalAccountEmail($payPalAccountEmail)
    {
        $this->pay_pal_account_email = $payPalAccountEmail;
    }

    /**
     * Get pay_pal_account_email
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @return string
     */
    public function getPayPalAccountEmail()
    {
        return $this->pay_pal_account_email;
    }

    /**
     * Autogenerated incorrectly
     */
    public function addOrder(\HarvestCloud\CoreBundle\Entity\Order $ordersAsSeller)
    {
        throw new Exception('Not implemented');
    }

    /**
     * Add ordersAsSeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-30
     *
     * @param  Order $order
     */
    public function addOrderAsSeller(Order $order)
    {
        $this->ordersAsSeller[] = $order;
    }

    /**
     * Get ordersAsSeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-30
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOrdersAsSeller()
    {
        return $this->ordersAsSeller;
    }

    /**
     * Autogenerated incorrectly
     */
    public function addOrderCollection(\HarvestCloud\CoreBundle\Entity\OrderCollection $orderCollectionsAsBuyer)
    {
        throw new Exception('Not implemented');
    }

    /**
     * Add orderCollectionsAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  OrderCollection $orderCollection
     */
    public function addOrderCollectionAsBuyer(OrderCollection $orderCollection)
    {
        $this->orderCollectionsAsBuyer[] = $orderCollection;
    }

    /**
     * Get orderCollectionsAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOrderCollectionsAsBuyer()
    {
        return $this->orderCollectionsAsBuyer;
    }

    /**
     * Get ordersAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOrdersAsBuyer()
    {
        return $this->ordersAsBuyer;
    }

    /**
     * Get ordersAsHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrdersAsHub()
    {
        return $this->ordersAsHub;
    }

    /**
     * Get productsAsSeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProductsAsSeller()
    {
        return $this->productsAsSeller;
    }

    /**
     * Add account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-04
     *
     * @param  Account $account
     */
    public function addAccount(Account $account)
    {
        $this->accounts[] = $account;
    }

    /**
     * Get accounts
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-04
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set fixed_order_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @param decimal $fixedOrderFee
     */
    public function setFixedOrderFee($fixedOrderFee)
    {
        $this->fixed_order_fee = $fixedOrderFee;
    }

    /**
     * Get fixed_order_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @return decimal
     */
    public function getFixedOrderFee()
    {
        return $this->fixed_order_fee;
    }

    /**
     * Set variable_order_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @param decimal $variableOrderFee
     */
    public function setVariableOrderFee($variableOrderFee)
    {
        $this->variable_order_fee = $variableOrderFee;
    }

    /**
     * Get variable_order_fee
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-20
     *
     * @return decimal
     */
    public function getVariableOrderFee()
    {
        return $this->variable_order_fee;
    }


    /**
     * Get Profile's account by code
     *
     *   e.g. AP => Accounts Payable
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-21
     *
     * @param  string  $type_code
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getAccountByCode($type_code)
    {
        switch ($type_code)
        {
            case Account::TYPE_ACCOUNTS_RECEIVABLE:
            case Account::TYPE_ACCOUNTS_PAYABLE:
            case Account::TYPE_SALES:
            case Account::TYPE_BANK:

                foreach ($this->getAccounts() as $account)
                {
                    if ($account->getTypeCode() == $type_code)
                    {
                        return $account;
                    }
                }

                // We couldn't find an account, so let's create one
                $account = new Account();
                $account->setBalance(0);
                $account->setProfile($this);
                $account->setTypeCode($type_code);
                $account->setName($this->getName().Account::getAccountNameSuffix($type_code));

                return $account;


            default:

                throw new \Exception('Invalid account code: '.$account_code);
        }
    }


    /**
     * Get A/P Account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-21
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getAPAccount()
    {
        return $this->getAccountByCode(Account::TYPE_ACCOUNTS_PAYABLE);
    }


    /**
     * Get Bank Account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-21
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getBankAccount()
    {
        return $this->getAccountByCode(Account::TYPE_BANK);
    }

    /**
     * Set as_seller_display_on_map
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-23
     *
     * @param boolean $asSellerDisplayOnMap
     */
    public function setAsSellerDisplayOnMap($asSellerDisplayOnMap)
    {
        $this->as_seller_display_on_map = $asSellerDisplayOnMap;
    }

    /**
     * Get as_seller_display_on_map
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-23
     *
     * @return boolean
     */
    public function getAsSellerDisplayOnMap()
    {
        return $this->as_seller_display_on_map;
    }

    /**
     * Proxy to getAsSellerDisplayOnMap()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-23
     *
     * @return boolean
     */
    public function asSellerDisplayOnMap()
    {
        return $this->getAsSellerDisplayOnMap();
    }

    /**
     * hasActiveSellerStatus()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-17
     *
     * @return bool
     */
    public function hasActiveSellerStatus()
    {
        if (Profile::STATUS_ACTIVE == $this->getSellerStatus())
        {
            return true;
        }

        return false;
    }

    /**
     * hasActiveHubStatus()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-17
     *
     * @return bool
     */
    public function hasActiveHubStatus()
    {
        if (Profile::STATUS_ACTIVE == $this->getHubStatus())
        {
            return true;
        }

        return false;
    }

    /**
     * Add hubWindows
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @param HarvestCloud\CoreBundle\Entity\HubWindow $hubWindow
     */
    public function addHubWindow(\HarvestCloud\CoreBundle\Entity\HubWindow $hubWindow)
    {
        $this->hubWindows[] = $hubWindow;

        $hubWindow->setHub($this);
    }

    /**
     * Get hubWindows
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-24
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getHubWindows()
    {
        return $this->hubWindows;
    }

    /**
     * Add hubWindowMaker
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @param HarvestCloud\CoreBundle\Entity\HubWindowMaker $hubWindowMaker
     */
    public function addHubWindowMaker(\HarvestCloud\CoreBundle\Entity\HubWindowMaker $hubWindowMaker)
    {
        $this->hubWindowMakers[] = $hubWindowMaker;

        $hubWindowMaker->setHub($this);
    }

    /**
     * Get hubWindowMakers
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-25
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getHubWindowMakers()
    {
        return $this->hubWindowMakers;
    }

    /**
     * getHubWindowAtThisTime()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @param  \DateTime  $startTime
     * @param  string     $delivery_type see HubWindow::DELIVERY_TYPE_*
     *
     * @return mixed      HubWindow or false
     */
    public function getHubWindowAtThisTime(\DateTime $startTime, $delivery_type)
    {
        $windows = $this->getHubWindowsIndexedByStartTimeAndDeliveryType();

        if (!empty($windows[$startTime->format(\DateTime::ATOM)][$delivery_type]))
        {
            return $windows[$startTime->format(\DateTime::ATOM)][$delivery_type];
        }

        return false;
    }

    /**
     * getHubWindowsIndexedByStartTimeAndDeliveryType()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-27
     *
     * @return array
     */
    public function getHubWindowsIndexedByStartTimeAndDeliveryType()
    {
        $windows = array();

        foreach ($this->getHubWindows() as $window)
        {
            $start_time    = $window->getStartTime()->format(\DateTime::ATOM);
            $delivery_type = $window->getDeliveryType();

            $windows[$start_time][$delivery_type] = $window;
        }

        return $windows;
    }

    /**
     * getAvatarName()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-04
     *
     * @return string
     */
    public function getAvatarName()
    {
        return (string) $this->getId()%16;
    }

    /**
     * getProductLocations()
     *
     * Locations that this Seller can use as Product Locations
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-04
     *
     * @return array
     */
    public function getProductLocations()
    {
        return $this->getLocations();
    }

    /**
     * createSetOfAccounts()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-27
     *
     * @return Account
     */
    public function createSetOfAccounts()
    {
        if ($this->getRootAccount())
        {
            throw new \Exception('Profile already has a set of accounts');
        }

        $account = new \HarvestCloud\CoreBundle\Entity\RootAccount('Root Account for '.$this->getName());
        $account->setProfile($this);

        $this->setRootAccount($account);

        // Assets
        $asset    = new \HarvestCloud\CoreBundle\Entity\AssetAccount('Assets');
        $ar       = new \HarvestCloud\CoreBundle\Entity\AssetAccount('Accounts Receivable');
        $current  = new \HarvestCloud\CoreBundle\Entity\AssetAccount('Current Assets');
        $arPrePay = new \HarvestCloud\CoreBundle\Entity\AssetAccount('AR Pre-Payments');
        $cash     = new \HarvestCloud\CoreBundle\Entity\AssetAccount('Cash');
        $bank     = new \HarvestCloud\CoreBundle\Entity\AssetAccount('Bank');
        $payPal   = new \HarvestCloud\CoreBundle\Entity\AssetAccount('PayPal');

        $this->setAccountsReceivableAccount($ar);
        $this->setPayPalAccount($payPal);
        $this->setArPrePaymentAccount($arPrePay);

        $account->addAccount($asset);
        $asset->addAccount($ar);
        $ar->addAccount($arPrePay);
        $asset->addAccount($current);
        $current->addAccount($bank);
        $current->addAccount($cash);
        $current->addAccount($payPal);

        // Liability
        $liability = new \HarvestCloud\CoreBundle\Entity\LiabilityAccount('Liabilities');
        $ap        = new \HarvestCloud\CoreBundle\Entity\LiabilityAccount('Accounts Payable');
        $apPrePay  = new \HarvestCloud\CoreBundle\Entity\LiabilityAccount('AP Pre-Payment');

        $this->setAccountsPayableAccount($ap);
        $this->setApPrePaymentAccount($apPrePay);

        $account->addAccount($liability);
        $liability->addAccount($ap);
        $ap->addAccount($apPrePay);

        // Income
        $income = new \HarvestCloud\CoreBundle\Entity\IncomeAccount('Income');
        $sales  = new \HarvestCloud\CoreBundle\Entity\IncomeAccount('Sales');

        $this->setSalesAccount($sales);

        $account->addAccount($income);
        $income->addAccount($sales);

        // Expenses
        $expenses        = new \HarvestCloud\CoreBundle\Entity\ExpenseAccount('Expenses');
        $purchases       = new \HarvestCloud\CoreBundle\Entity\ExpenseAccount('Purchases');
        $costOfGoodsSold = new \HarvestCloud\CoreBundle\Entity\ExpenseAccount('Cost of Goods Sold');

        $this->setExpenseAccount($expenses);
        $this->setPurchasesAccount($purchases);
        $this->setCostOfGoodsSoldAccount($costOfGoodsSold);

        $account->addAccount($expenses);
        $expenses->addAccount($purchases);
        $expenses->addAccount($costOfGoodsSold);

        return $account;
    }

    /**
     * Set costOfGoodsSoldAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Account $costOfGoodsSoldAccount
     */
    public function setCostOfGoodsSoldAccount(\HarvestCloud\CoreBundle\Entity\Account $costOfGoodsSoldAccount)
    {
        $this->costOfGoodsSoldAccount = $costOfGoodsSoldAccount;
    }

    /**
     * Get costOfGoodsSoldAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Account
     */
    public function getCostOfGoodsSoldAccount()
    {
        return $this->costOfGoodsSoldAccount;
    }

    /**
     * Set salesAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Account $salesAccount
     */
    public function setSalesAccount(\HarvestCloud\CoreBundle\Entity\Account $salesAccount)
    {
        $this->salesAccount = $salesAccount;
    }

    /**
     * Get salesAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Account
     */
    public function getSalesAccount()
    {
        return $this->salesAccount;
    }

    /**
     * Set expenseAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Account $expenseAccount
     */
    public function setExpenseAccount(\HarvestCloud\CoreBundle\Entity\Account $expenseAccount)
    {
        $this->expenseAccount = $expenseAccount;
    }

    /**
     * Get expenseAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Account
     */
    public function getExpenseAccount()
    {
        return $this->expenseAccount;
    }

    /**
     * Set accountsReceivableAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Account $accountsReceivableAccount
     */
    public function setAccountsReceivableAccount(\HarvestCloud\CoreBundle\Entity\Account $accountsReceivableAccount)
    {
        $this->accountsReceivableAccount = $accountsReceivableAccount;
    }

    /**
     * Get accountsReceivableAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Account
     */
    public function getAccountsReceivableAccount()
    {
        return $this->accountsReceivableAccount;
    }

    /**
     * Set accountsPayableAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Account $accountsPayableAccount
     */
    public function setAccountsPayableAccount(\HarvestCloud\CoreBundle\Entity\Account $accountsPayableAccount)
    {
        $this->accountsPayableAccount = $accountsPayableAccount;
    }

    /**
     * Get accountsPayableAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Account
     */
    public function getAccountsPayableAccount()
    {
        return $this->accountsPayableAccount;
    }

    /**
     * Set rootAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Account $rootAccount
     */
    public function setRootAccount(\HarvestCloud\CoreBundle\Entity\Account $rootAccount)
    {
        $this->rootAccount = $rootAccount;
    }

    /**
     * Get rootAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-01-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Account
     */
    public function getRootAccount()
    {
        return $this->rootAccount;
    }

    /**
     * Set purchasesAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Account $purchasesAccount
     *
     * @return Profile
     */
    public function setPurchasesAccount(\HarvestCloud\CoreBundle\Entity\Account $purchasesAccount = null)
    {
        $this->purchasesAccount = $purchasesAccount;

        return $this;
    }

    /**
     * Get purchasesAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getPurchasesAccount()
    {
        return $this->purchasesAccount;
    }

    /**
     * Set arPrePaymentAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @param \HarvestCloud\CoreBundle\Entity\Account $arPrePaymentAccount
     *
     * @return Profile
     */
    public function setArPrePaymentAccount(\HarvestCloud\CoreBundle\Entity\Account $arPrePaymentAccount = null)
    {
        $this->arPrePaymentAccount = $arPrePaymentAccount;

        return $this;
    }

    /**
     * Get arPrePaymentAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getArPrePaymentAccount()
    {
        return $this->arPrePaymentAccount;
    }

    /**
     * Set apPrePaymentAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Account $apPrePaymentAccount
     *
     * @return Profile
     */
    public function setApPrePaymentAccount(\HarvestCloud\CoreBundle\Entity\Account $apPrePaymentAccount = null)
    {
        $this->apPrePaymentAccount = $apPrePaymentAccount;

        return $this;
    }

    /**
     * Get apPrePaymentAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getApPrePaymentAccount()
    {
        return $this->apPrePaymentAccount;
    }

    /**
     * Set payPalAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Account $payPalAccount
     *
     * @return Profile
     */
    public function setPayPalAccount(\HarvestCloud\CoreBundle\Entity\Account $payPalAccount = null)
    {
        $this->payPalAccount = $payPalAccount;

        return $this;
    }

    /**
     * Get payPalAccount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-09
     *
     * @return \HarvestCloud\CoreBundle\Entity\Account
     */
    public function getPayPalAccount()
    {
        return $this->payPalAccount;
    }

    /**
     * prePersist
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-10
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getRootAccount())
        {
            // Create set of accounts
            $this->createSetOfAccounts();
        }

        // Set slug for Profile
        if (!$this->slug)
        {
            $this->setSlug(\Gedmo\Sluggable\Util\Urlizer::urlize($this->getName()));
        }
    }

    /**
     * Add invoiceAsVendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsVendor
     *
     * @return Profile
     */
    public function addInvoiceAsVendor(\HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsVendor)
    {
        $this->invoicesAsVendor[] = $invoiceAsVendor;

        return $this;
    }

    /**
     * Remove invoiceAsVendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsVendor
     */
    public function removeInvoiceAsVendor(\HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsVendor)
    {
        $this->invoicesAsVendor->removeElement($invoiceAsVendor);
    }

    /**
     * Get invoicesAsVendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoicesAsVendor()
    {
        return $this->invoicesAsVendor;
    }

    /**
     * Add invoicesAsCustomer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsCustomer
     *
     * @return Profile
     */
    public function addInvoiceAsCustomer(\HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsCustomer)
    {
        $this->invoicesAsCustomer[] = $invoiceAsCustomer;

        return $this;
    }

    /**
     * Remove invoiceAsCustomer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsCustomer
     */
    public function removeInvoiceAsCustomer(\HarvestCloud\CoreBundle\Entity\Invoice\Invoice $invoiceAsCustomer)
    {
        $this->invoicesAsCustomer->removeElement($invoiceAsCustomer);
    }

    /**
     * Get invoicesAsCustomer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoicesAsCustomer()
    {
        return $this->invoicesAsCustomer;
    }

    /**
     * Add journal
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Journal $journal
     *
     * @return Profile
     */
    public function addJournal(\HarvestCloud\CoreBundle\Entity\Journal $journal)
    {
        $this->journals[] = $journal;

        return $this;
    }

    /**
     * Remove journals
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param \HarvestCloud\CoreBundle\Entity\Journal $journal
     */
    public function removeJournal(\HarvestCloud\CoreBundle\Entity\Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Add postings
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Posting $postings
     *
     * @return Profile
     */
    public function addPosting(\HarvestCloud\CoreBundle\Entity\Posting $posting)
    {
        $this->postings[] = $posting;

        return $this;
    }

    /**
     * Remove postings
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param \HarvestCloud\CoreBundle\Entity\Posting $posting
     */
    public function removePosting(\HarvestCloud\CoreBundle\Entity\Posting $posting)
    {
        $this->postings->removeElement($posting);
    }

    /**
     * Get postings
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostings()
    {
        return $this->postings;
    }

    /**
     * Set stripe_user_id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  string $stripeUserId
     *
     * @return Profile
     */
    public function setStripeUserId($stripeUserId)
    {
        $this->stripe_user_id = $stripeUserId;

        return $this;
    }

    /**
     * Get stripe_user_id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return string
     */
    public function getStripeUserId()
    {
        return $this->stripe_user_id;
    }

    /**
     * Set stripe_access_token
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  string $stripeAccessToken
     *
     * @return Profile
     */
    public function setStripeAccessToken($stripeAccessToken)
    {
        $this->stripe_access_token = $stripeAccessToken;

        return $this;
    }

    /**
     * Get stripe_access_token
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return string
     */
    public function getStripeAccessToken()
    {
        return $this->stripe_access_token;
    }

    /**
     * Set stripe_publishable_key
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  string $stripePublishableKey
     *
     * @return Profile
     */
    public function setStripePublishableKey($stripePublishableKey)
    {
        $this->stripe_publishable_key = $stripePublishableKey;

        return $this;
    }

    /**
     * Get stripe_publishable_key
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return string
     */
    public function getStripePublishableKey()
    {
        return $this->stripe_publishable_key;
    }

    /**
     * Add savedCreditCards
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard $savedCreditCard
     *
     * @return Profile
     */
    public function addSavedCreditCard(\HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard $savedCreditCard)
    {
        $this->savedCreditCards[] = $savedCreditCard;

        $savedCreditCard->setProfile($this);

        return $this;
    }

    /**
     * Remove savedCreditCards
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard $savedCreditCard
     */
    public function removeSavedCreditCard(\HarvestCloud\CoreBundle\Entity\Payment\SavedCreditCard $savedCreditCard)
    {
        $this->savedCreditCards->removeElement($savedCreditCard);
    }

    /**
     * Get savedCreditCards
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSavedCreditCards()
    {
        return $this->savedCreditCards;
    }

    /**
     * Get active savedCreditCards
     *
     * Get cards that can be used for a new charge
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-27
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActiveSavedCreditCards()
    {
        return $this->savedCreditCards;
    }

    /**
     * Add productSubscriptionsAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @param  \HarvestCloud\CoreBundle\Entity\ProductSubscription $productSubscriptionsAsBuyer
     *
     * @return Profile
     */
    public function addProductSubscriptionsAsBuyer(\HarvestCloud\CoreBundle\Entity\ProductSubscription $productSubscription)
    {
        $this->productSubscriptionsAsBuyer[] = $productSubscription;

        return $this;
    }

    /**
     * Remove productSubscriptionsAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @param  \HarvestCloud\CoreBundle\Entity\ProductSubscription $productSubscription
     */
    public function removeProductSubscriptionsAsBuyer(\HarvestCloud\CoreBundle\Entity\ProductSubscription $productSubscription)
    {
        $this->productSubscriptionsAsBuyer->removeElement($productSubscription);
    }

    /**
     * Get productSubscriptionsAsBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-10-06
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductSubscriptionsAsBuyer()
    {
        return $this->productSubscriptionsAsBuyer;
    }
}
