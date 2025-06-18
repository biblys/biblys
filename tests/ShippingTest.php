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
        $sm->update($shipping);

        $updatedShipping = $sm->getById($shipping->get('id'));

        $this->assertTrue($updatedShipping->has('updated'));
        $this->assertEquals('Shipping mode', $updatedShipping->get('mode'));

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
