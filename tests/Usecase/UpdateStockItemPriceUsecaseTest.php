<?php
/*
 * Copyright (C) 2024 Clément Latzarus
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

use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class UpdateStockItemPriceUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testWhenArticlePriceIsNotEditable()
    {
        // given
        $article = ModelFactory::createArticle(price: 500);
        $stockItem = ModelFactory::createStockItem(article: $article, sellingPrice: 1000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $usecase = new UpdateStockItemPriceUsecase($currentUser);

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("Le prix de cet article n'est pas libre.");

        // when
        $usecase->execute($stockItem, newPrice: 500);
    }

    /**
     * @throws PropelException
     */
    public function testWhenNewPriceIsLowerThanArticlePrice()
    {
        // given
        $article = ModelFactory::createArticle(price: 500, isPriceEditable: true);
        $stockItem = ModelFactory::createStockItem(article: $article, sellingPrice: 1000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $usecase = new UpdateStockItemPriceUsecase($currentUser);

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("Le prix doit être supérieur ou égal à 5,00&nbsp;&euro");

        // when
        $usecase->execute($stockItem, newPrice: 400);
    }

    /**
     * @throws PropelException
     */
    public function testWhenStockItemIsNotInUserCart()
    {
        // given
        $article = ModelFactory::createArticle(price: 500, isPriceEditable: true);
        $stockItem = ModelFactory::createStockItem(article: $article, sellingPrice: 1000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("hasStockItemInCart")->andReturn(false);
        $usecase = new UpdateStockItemPriceUsecase($currentUser);

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("Cet exemplaire n'est pas dans votre panier.");

        // when
        $usecase->execute($stockItem, newPrice: 600);
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function testSuccessCase()
    {
        // given
        $cart = ModelFactory::createCart(amount: 1000);
        $article = ModelFactory::createArticle(price: 500, isPriceEditable: true);
        $stockItem = ModelFactory::createStockItem(article: $article, cart: $cart, sellingPrice: 1000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("hasStockItemInCart")->andReturn(true);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $usecase = new UpdateStockItemPriceUsecase($currentUser);

        // when
        $usecase->execute($stockItem, newPrice: 500);

        // then
        $stockItem->reload();
        $this->assertEquals(500, $stockItem->getSellingPrice(), "updates stock selling price");
        $cart->reload();
        $this->assertEquals(500, $cart->getAmount(), "updates cart amount");
        $this->assertEquals(1, $cart->getCount(), "updates cart count");
    }
}
