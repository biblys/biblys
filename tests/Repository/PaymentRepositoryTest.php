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

namespace Repository;

use Biblys\Test\ModelFactory;
use Model\Order;
use Model\Payment;
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class PaymentRepositoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PaymentQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testFindByIdReturnsPayment(): void
    {
        // given
        $payment = ModelFactory::createPayment();
        $repo = new PaymentRepository();

        // when
        $found = $repo->findById($payment->getId());

        // then
        $this->assertNotNull($found);
        $this->assertEquals($payment->getId(), $found->getId());
    }

    /**
     * @throws PropelException
     */
    public function testFindByIdReturnsNullWhenNotFound(): void
    {
        // given
        $repo = new PaymentRepository();

        // when
        $found = $repo->findById(999999);

        // then
        $this->assertNull($found);
    }

    /**
     * @throws PropelException
     */
    public function testFindByOrderReturnsPaymentsForOrder(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $payment = ModelFactory::createPayment(order: $order, amount: 1500);
        ModelFactory::createPayment(amount: 500);
        $repo = new PaymentRepository();

        // when
        $found = $repo->findByOrder($order);

        // then
        $this->assertCount(1, $found);
        $this->assertEquals($payment->getId(), $found[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testFindExecutedByOrderReturnsOnlyExecutedPayments(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $executed = ModelFactory::createPayment(order: $order, amount: 1500);
        ModelFactory::createPayment(order: $order, amount: 500, executedAt: null);
        $repo = new PaymentRepository();

        // when
        $found = $repo->findExecutedByOrder($order);

        // then
        $this->assertCount(1, $found);
        $this->assertEquals($executed->getId(), $found[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testSavePersistsPayment(): void
    {
        // given
        $payment = new Payment();
        $payment->setMode(Payment::MODE_STRIPE);
        $payment->setAmount(1500);
        $repo = new PaymentRepository();

        // when
        $repo->save($payment);

        // then
        $this->assertNotNull($payment->getId());
        $this->assertEquals(1500, $payment->getAmount());
    }
}
