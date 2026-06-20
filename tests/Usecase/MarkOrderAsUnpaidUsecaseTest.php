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
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class MarkOrderAsUnpaidUsecaseTest extends TestCase
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
    public function testMarksOrderAsUnpaidWhenPaymentSumIsLessThanTotal(): void
    {
        // given
        $order = ModelFactory::createOrder(paymentDate: new DateTime());
        ModelFactory::createStockItem(order: $order, sellingPrice: 1500);
        ModelFactory::createPayment(order: $order, amount: 1500);
        ModelFactory::createPayment(order: $order, amount: -1500);

        // when
        $usecase = new MarkOrderAsUnpaidUsecase();
        $usecase->execute($order);

        // then
        $order->reload();
        $this->assertNull($order->getPaymentDate(), "la commande est marquée comme non payée");
    }

    /**
     * @throws PropelException
     */
    public function testDoesNotMarkOrderAsUnpaidWhenPaymentSumEqualsTotal(): void
    {
        // given
        $order = ModelFactory::createOrder(paymentDate: new DateTime());
        ModelFactory::createStockItem(order: $order, sellingPrice: 1000);
        ModelFactory::createPayment(order: $order, amount: 1000);

        // when
        $usecase = new MarkOrderAsUnpaidUsecase();
        $usecase->execute($order);

        // then
        $order->reload();
        $this->assertNotNull($order->getPaymentDate(), "la commande reste marquée comme payée");
    }

    /**
     * @throws PropelException
     */
    public function testDoesNotMarkOrderAsUnpaidWhenPaymentSumIsGreaterThanTotal(): void
    {
        // given
        $order = ModelFactory::createOrder(paymentDate: new DateTime());
        ModelFactory::createStockItem(order: $order, sellingPrice: 1000);
        ModelFactory::createPayment(order: $order, amount: 2000);

        // when
        $usecase = new MarkOrderAsUnpaidUsecase();
        $usecase->execute($order);

        // then
        $order->reload();
        $this->assertNotNull($order->getPaymentDate(), "la commande reste marquée comme payée");
    }
}
