<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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


namespace Usecase;

use Biblys\Test\ModelFactory;
use Model\Payment;
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class RecordShopPaymentsUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testCreatesOnePaymentForSingleMode(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $usecase = new RecordShopPaymentsUsecase();

        // when
        $usecase->execute($order->getId(), [Payment::MODE_CASH => 1000]);

        // then
        $payments = PaymentQuery::create()->filterByOrderId($order->getId())->find();
        $this->assertCount(1, $payments);
        $this->assertEquals(Payment::MODE_CASH, $payments[0]->getMode());
        $this->assertEquals(1000, $payments[0]->getAmount());
        $this->assertNotNull($payments[0]->getExecuted());
    }

    /**
     * @throws PropelException
     */
    public function testCreatesMultiplePaymentsForMultipleModes(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $usecase = new RecordShopPaymentsUsecase();

        // when
        $usecase->execute($order->getId(), [
            Payment::MODE_CASH  => 500,
            Payment::MODE_CARD  => 500,
        ]);

        // then
        $payments = PaymentQuery::create()->filterByOrderId($order->getId())->find();
        $this->assertCount(2, $payments);
    }

    /**
     * @throws PropelException
     */
    public function testSkipsZeroAmountModes(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $usecase = new RecordShopPaymentsUsecase();

        // when
        $usecase->execute($order->getId(), [
            Payment::MODE_CASH  => 1000,
            Payment::MODE_CHECK => 0,
            Payment::MODE_CARD  => 0,
        ]);

        // then
        $payments = PaymentQuery::create()->filterByOrderId($order->getId())->find();
        $this->assertCount(1, $payments);
        $this->assertEquals(Payment::MODE_CASH, $payments[0]->getMode());
    }
}
