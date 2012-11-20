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
use HarvestCloud\DoubleEntryBundle\Entity\Account;

/**
 * Profile Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-05
 *
 * @ORM\Entity
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\ProfileRepository")
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
     * @ORM\Column(type="decimal", scale="7", nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale="7", nullable=true)
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
    protected $pay_pal_account;

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
     * @ORM\OneToMany(targetEntity="HarvestCloud\DoubleEntryBundle\Entity\Account", mappedBy="profile")
     */
    protected $accounts;

    /**
     * The fixed part (in dollars) of the fee that a Seller is charged by the
     * system for each Order that is completed
     *
     *   e.g. $0.10 per order (see caculation below)
     *
     * @ORM\Column(type="decimal", scale="2")
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
     * @ORM\Column(type="decimal", scale="3")
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
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     */
    public function __construct()
    {
        $this->usersAsDefaultProfile   = new ArrayCollection();
        $this->users                   = new ArrayCollection();
        $this->locations               = new ArrayCollection();
        $this->buyerHubRefsAsBuyer     = new ArrayCollection();
        $this->buyerHubRefsAsHub       = new ArrayCollection();
        $this->sellerHubRefsAsSeller   = new ArrayCollection();
        $this->sellerHubRefsAsHub      = new ArrayCollection();
        $this->ordersAsBuyer           = new ArrayCollection();
        $this->ordersAsSeller          = new ArrayCollection();
        $this->ordersAsHub             = new ArrayCollection();
        $this->orderCollectionsAsBuyer = new ArrayCollection();
        $this->productsAsSeller        = new ArrayCollection();
        $this->accounts                = new ArrayCollection();
        $this->hubWindows              = new ArrayCollection();
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
     * Set pay_pal_account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @param string $payPalAccount
     */
    public function setPayPalAccount($payPalAccount)
    {
        $this->pay_pal_account = $payPalAccount;
    }

    /**
     * Get pay_pal_account
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-29
     *
     * @return string
     */
    public function getPayPalAccount()
    {
        return $this->pay_pal_account;
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
     * @return \HarvestCloud\DoubleEntryBundle\Entity\Account
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
     * @return \HarvestCloud\DoubleEntryBundle\Entity\Account
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
     * @return \HarvestCloud\DoubleEntryBundle\Entity\Account
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
}
