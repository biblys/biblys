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

use Biblys\Exception\CannotDeleteShippingFeeUsedByOrders;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ShippingFeeTest extends TestCase
{

    /**
     * isCompliantWithFrenchLaw
     */

    public function testIsCompliantWithFrenchLawForLowAmountAndFreeShipping()
    {
        // given
        $shippingFee = new ShippingFee();
        $shippingFee->setFee(1);
        $shippingFee->setMinAmount(1000);

        // when
        $isCompliant = $shippingFee->isCompliantWithFrenchLaw();

        // then
        $this->assertFalse($isCompliant);
    }

    public function testIsCompliantWithFrenchLawForLowAmount()
    {
        // given
        $shippingFee = new ShippingFee();
        $shippingFee->setFee(350);
        $shippingFee->setMinAmount(1000);

        // when
        $isCompliant = $shippingFee->isCompliantWithFrenchLaw();

        // then
        $this->assertTrue($isCompliant);
    }

    public function testIsCompliantWithFrenchLawForFreeShipping()
    {
        // given
        $shippingFee = new ShippingFee();
        $shippingFee->setFee(1);
        $shippingFee->setMinAmount(3500);

        // when
        $isCompliant = $shippingFee->isCompliantWithFrenchLaw();

        // then
        $this->assertTrue($isCompliant);
    }

    public function testIsCompliantWithFrenchLawForClickAndCollect()
    {
        // given
        $shippingFee = new ShippingFee();
        $shippingFee->setType('magasin');
        $shippingFee->setFee(0);
        $shippingFee->setMinAmount(0);

        // when
        $isCompliant = $shippingFee->isCompliantWithFrenchLaw();

        // then
        $this->assertTrue($isCompliant);
    }

    /**
     * delete
     */

    /**
     * @throws PropelException
     */
    public function testDeleteWithoutRelatedOrders(): void
    {
        // given
        $shippingFee = ModelFactory::createShippingFee();

        // when
        $shippingFee->delete();

        // then
        $this->assertTrue($shippingFee->isDeleted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteWithRelatedOrders(): void
    {
        // given
        $shippingFee = ModelFactory::createShippingFee();
        ModelFactory::createOrder(shippingFee: $shippingFee);

        // when
        $exception = Helpers::runAndCatchException(fn() => $shippingFee->delete());

        // then
        $this->assertInstanceOf(CannotDeleteShippingFeeUsedByOrders::class, $exception);
        $this->assertEquals(
            "Vous ne pouvez pas supprimer cette tranche de frais de port ".
            "car elle est utilisée par 1 commande(s), mais vous pouvez l'archiver.",
            $exception->getMessage()
        );
        $this->assertFalse($shippingFee->isDeleted());
    }
}
