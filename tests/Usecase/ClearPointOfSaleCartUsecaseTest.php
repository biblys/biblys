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
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class ClearPointOfSaleCartUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testClearsCartAndPutsStockItemsBackInStock(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->setCustomerId(1);
        $cart->setTitle("Panier de test");
        $cart->save();
        $firstStock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1899);
        $secondStock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1299);
        $cart->setCount(2);
        $cart->setAmount(3198);
        $cart->save();
        $usecase = new ClearPointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId());

        // then
        $firstStock->reload();
        $this->assertNull($firstStock->getCartId());
        $secondStock->reload();
        $this->assertNull($secondStock->getCartId());
        $cart->reload();
        $this->assertEquals(0, $cart->getCount());
        $this->assertEquals(0, $cart->getAmount());
        $this->assertNull($cart->getCustomerId());
        $this->assertEquals("Panier n&deg; " . $cart->getId(), $cart->getTitle());
    }

    /**
     * @throws PropelException
     */
    public function testClearsAlreadyEmptyCartWithoutError(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $usecase = new ClearPointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId());

        // then
        $cart->reload();
        $this->assertEquals(0, $cart->getCount());
    }
}
