<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Legacy\LegacyCodeHelper;

require_once "setUp.php";

class PaymentTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a payment
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $lm = new PaymentManager();

        $payment = $lm->create();

        $this->assertInstanceOf('Payment', $payment);
        $this->assertEquals($payment->get('site_id'), $globalSite->get('id'));

        return $payment;
    }

    /**
     * Test getting a payment
     * @depends testCreate
     */
    public function testGet(Payment $payment)
    {
        $lm = new PaymentManager();

        $gotPayment = $lm->getById($payment->get('id'));

        $this->assertInstanceOf('Payment', $payment);
        $this->assertEquals($payment->get('id'), $gotPayment->get('id'));

        return $payment;
    }

    /**
     * Test updating a payment
     * @depends testGet
     */
    public function testUpdate(Payment $payment)
    {
        $lm = new PaymentManager();

        $payment->set('payment_amount', 2300);
        $lm->update($payment);

        $updatedPayment = $lm->getById($payment->get('id'));

        $this->assertTrue($updatedPayment->has('updated'));
        $this->assertEquals($updatedPayment->get('amount'), 2300);

        return $updatedPayment;
    }

    /**
     * Test if payment has been executed
     * @depends testGet
     */
    public function testIsExecuted(Payment $payment)
    {
        $this->assertFalse($payment->isExecuted());

        $payment->set('payment_executed', date("Y-m-d H:i:s"));

        $this->assertTrue($payment->isExecuted());
    }

    /**
     * Test setting payment has executed
     */
    public function testSetExecuted()
    {
        $pm = new PaymentManager();
        $payment = $pm->create();

        $this->assertFalse($payment->isExecuted());

        $payment->setExecuted();

        $this->assertTrue($payment->isExecuted());
    }

    /**
     * Test deleting a payment
     * @depends testGet
     */
    public function testDelete(Payment $payment)
    {
        $lm = new PaymentManager();

        $lm->delete($payment, 'Test entity');

        $paymentExists = $lm->getById($payment->get('id'));

        $this->assertFalse($paymentExists);
    }
}
