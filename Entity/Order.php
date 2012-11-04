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
use HarvestCloud\ProfileFinancialBundle\Entity\ProfileInvoice as Invoice;

/**
 * Order Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-05
 *
 * @ORM\Entity
 * @ORM\Table(name="order_")
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * Statuses
     *
     * @var int
     */
    const
        STATUS_CART                         = 1,
        STATUS_CART_ABANDONNED              = 2,
        STATUS_NEW                          = 3,
        STATUS_CANCELED_BY_BUYER            = 4,
        STATUS_ACCEPTED_BY_SELLER           = 5,
        STATUS_REJECTED_BY_SELLER           = 6,
        STATUS_IN_PICK_AT_SELLER            = 7,
        STATUS_READY_FOR_DISPATCH_TO_HUB    = 8,
        STATUS_IN_TRANSIT_TO_HUB            = 9,
        STATUS_AT_HUB                       = 10,
        STATUS_READY_FOR_PICKUP_FROM_HUB    = 11,
        STATUS_PICKED_UP_FROM_HUB           = 12,
        STATUS_COMPLETED                    = 13
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $rating;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status_code;

    /**
     * @ORM\ManyToOne(targetEntity="OrderCollection", inversedBy="orders")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     */
    protected $collection;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="ordersAsBuyer")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     */
    protected $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="ordersAsSeller")
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    protected $seller;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="ordersAsHub")
     * @ORM\JoinColumn(name="hub_id", referencedColumnName="id")
     */
    protected $hub;

    /**
     * @ORM\OneToMany(targetEntity="OrderLineItem", mappedBy="order", cascade={"persist"})
     */
    protected $lineItems;

    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\ProfileFinancialBundle\Entity\ProfileOrderInvoice", mappedBy="order")
     */
    protected $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="SellerWindow", inversedBy="orders")
     * @ORM\JoinColumn(name="seller_window_id", referencedColumnName="id")
     */
    protected $sellerWindow;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-09
     */
    public function __construct()
    {
        $this->lineItems = new ArrayCollection();

        $this->setStatusCode(self::STATUS_CART);
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
     * Get status
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return string
     */
    public function getStatus()
    {
        $statuses = array(
            self::STATUS_CART                       => 'Cart',
            self::STATUS_NEW                        => 'New',
            self::STATUS_CANCELED_BY_BUYER          => 'Canceled by Buyer',
            self::STATUS_ACCEPTED_BY_SELLER         => 'Accepted by Seller',
            self::STATUS_REJECTED_BY_SELLER         => 'Rejected by Seller',
            self::STATUS_IN_PICK_AT_SELLER          => 'In pick at Seller',
            self::STATUS_READY_FOR_DISPATCH_TO_HUB  => 'Ready to be dispatched to Hub',
            self::STATUS_IN_TRANSIT_TO_HUB          => 'In transit to Hub',
            self::STATUS_AT_HUB                     => 'At Hub',
            self::STATUS_READY_FOR_PICKUP_FROM_HUB  => 'Ready to be picked up from Hub',
            self::STATUS_PICKED_UP_FROM_HUB         => 'Picked up by Buyer',
        );

        if (array_key_exists($this->getStatusCode(), $statuses))
        {
            return $statuses[$this->getStatusCode()];
        }

        return 'Unknown';
    }


    /**
     * Get open status codes
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @return array
     */
    public static function getOpenStatusCodes()
    {
        return array(
            self::STATUS_NEW,
            self::STATUS_ACCEPTED_BY_SELLER,
            self::STATUS_REJECTED_BY_SELLER,
            self::STATUS_IN_PICK_AT_SELLER,
            self::STATUS_READY_FOR_DISPATCH_TO_HUB,
            self::STATUS_IN_TRANSIT_TO_HUB,
            self::STATUS_READY_FOR_PICKUP_FROM_HUB,
            self::STATUS_AT_HUB,
        );
    }

    /**
     * isOpen
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @return bool
     */
    public function isOpen()
    {
        return array_key_exists($this->getStatusCode(), self::getOpenStatusCodes());
    }


    /**
     * Get sum of line items
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-13
     *
     * @return float
     */
    public function getSumOfLineItems()
    {
        $amount = 0;

        foreach ($this->getLineItems() as $lineItem)
        {
            $amount += $lineItem->getPrice()*$lineItem->getQuantity();
        }

        return round($amount, 2);
    }

    /**
     * getSubTotal()
     *
     * Sum of line items minus any discounts
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-23
     *
     * @return float
     */
    public function getSubTotal()
    {
        return $this->getSumOfLineItems();
    }

    /**
     * Set buyer_profile_id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-05
     *
     * @param integer $buyerProfileId
     */
    public function setBuyerProfileId($buyerProfileId)
    {
        $this->buyer_profile_id = $buyerProfileId;
    }

    /**
     * Get buyer_profile_id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-05
     *
     * @return integer
     */
    public function getBuyerProfileId()
    {
        return $this->buyer_profile_id;
    }

    /**
     * Add lineItem
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-05
     *
     * @param HarvestCloud\CoreBundle\Entity\OrderLineItem $lineItems
     */
    public function addLineItem(\HarvestCloud\CoreBundle\Entity\OrderLineItem $lineItem)
    {
        $this->lineItems[] = $lineItem;

        $lineItem->setOrder($this);
    }

    /**
     * Get lineItems
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-05
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * Get OrderLineItem for given Product
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-10
     *
     * @param  HarvestCloud\CoreBundle\Entity\Product
     *
     * @return HarvestCloud\CoreBundle\Entity\OrderLineItem
     */
    public function getLineItemForProduct(Product $product)
    {
        foreach ($this->getLineItems() as $lineItem)
        {
            if ($lineItem->getProduct()->getId() == $product->getId())
            {
                return $lineItem;
            }
        }

        return null;
    }

    /**
     * Create OrderLineItem for given Product
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-10
     *
     * @todo  Work out better default quantity
     *
     * @param  HarvestCloud\CoreBundle\Entity\Product
     *
     * @return HarvestCloud\CoreBundle\Entity\OrderLineItem
     */
    public function createLineItemForProduct(Product $product)
    {
        $lineItem = new OrderLineItem();
        $lineItem->setProduct($product);
        $lineItem->setQuantity(1);

        $this->addLineItem($lineItem);

        return $lineItem;
    }

    /**
     * place order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @todo   Peform some sanity checks
     * @todo   Refactor the decrement part
     */
    public function place()
    {
        $this->setStatusCode(self::STATUS_NEW);

        foreach ($this->getLineItems() as $lineItem)
        {
            $quantity = $lineItem->getQuantity();

            // Decrement stock from Product
            $product = $lineItem->getProduct();
            $product->setQuantityOnHold($product->getQuantityOnHold()+$quantity);
            $product->setQuantityAvailable($product->getQuantityAvailable()-$quantity);

            // Create stock transaction for each line item
            $stockTransaction = new OrderStockTransaction();
            $stockTransaction->setQuantity(-1*$quantity);
            $stockTransaction->setProduct($product);
            $stockTransaction->setStatusCode(OrderStockTransaction::STATUS_PENDING);

            $lineItem->setStockTransaction($stockTransaction);
        }
    }


    /**
     * acceptBySeller order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @todo   Peform some sanity checks
     * @todo   Refactor the decrement part
     */
    public function acceptBySeller()
    {
        $this->setStatusCode(self::STATUS_ACCEPTED_BY_SELLER);
    }


    /**
     * rejectBySeller order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @todo   Peform some sanity checks
     */
    public function rejectBySeller()
    {
        $this->setStatusCode(self::STATUS_REJECTED_BY_SELLER);
    }


    /**
     * cancelByBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @todo   Peform some sanity checks
     * @todo   Perform refund
     */
    public function cancelByBuyer()
    {
        $this->setStatusCode(self::STATUS_CANCELED_BY_BUYER);
    }


    /**
     * pickBySeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @todo   Peform some sanity checks
     */
    public function pickBySeller()
    {
        $this->setStatusCode(self::STATUS_IN_PICK_AT_SELLER);
    }


    /**
     * markReadyForDispatchBySeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @todo   Peform some sanity checks
     */
    public function markReadyForDispatchBySeller()
    {
        $this->setStatusCode(self::STATUS_READY_FOR_DISPATCH_TO_HUB);
    }


    /**
     * dispatchBySeller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @todo   Peform some sanity checks
     */
    public function dispatchBySeller()
    {
        $this->setStatusCode(self::STATUS_IN_TRANSIT_TO_HUB);
    }


    /**
     * receiveByHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @todo   Peform some sanity checks
     */
    public function receiveByHub()
    {
        $this->setStatusCode(self::STATUS_AT_HUB);
    }


    /**
     * markReadyForPickupFromHub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @todo   Peform some sanity checks
     */
    public function markReadyForPickupFromHub()
    {
        $this->setStatusCode(self::STATUS_READY_FOR_PICKUP_FROM_HUB);
    }


    /**
     * releaseToBuyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @todo   Peform some sanity checks
     */
    public function releaseToBuyer()
    {
        $this->setStatusCode(self::STATUS_PICKED_UP_FROM_HUB);
    }


    /**
     * Set status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @param integer $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;
    }

    /**
     * Get status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-11
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Set Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-30
     *
     * @param HarvestCloud\CoreBundle\Entity\Profile $seller
     */
    public function setSeller(\HarvestCloud\CoreBundle\Entity\Profile $seller)
    {
        $this->seller = $seller;
    }

    /**
     * Get Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-30
     *
     * @return HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getSeller()
    {
        return $this->seller;
    }


    /**
     * Get amount for PaymentGateway
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-01
     * @todo   make sure we're calculating line item price properly
     *
     * @return float
     */
    public function getAmountForPaymentGateway()
    {
        $amount = 0;

        foreach ($this->getLineItems() as $lineItem)
        {
            $amount += $lineItem->getPrice()*$lineItem->getQuantity();
        }

        return round($amount, 2);
    }

    /**
     * Get invoiceId for PaymentGateway
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-01
     * @todo   Maybe use a similar format system wide
     *
     * @return string
     */
    public function getInvoiceIdForPaymentGateway()
    {
        return
            implode(str_split(str_pad($this->getSeller()->getId(), 6, 0, STR_PAD_LEFT),3), '-')
            .' / '
            .implode(str_split(str_pad($this->getId(), 6, 0, STR_PAD_LEFT),3), '-')
            .' / '
            .$this->getSeller()->getName();
    }

    /**
     * Set collection
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  OrderCollection $collection
     */
    public function setCollection(OrderCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get collection
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return OrderCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set Buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  Profile $buyer
     */
    public function setBuyer(Profile $buyer)
    {
        $this->buyer = $buyer;
    }

    /**
     * Get Buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Profile
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  Profile $hub
     */
    public function setHub(Profile $hub)
    {
        $this->hub = $hub;
    }

    /**
     * Get Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Profile
     */
    public function getHub()
    {
        return $this->hub;
    }


    /**
     * Can be accepted by seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-13
     *
     * @return bool
     */
    public function canBeAcceptedBySeller()
    {
        if (self::STATUS_NEW == $this->getStatusCode())
        {
            return true;
        }

        return false;
    }


    /**
     * Can be rejected by seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return bool
     */
    public function canBeRejectedBySeller()
    {
        if (self::STATUS_NEW == $this->getStatusCode())
        {
            return true;
        }

        return false;
    }


    /**
     * Can be canceled by Buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return bool
     */
    public function canBeCanceledByBuyer()
    {
        if (self::STATUS_NEW == $this->getStatusCode())
        {
            return true;
        }

        return false;
    }


    /**
     * Can be picked by Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return bool
     */
    public function canBePickedBySeller()
    {
        if (self::STATUS_ACCEPTED_BY_SELLER == $this->getStatusCode())
        {
            return true;
        }

        return false;
    }


    /**
     * Can be marked ready for dispatch by Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @return bool
     */
    public function canBeMarkedReadyForDispatchedBySeller()
    {
        switch ($this->getStatusCode())
        {
            case self::STATUS_ACCEPTED_BY_SELLER:
            case self::STATUS_IN_PICK_AT_SELLER:

                return true;

            default:

                return false;
        }
    }


    /**
     * Can be dispatched by Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return bool
     */
    public function canBeDispatchedBySeller()
    {
        switch ($this->getStatusCode())
        {
            case self::STATUS_ACCEPTED_BY_SELLER:
            case self::STATUS_IN_PICK_AT_SELLER:
            case self::STATUS_READY_FOR_DISPATCH_TO_HUB:

                return true;

            default:

                return false;
        }
    }


    /**
     * Can be received by Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return bool
     */
    public function canBeReceivedByHub()
    {
        switch ($this->getStatusCode())
        {
            case self::STATUS_READY_FOR_DISPATCH_TO_HUB:
            case self::STATUS_IN_TRANSIT_TO_HUB:

                return true;

            default:

                return false;
        }
    }


    /**
     * Can be marked as ready to pickup from hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-15
     *
     * @return bool
     */
    public function canBeMarkedReadyToBePickedUpFromHub()
    {
        switch ($this->getStatusCode())
        {
            case self::STATUS_AT_HUB:

                return true;

            default:

                return false;
        }
    }


    /**
     * Can be released by Hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-14
     *
     * @return bool
     */
    public function canBeReleasedByHub()
    {
        switch ($this->getStatusCode())
        {
            case self::STATUS_READY_FOR_PICKUP_FROM_HUB:
            case self::STATUS_AT_HUB:

                return true;

            default:

                return false;
        }
    }


    /**
     * Can be rayted by Buyer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-28
     *
     * @return bool
     */
    public function canBeRatedByBuyer()
    {
        switch ($this->getStatusCode())
        {
            case self::STATUS_COMPLETED:
            case self::STATUS_PICKED_UP_FROM_HUB:

                return true;

            default:

                return false;
        }
    }

    /**
     * Set sellerWindow
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-19
     *
     * @param  SellerWindow $sellerWindow
     */
    public function setSellerWindow(SellerWindow $sellerWindow)
    {
        $this->sellerWindow = $sellerWindow;
        $this->setHub($sellerWindow->getSellerHubRef()->getHub());
    }

    /**
     * Get sellerWindow
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-19
     *
     * @return SellerWindow
     */
    public function getSellerWindow()
    {
        return $this->sellerWindow;
    }

    /**
     * Set rating
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-30
     *
     * @param integer $rating
     */
    public function setRating($rating)
    {
        if (!in_array((int) $rating, range(1,5)))
        {
            throw new \Exception('Rating can only be an integer 1-5');
        }

        $this->rating = $rating;
    }

    /**
     * Get rating
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-30
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }


    /**
     * rate
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-30
     *
     * @param integer $rating
     */
    public function rate($rating)
    {
        $this->setRating($rating);
    }
}
