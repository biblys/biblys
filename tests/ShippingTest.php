<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class ShippingTest extends PHPUnit\Framework\TestCase
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

        $fee = $sm->create([
            "site_id" => "1",
            "shipping_type" => "normal",
            "shipping_zone" => "ALL",
            "shipping_max_weight" => 1000,
            "shipping_max_amount" => 2000,
        ]);

        $country = $cm->getById(67); // France
        $order_weight = 500;
        $order_amount = 1500;

        $fees = $sm->getFees($country, $order_weight, $order_amount);

        $this->assertEquals(
            $fees[1],
            $fee
        );
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
