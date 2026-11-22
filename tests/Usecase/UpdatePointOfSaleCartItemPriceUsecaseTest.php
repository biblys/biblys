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

use Biblys\Exception\CannotUpdateStockItemPriceException;
use Biblys\Test\ModelFactory;
use Model\Cart;
use Model\Stock;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class UpdatePointOfSaleCartItemPriceUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testUpdatesPriceAndKeepsOriginalPriceOfUsedItem(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000, condition: "Bon état");
        $this->_syncCartTotals($cart, 1000);
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // when
        $usecase->execute($cart->getId(), $stock->getId(), 800);

        // then
        $stock->reload();
        $this->assertEquals(800, $stock->getSellingPrice());
        $this->assertEquals(1000, $stock->getSellingPriceSaved());
    }

    /**
     * @throws PropelException
     */
    public function testReturnsUpdatedStockItem(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000, condition: "Bon état");
        $this->_syncCartTotals($cart, 1000);
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // when
        $updatedStockItem = $usecase->execute($cart->getId(), $stock->getId(), 800);

        // then
        $this->assertEquals($stock->getId(), $updatedStockItem->getId());
        $this->assertEquals(800, $updatedStockItem->getSellingPrice());
        $this->assertEquals(1000, $updatedStockItem->getSellingPriceSaved());
    }

    /**
     * @throws PropelException
     */
    public function testUpdatesCartAmount(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000, condition: "Bon état");
        $this->_syncCartTotals($cart, 1000);
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // when
        $usecase->execute($cart->getId(), $stock->getId(), 800);

        // then
        $cart->reload();
        $this->assertEquals(800, $cart->getAmount());
        $this->assertEquals(1, $cart->getCount());
    }

    /**
     * @throws PropelException
     */
    public function testKeepsFirstOriginalPriceWhenPriceIsUpdatedAgain(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000, condition: "Bon état");
        $this->_syncCartTotals($cart, 1000);
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();
        $usecase->execute($cart->getId(), $stock->getId(), 800);

        // when
        $usecase->execute($cart->getId(), $stock->getId(), 700);

        // then
        $stock->reload();
        $this->assertEquals(700, $stock->getSellingPrice());
        $this->assertEquals(1000, $stock->getSellingPriceSaved());
    }

    /**
     * @throws PropelException
     */
    public function testForgetsOriginalPriceWhenPriceIsBackToOriginal(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000, condition: "Bon état");
        $this->_syncCartTotals($cart, 1000);
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();
        $usecase->execute($cart->getId(), $stock->getId(), 800);

        // when
        $usecase->execute($cart->getId(), $stock->getId(), 1000);

        // then
        $stock->reload();
        $this->assertEquals(1000, $stock->getSellingPrice());
        $this->assertNull($stock->getSellingPriceSaved());
    }

    /**
     * @throws PropelException
     */
    public function testThrowsWhenStockItemIsNew(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(
            cart: $cart,
            sellingPrice: 1000,
            condition: Stock::CONDITION_NEW,
        );
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // then
        $this->expectException(CannotUpdateStockItemPriceException::class);
        $this->expectExceptionMessage("Le prix d'un exemplaire neuf ne peut pas être modifié.");

        // when
        $usecase->execute($cart->getId(), $stock->getId(), 800);
    }

    /**
     * @throws PropelException
     */
    public function testThrowsWhenStockItemNotInGivenCart(): void
    {
        // given
        $firstCart = $this->_createPointOfSaleCart();
        $secondCart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $firstCart, sellingPrice: 1000, condition: "Bon état");
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // then
        $this->expectException(CannotUpdateStockItemPriceException::class);
        $this->expectExceptionMessage("L'exemplaire n° {$stock->getId()} n'est pas présent dans ce panier.");

        // when
        $usecase->execute($secondCart->getId(), $stock->getId(), 800);
    }

    /**
     * @throws PropelException
     */
    public function testThrowsWhenStockItemNotFound(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // then
        $this->expectException(CannotUpdateStockItemPriceException::class);

        // when
        $usecase->execute($cart->getId(), 99999, 800);
    }

    /**
     * @throws PropelException
     */
    public function testThrowsWhenPriceIsNegative(): void
    {
        // given
        $cart = $this->_createPointOfSaleCart();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1000, condition: "Bon état");
        $usecase = new UpdatePointOfSaleCartItemPriceUsecase();

        // then
        $this->expectException(CannotUpdateStockItemPriceException::class);
        $this->expectExceptionMessage("Le prix ne peut pas être négatif.");

        // when
        $usecase->execute($cart->getId(), $stock->getId(), -1);
    }

    /**
     * @throws PropelException
     */
    private function _createPointOfSaleCart(): Cart
    {
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();

        return $cart;
    }

    /**
     * @throws PropelException
     */
    private function _syncCartTotals(Cart $cart, int $amount): void
    {
        $cart->setCount(1);
        $cart->setAmount($amount);
        $cart->save();
    }
}
