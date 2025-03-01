<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Data\ArticleType;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class AddIntangibleArticleToCartUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        StockQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testUsecaseFailsIfArticleIsPhysical()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(title: "Livre papier");
        $cart = ModelFactory::createCart();

        // when
        $exception = Helpers::runAndCatchException(fn() => $usecase->execute($article, $cart));

        // when
        $this->assertInstanceOf(BusinessRuleException::class, $exception);
        $this->assertEquals(
            "L'article Livre papier n'a pas pu être ajouté au panier car il n'est pas intangible.",
            $exception->getMessage()
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testUsecaseFailsIfArticleIsSoldOut()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK, availabilityDilicom: 6);
        $cart = ModelFactory::createCart();

        // when
        $exception = Helpers::runAndCatchException(fn() => $usecase->execute($article, $cart));

        // then
        $this->assertInstanceOf(BusinessRuleException::class, $exception);
        $this->assertEquals(
            "L'article {$article->getTitle()} n'a pas pu être ajouté au panier car il est épuisé.",
            $exception->getMessage()
        );
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function testUsecaseCreatesAndAddStockItemForDownloadableArticleToCart()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(price: 1000, typeId: ArticleType::EBOOK);
        $cart = ModelFactory::createCart();

        // when
        $usecase->execute($article, $cart);

        // then
        $itemInCart = StockQuery::create()->filterByCart($cart)->findOne();
        $this->assertNotNull($itemInCart);
        $this->assertEquals($article, $itemInCart->getArticle());
        $this->assertEquals(1000, $itemInCart->getSellingPrice());
        $this->assertEquals(1, $cart->getCount());
        $this->assertEquals(1000, $cart->getAmount());
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function testUsecaseCreatesAndAddStockItemForServiceArticleToCart()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(price: 1000, typeId: ArticleType::SUBSCRIPTION);
        $cart = ModelFactory::createCart();

        // when
        $usecase->execute($article, $cart);

        // then
        $itemInCart = StockQuery::create()->filterByCart($cart)->findOne();
        $this->assertNotNull($itemInCart);
        $this->assertEquals($article, $itemInCart->getArticle());
        $this->assertEquals(1000, $itemInCart->getSellingPrice());
        $this->assertEquals(1, $cart->getCount());
        $this->assertEquals(1000, $cart->getAmount());
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function testUsecaseAddsExistingStockItemToCart()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        $item = ModelFactory::createStockItem(article: $article, sellingPrice: 1000);
        $cart = ModelFactory::createCart();

        // when
        $usecase->execute($article, $cart);

        // then
        $this->assertEquals($item->getCart(), $cart);
        $this->assertNotNull($item->getCartDate());
        $this->assertEquals(1, $cart->getCount());
        $this->assertEquals(1000, $cart->getAmount());
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function testUsecaseAddsPreorderableArticleToCart()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(
            title: "À paraître",
            price: 1000,
            typeId: ArticleType::EBOOK,
            publicationDate: new DateTime("tomorrow"),
            isPreorderable: true,
        );
        $cart = ModelFactory::createCart();

        // when
        $usecase->execute($article, $cart);

        // then
        $itemInCart = StockQuery::create()->filterByCart($cart)->filterByArticle($article)->findOne();
        $this->assertNotNull($itemInCart);
        $this->assertEquals(1, $cart->getCount());
        $this->assertEquals(1000, $cart->getAmount());
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function testUsecaseIgnoresSoldItem()
    {
        // given
        $usecase = new AddIntangibleArticleToCartUsecase();

        $article = ModelFactory::createArticle(price: 1000, typeId: ArticleType::EBOOK);
        $soldItem = ModelFactory::createStockItem(article: $article, sellingDate: new DateTime());
        $cart = ModelFactory::createCart();

        // when
        $usecase->execute($article, $cart);

        // then
        $soldItem->reload();
        $this->assertNull($soldItem->getCart());
        $this->assertNull($soldItem->getCartDate());
        $this->assertEquals(1, $cart->getCount());
        $this->assertEquals(1000, $cart->getAmount());
    }
}
