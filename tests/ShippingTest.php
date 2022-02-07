<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Test\EntityFactory;

require_once "setUp.php";

class ShippingTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a copy
     * @throws Exception
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
    public function testGet(Shipping $shipping): Shipping
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
     * @throws Exception
     */
    public function testUpdate(Shipping $shipping)
    {
        $sm = new ShippingManager();

        $shipping->set('shipping_mode', 'Shipping mode');
        $shipping->set('shipping_zone', 'F');
        $sm->update($shipping);

        $updatedShipping = $sm->getById($shipping->get('id'));

        $this->assertTrue($updatedShipping->has('updated'));
        $this->assertEquals('Shipping mode', $updatedShipping->get('mode'));
        $this->assertEquals('F', $updatedShipping->get('zone'));

        return $updatedShipping;
    }

    /**
     * Test deleting a copy
     * @depends testGet
     * @throws Exception
     */
    public function testDelete(Shipping $shipping)
    {
        $sm = new ShippingManager();

        $sm->delete($shipping, 'Test entity');

        $shippingExists = $sm->getById($shipping->get('id'));

        $this->assertFalse($shippingExists);
    }
}
