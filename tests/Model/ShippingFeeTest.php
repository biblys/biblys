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

class ShippingFeeTest extends TestCase
{

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
}
