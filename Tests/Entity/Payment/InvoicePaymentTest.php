<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Tests\Entity\Payment;

use HarvestCloud\CoreBundle\Entity\Payment\InvoicePayment;

/**
 * Tests for InvoicePayment
 *
 * @author  Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since   2013-03-25
 */
class InvoicePaymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConstructorWithEmptyArray()
     *
     * @author            Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since             2013-03-25
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithEmptyArray()
    {
        $payment = new InvoicePayment(array());
    }
}
