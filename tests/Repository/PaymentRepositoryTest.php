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
use DateTime;
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
    public function testSaveRefundPersistsBothPayments(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $original = ModelFactory::createPayment(order: $order, amount: 1500, mode: Payment::MODE_STRIPE);
        $original->setRefundedAt(new DateTime());

        $refund = new Payment();
        $refund->setOrderId($order->getId());
        $refund->setMode(Payment::MODE_STRIPE);
        $refund->setAmount(-1500);
        $refund->setOriginalId($original->getId());
        $refund->setExecuted(new DateTime());

        $repo = new PaymentRepository();

        // when
        $repo->saveRefund($original, $refund);

        // then
        $original->reload();
        $this->assertNotNull($original->getRefundedAt());
        $this->assertNotNull($refund->getId());
        $this->assertEquals(-1500, $refund->getAmount());
        $this->assertEquals($original->getId(), $refund->getOriginalId());
    }
}
