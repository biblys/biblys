<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class ShippingTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $sm = new ShippingManager();

        $shipping = $sm->create();

        $this->assertInstanceOf('Shipping', $shipping);

        return $shipping;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Shipping $shipping)
    {
        $sm = new ShippingManager();

        $gotShipping = $sm->getById($shipping->get('id'));

        $this->assertInstanceOf('Shipping', $shipping);
        $this->assertEquals($shipping->get('id'), $gotShipping->get('id'));

        return $shipping;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Shipping $shipping)
    {
        $sm = new ShippingManager();

        $shipping->set('shipping_mode', 'Shipping mode');
        $shipping->set('shipping_zone', 'F');
        $sm->update($shipping);

        $updatedShipping = $sm->getById($shipping->get('id'));

        $this->assertTrue($updatedShipping->has('updated'));
        $this->assertEquals($updatedShipping->get('mode'), 'Shipping mode');
        $this->assertEquals($updatedShipping->get('zone'), 'F');

        return $updatedShipping;
    }

    /**
     * Test getting fees
     */
    public function testGetFees()
    {
        $sm = new ShippingManager();
        $cm = new CountryManager();

        $country = $cm->getById(67); // France
        $order_weight = 1000;
        $order_amount = 2714;

        $fees = $sm->getFees($country, $order_weight, $order_amount);

        foreach ($fees as $fee)
        {
            $this->assertInstanceOf('Shipping', $fee);
        }

    }

    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Shipping $shipping)
    {
        $sm = new ShippingManager();

        $sm->delete($shipping, 'Test entity');

        $shippingExists = $sm->getById($shipping->get('id'));

        $this->assertFalse($shippingExists);
    }





























}
