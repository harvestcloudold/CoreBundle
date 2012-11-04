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
use HarvestCloud\CoreBundle\Entity\Order;
use HarvestCloud\PayPalBundle\Entity\PayPalPaymentCollection;
use HarvestCloud\PaymentBundle\Entity\PayPalPayment;

/**
 * OrderCollection Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-07
 *
 * @ORM\Entity
 * @ORM\Table(name="order_collection")
 * @ORM\Entity(repositoryClass="HarvestCloud\CoreBundle\Repository\OrderCollectionRepository")
 */
class OrderCollection
{
    /**
     * Currently PayPal supports a maximum of 6 (six) recipients in its
     * Parallel Payments offering, therefore we will limit the number of
     * Sellers a Buyer can buy from in one session accordingly;
     *
     * @var integer
     */
     const MAX_NUM_ORDERS = 6;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status_code;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="orderCollectionsAsBuyer")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     */
    protected $buyer;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="collection")
     */
    protected $orders;

    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\PayPalBundle\Entity\PayPalPaymentCollection", mappedBy="orderCollection")
     */
    protected $payPalPaymentCollection;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return integer
     */
    public function __construct()
    {
        $this->status_code = Order::STATUS_CART;
        $this->orders      = new ArrayCollection();
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
     * Set status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
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
     * @since  2012-05-02
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Add orders
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     * @todo   Find a better Exception class to use
     *
     * @param  Order $order
     */
    public function addOrder(Order $order)
    {
        if (count($this->orders)+1 >= self::MAX_NUM_ORDERS)
        {
            throw new Exception('Cannot have more than '.self::MAX_NUM_ORDERS.' Orders in a single Order Collection');
        }

        $this->orders[] = $order;
        $order->setCollection($this);
    }

    /**
     * Get orders
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
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
     * Get Order for given Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  Profile $seller
     *
     * @return Order
     */
    public function getOrderForSeller(Profile $seller)
    {
        foreach ($this->getOrders() as $order)
        {
            if ($order->getSeller()->getId() == $seller->getId())
            {
                return $order;
            }
        }

        return null;
    }

    /**
     * Create Order for given Seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-02
     *
     * @param  Profile $seller
     *
     * @return Order
     */
    public function createOrderForSeller(Profile $seller)
    {
        $order = new Order();
        $order->setSeller($seller);
        $order->setBuyer($this->getBuyer());

        $this->addOrder($order);

        return $order;
    }

    /**
     * Get PayPalPaymentCollection
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return PayPalPaymentCollection
     */
    public function getPayPalPaymentCollection()
    {
        if (!$this->payPalPaymentCollection)
        {
            $payPalPaymentCollection = new PayPalPaymentCollection();

            foreach ($this->getOrders() as $order)
            {
                $payment = new PayPalPayment();
                $payment->setAmount($order->getAmountForPaymentGateway());
                $payment->setPayPalAccount($order->getSeller()->getPayPalAccount());
                $payment->setInvoiceIdForGateway($order->getInvoiceIdForPaymentGateway());

                $payPalPaymentCollection->addPayPalPayment($payment);
            }

            $this->setPayPalPaymentCollection($payPalPaymentCollection);
        }

        return $this->payPalPaymentCollection;
    }

    /**
     * Set payPalPaymentCollection
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @param  PayPalPaymentCollection $payPalPaymentCollection
     */
    public function setPayPalPaymentCollection(PayPalPaymentCollection $payPalPaymentCollection)
    {
        $this->payPalPaymentCollection = $payPalPaymentCollection;
        $payPalPaymentCollection->setOrderCollection($this);
    }

    /**
     * getSubTotal()
     *
     * The sum of the Order sub_totals
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-10-23
     *
     * @return float
     */
    public function getSubTotal()
    {
        $sub_total = 0;

        foreach ($this->getOrders() as $order)
        {
            $sub_total += $order->getSubTotal();
        }

        return $sub_total;
    }

    /**
     * getSellerIds()
     *
     * Get an array of the ids for all of the Sellers in this OrderCollection
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-02
     *
     * @return array
     */
    public function getSellerIds()
    {
        $sellerIds = array();

        foreach ($this->getOrders() as $order)
        {
            $sellerIds[] = $order->getSeller()->getId();
        }

        return $sellerIds;
    }

    /**
     * setHubWindow()
     *
     * Sets the SellerWindows for each Order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-11-03
     *
     * @param  HubWindow  $hubWindow
     */
    public function setHubWindow(HubWindow $hubWindow)
    {
        // Apply the appropriate SellerWindow for each Order
        foreach ($hubWindow->getSellerWindows() as $sellerWindow)
        {
            foreach ($this->getOrders() as $order)
            {
                if ($sellerWindow->getSeller()->getId() == $order->getSeller()->getId())
                {
                    $order->setSellerWindow($sellerWindow);

                    break;
                }
            }
        }

        // Now make sure every Order has a SellerWindow
        foreach ($this->getOrders() as $order)
        {
            if (!$order->getSellerWindow()->getId())
            {
                throw new \Exception('Order #'.$order->getId().' has no SellerWindow');
            }
        }
    }
}
