<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class PaymentTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a payment
     */
    public function testCreate()
    {
        global $site;

        $lm = new PaymentManager();

        $payment = $lm->create();

        $this->assertInstanceOf('Payment', $payment);
        $this->assertEquals($payment->get('site_id'), $site->get('id'));

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
