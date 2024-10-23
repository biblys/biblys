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


namespace Model;

use Biblys\Data\ArticleType;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class CartTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testCountPhysicalArticles()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart);

        // when
        $count = $cart->getPhysicalArticleCount();

        // then
        $this->assertEquals(1, $count);
    }

    /**
     * @throws PropelException
     */
    public function testCountDownloadableArticles()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart);

        // when
        $count = $cart->getDownloadableArticleCount();

        // then
        $this->assertEquals(1, $count);
    }

    /**
     * @throws PropelException
     */
    public function testContainsPhysicalArticlesReturnsFalse()
    {
        // given
        $cart = ModelFactory::createCart();
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart);

        // when
        $contains = $cart->containsPhysicalArticles();

        // then
        $this->assertFalse($contains);
    }

    /**
     * @throws PropelException
     */
    public function testContainsPhysicalArticlesReturnsTrue()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart);

        // when
        $contains = $cart->containsPhysicalArticles();

        // then
        $this->assertTrue($contains);
    }

    /**
     * @throws PropelException
     */
    public function testContainsDownloadableArticlesReturnsFalse()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);

        // when
        $contains = $cart->containsDownloadableArticles();

        // then
        $this->assertFalse($contains);
    }

    /**
     * @throws PropelException
     */
    public function testContainsDownloadableArticlesReturnsTrue()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart);

        // when
        $contains = $cart->containsDownloadableArticles();

        // then
        $this->assertTrue($contains);
    }
}
