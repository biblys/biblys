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
     * Test getting fees
     * @throws Exception
     */
    public function testGetFees()
    {
        // given
        $sm = new ShippingManager();
        $site = EntityFactory::createSite();
        $GLOBALS["site"] = $site;
        $country = EntityFactory::createCountry();
        $fee = EntityFactory::createShipping([
            "site_id" => $site->get('id'),
            "type" => "normal",
            "zone" => $country->get("shipping_zone"),
            "max_weight" => 1000,
            "max_amount" => 2000,
        ]);
        $orderWeight = 500;
        $orderAmount = 1500;

        // when
        list(, $feeNormal) = $sm->getFees($country, $orderWeight, $orderAmount);

        // then
        $this->assertEquals(
            $feeNormal,
            $fee
        );
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
