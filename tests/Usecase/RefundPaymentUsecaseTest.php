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
use DateTime;
use Model\Payment;
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class RefundPaymentUsecaseTest extends TestCase
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
     * @throws BusinessRuleException
     */
    public function testRefundsPayment(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $payment = ModelFactory::createPayment(order: $order, amount: 1500, mode: Payment::MODE_STRIPE);

        // when
        $usecase = new RefundPaymentUsecase();
        $refund = $usecase->execute($payment);

        // then
        $payment->reload();
        $this->assertNotNull($payment->getRefundedAt());

        $this->assertEquals(-1500, $refund->getAmount());
        $this->assertEquals(Payment::MODE_STRIPE, $refund->getMode());
        $this->assertEquals($order->getId(), $refund->getOrderId());
        $this->assertEquals($payment->getId(), $refund->getOriginalId());
        $this->assertNotNull($refund->getExecuted());
    }

    /**
     * @throws PropelException
     */
    public function testThrowsIfAlreadyRefunded(): void
    {
        // given
        $payment = ModelFactory::createPayment(refundedAt: new DateTime());

        // when / then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("Ce paiement a déjà été remboursé.");

        $usecase = new RefundPaymentUsecase();
        $usecase->execute($payment);
    }

    /**
     * @throws PropelException
     */
    public function testThrowsIfNegativeAmount(): void
    {
        // given
        $payment = ModelFactory::createPayment(amount: -1000);

        // when / then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("Impossible de rembourser un paiement négatif.");

        $usecase = new RefundPaymentUsecase();
        $usecase->execute($payment);
    }
}
