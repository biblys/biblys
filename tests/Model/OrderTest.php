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


namespace Model;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class OrderTest extends TestCase
{

    /** getTrackingLink */

    public function testGetTrackingLink(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("normal");

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals("", $result);
    }

    public function testGetTrackingLinkWithoutTrackingNumber(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("suivi");

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals("", $result);
    }

    public function testGetTrackingLinkForColissimo(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("suivi");
        $order->setTrackNumber("1234567890");

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals("https://www.laposte.fr/outils/suivre-vos-envois?code=1234567890", $result);
    }

    public function testGetTrackingLinkForMondialRelay(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("mondial-relay");
        $order->setTrackNumber("1234567890");
        $order->setPostalcode(46800);

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals(
            "https://www.mondialrelay.fr/suivi-de-colis?numeroExpedition=1234567890&codePostal=46800",
            $result
        );
    }

    /** getTotalWeight */

    /**
     * @throws PropelException
     */
    public function testGetTotalWeight(): void
    {
        // given
        $order = ModelFactory::createOrder();
        ModelFactory::createStockItem(order: $order, weight: 123);
        ModelFactory::createStockItem(order: $order, weight: 456);

        // when
        $result = $order->getTotalWeight();

        // then
        $this->assertEquals(579, $result);
    }

    /** getTotalAmount */

    /**
     * @throws PropelException
     */
    public function testGetTotalAmount(): void
    {
        // given
        $order = ModelFactory::createOrder();
        ModelFactory::createStockItem(order: $order, sellingPrice: 123);
        ModelFactory::createStockItem(order: $order, sellingPrice: 456);

        // when
        $result = $order->getTotalAmount();

        // then
        $this->assertEquals(579, $result);
    }
}
