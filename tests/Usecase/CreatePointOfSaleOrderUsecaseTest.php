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
use Model\CartQuery;
use Model\OrderQuery;
use Model\Payment;
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class CreatePointOfSaleOrderUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testCreatesShopOrderWithSellerAndCustomer(): void
    {
        // given
        $seller = ModelFactory::createUser();
        $customer = ModelFactory::createCustomer();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1899);

        $usecase = new CreatePointOfSaleOrderUsecase();

        // when
        $orderId = $usecase->execute(
            $cart->getId(),
            sellerId: $seller->getId(),
            customerId: $customer->getId(),
            cashAmount: 1899,
            chequeAmount: 0,
            cardAmount: 0,
            amountToBePaid: 0,
            paymentLeft: 0,
        );

        // then
        $order = OrderQuery::create()->findPk($orderId);
        $this->assertNotNull($order);
        $this->assertEquals("shop", $order->getType());
        $this->assertEquals($seller->getId(), $order->getSellerId());
        $this->assertEquals($customer->getId(), $order->getCustomerId());
        $this->assertEquals(1899, $order->getPaymentCash());

        $stock->reload();
        $this->assertEquals($orderId, $stock->getOrderId());
    }

    /**
     * @throws PropelException
     */
    public function testRecordsPaymentsForEachMode(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        ModelFactory::createStockItem(cart: $cart, sellingPrice: 3000);

        $usecase = new CreatePointOfSaleOrderUsecase();

        // when
        $orderId = $usecase->execute(
            $cart->getId(),
            sellerId: null,
            customerId: null,
            cashAmount: 1000,
            chequeAmount: 1000,
            cardAmount: 1000,
            amountToBePaid: 0,
            paymentLeft: 0,
        );

        // then
        $payments = PaymentQuery::create()->filterByOrderId($orderId)->find();
        $this->assertCount(3, $payments);
        $modes = array_map(fn(Payment $payment) => $payment->getMode(), iterator_to_array($payments));
        $this->assertContains(Payment::MODE_CASH, $modes);
        $this->assertContains(Payment::MODE_CHECK, $modes);
        $this->assertContains(Payment::MODE_CARD, $modes);
    }

    /**
     * @throws PropelException
     */
    public function testDeletesCartAfterSale(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $cartId = $cart->getId();

        $usecase = new CreatePointOfSaleOrderUsecase();

        // when
        $usecase->execute(
            $cartId,
            sellerId: null,
            customerId: null,
            cashAmount: 0,
            chequeAmount: 0,
            cardAmount: 0,
            amountToBePaid: 0,
            paymentLeft: 0,
        );

        // then
        $this->assertFalse(CartQuery::create()->filterById($cartId)->exists());
    }
}
