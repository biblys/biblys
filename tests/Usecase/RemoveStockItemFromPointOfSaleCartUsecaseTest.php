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

use Biblys\Exception\CannotRemoveStockItemFromCartException;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class RemoveStockItemFromPointOfSaleCartUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testRemovesStockItemFromPointOfSaleCart(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1899);
        $cart->setCount(1);
        $cart->setAmount(1899);
        $cart->save();
        $usecase = new RemoveStockItemFromPointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId(), $stock->getId());

        // then
        $stock->reload();
        $this->assertNull($stock->getCartId());
        $cart->reload();
        $this->assertEquals(0, $cart->getCount());
        $this->assertEquals(0, $cart->getAmount());
    }

    /**
     * @throws PropelException
     */
    public function testThrowsWhenStockItemNotInGivenCart(): void
    {
        // given
        $firstCart = ModelFactory::createCart();
        $firstCart->setType("shop");
        $firstCart->save();
        $secondCart = ModelFactory::createCart();
        $secondCart->setType("shop");
        $secondCart->save();
        $stock = ModelFactory::createStockItem(cart: $firstCart);

        $usecase = new RemoveStockItemFromPointOfSaleCartUsecase();

        // then
        $this->expectException(CannotRemoveStockItemFromCartException::class);

        // when
        $usecase->execute($secondCart->getId(), $stock->getId());
    }

    /**
     * @throws PropelException
     */
    public function testThrowsWhenStockItemNotFound(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();

        $usecase = new RemoveStockItemFromPointOfSaleCartUsecase();

        // then
        $this->expectException(CannotRemoveStockItemFromCartException::class);

        // when
        $usecase->execute($cart->getId(), 99999);
    }
}
