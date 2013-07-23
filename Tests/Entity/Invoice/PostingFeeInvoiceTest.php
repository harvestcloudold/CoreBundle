<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Tests\Entity\Invoice;

use HarvestCloud\CoreBundle\Entity\Invoice\Invoice;
use HarvestCloud\CoreBundle\Entity\Invoice\PostingFeeInvoice;
use HarvestCloud\DoubleEntryBundle\Entity\Asset;
use HarvestCloud\DoubleEntryBundle\Entity\Liability;
use HarvestCloud\DoubleEntryBundle\Entity\Income;
use HarvestCloud\DoubleEntryBundle\Entity\Expense;

/**
 * Tests for PostingFeeInvoice
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2013-03-20
 */
class PostingFeeInvoiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConstructor()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testConstructor($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);

        $this->assertEquals(get_class($invoice), 'HarvestCloud\CoreBundle\Entity\Invoice\PostingFeeInvoice');
    }

    /**
     * testGetAmount()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testGetAmount($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);

        $this->assertEquals(4, $invoice->getAmount());
    }

    /**
     * testGetCustomer()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testGetCustomer($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);

        $this->assertSame($order->getSeller(), $invoice->getCustomer());
    }

    /**
     * testGetVendor()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testGetVendor($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);

        $this->assertSame($exchange->getProfile(), $invoice->getVendor());
    }

    /**
     * testGetOrder()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testGetOrder($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);

        $this->assertSame($order, $invoice->getOrder());
    }

    /**
     * testPost()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testPost($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);
        $invoice->post();

        $this->assertSame(Invoice::STATUS_POSTED, $invoice->getStatusCode());
    }

    /**
     * testVendorAccountsReceivableBalance()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testVendorAccountsReceivableBalance($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);
        $invoice->post();

        $this->assertEquals(4.0, $invoice->getVendor()->getAccountsReceivableAccount()->getBalance());
    }

    /**
     * testVendorSalesAccountBalance()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testVendorSalesAccountBalance($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);
        $invoice->post();

        $this->assertEquals(-4.0, $invoice->getVendor()->getSalesAccount()->getBalance());
    }

    /**
     * testCustomerAccountsPayableBalance()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testCustomerAccountsPayableBalance($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);
        $invoice->post();

        $this->assertEquals(-4.0, $invoice->getCustomer()->getAccountsPayableAccount()->getBalance());
    }

    /**
     * testCustomerCostOfGoodsSoldAccountBalance()
     *
     * @author       Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since        2013-03-20
     *
     * @dataProvider orderAndExchangeProvider
     */
    public function testCustomerCostOfGoodsSoldAccountBalance($order, $exchange)
    {
        $invoice = new PostingFeeInvoice($order, $exchange);
        $invoice->post();

        $this->assertEquals(4.0, $invoice->getCustomer()->getCostOfGoodsSoldAccount()->getBalance());
    }

    /**
     * orderAndExchangeProvider()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-20
     *
     * @return array
     */
    public function orderAndExchangeProvider()
    {
        $order    = new \HarvestCloud\CoreBundle\Entity\Order();
        $order->setSeller(new \HarvestCloud\CoreBundle\Entity\Profile());
        $order->setSubTotal(100);
        $order->getSeller()->createSetOfAccounts();

        $exchange = new \HarvestCloud\CoreBundle\Entity\Exchange();
        $exchange->setName('Exchange Name');
        $exchange->setProfile(new \HarvestCloud\CoreBundle\Entity\Profile());

        $exchange->getProfile()->createSetOfAccounts();

        return array(
            array($order, $exchange),
        );
    }
}
