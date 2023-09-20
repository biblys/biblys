<?php

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
