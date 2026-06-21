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
use Model\OrderQuery;
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Repository\PaymentRepository;

require_once __DIR__ . "/../setUp.php";

class CancelOrderUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        OrderQuery::create()->deleteAll();
        PaymentQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testThrowsIfOrderHasPayments(): void
    {
        // given
        $order = ModelFactory::createOrder();
        ModelFactory::createPayment(order: $order, amount: 1500);
        $usecase = new CancelOrderUsecase(new PaymentRepository());

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("Effectuez d'abord un remboursement.");

        // when
        $usecase->execute($order->getId());
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function testCancelsOrderWhenNoPayments(): void
    {
        // given
        $order = ModelFactory::createOrder();
        $usecase = new CancelOrderUsecase(new PaymentRepository());

        // when
        $usecase->execute($order->getId());

        // then
        $order->reload();
        $this->assertNotNull($order->getCancelDate());
    }
}
