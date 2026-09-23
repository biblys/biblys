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

namespace Repository;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Model\ArticleQuery;
use Model\Stock;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class StockRepositoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        StockQuery::create()->deleteAll();
        ArticleQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableItemsForReturnsOnlyAvailableCopies(): void
    {
        // given
        $article = ModelFactory::createArticle();
        $available = ModelFactory::createStockItem(article: $article, sellingPrice: 500);
        ModelFactory::createStockItem(article: $article, sellingPrice: 500, sellingDate: new \DateTime());
        ModelFactory::createStockItem(article: $article, sellingPrice: 500, returnDate: new \DateTime());
        ModelFactory::createStockItem(article: $article, sellingPrice: 500, lostDate: new \DateTime());
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableItemsFor($article, $currentSite);

        // then
        $this->assertCount(1, $items);
        $this->assertEquals($available->getId(), $items[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableItemsForReturnsEmptyArrayWhenNoStock(): void
    {
        // given
        $article = ModelFactory::createArticle();
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableItemsFor($article, $currentSite);

        // then
        $this->assertEquals([], $items);
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableItemsForExcludesCopiesOutsideActiveStock(): void
    {
        // given
        $article = ModelFactory::createArticle();
        $inActiveStock = ModelFactory::createStockItem(article: $article, sellingPrice: 500, stockage: "A1");
        ModelFactory::createStockItem(article: $article, sellingPrice: 500, stockage: "B2");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("active_stock")->willReturn("A1,C3");
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableItemsFor($article, $currentSite);

        // then
        $this->assertCount(1, $items);
        $this->assertEquals($inActiveStock->getId(), $items[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableItemsForIncludesAllCopiesWhenActiveStockIsNotSet(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $article, sellingPrice: 500, stockage: "A1");
        ModelFactory::createStockItem(article: $article, sellingPrice: 500, stockage: "B2");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("active_stock")->willReturn(null);
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableItemsFor($article, $currentSite);

        // then
        $this->assertCount(2, $items);
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableUsedItemsForReturnsOnlyUsedAvailableCopiesSortedByPrice(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $article, condition: Stock::CONDITION_NEW, sellingPrice: 500);
        $cheap = ModelFactory::createStockItem(article: $article, condition: "Bon état", sellingPrice: 300);
        $expensive = ModelFactory::createStockItem(article: $article, condition: "Très bon état", sellingPrice: 800);
        ModelFactory::createStockItem(article: $article, condition: "Bon état", sellingPrice: 400, sellingDate: new \DateTime());
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableUsedItemsFor($article, $currentSite);

        // then
        $this->assertCount(2, $items);
        $this->assertEquals($cheap->getId(), $items[0]->getId());
        $this->assertEquals($expensive->getId(), $items[1]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableUsedItemsForIncludesOtherEditionsSharingTheSameItem(): void
    {
        // given
        $paperback = ModelFactory::createArticle(item: 42);
        $hardcover = ModelFactory::createArticle(item: 42, url: "author/article-relie");
        $unrelated = ModelFactory::createArticle(item: 43, url: "author/autre-livre");
        $sameItem = ModelFactory::createStockItem(article: $hardcover, condition: "Bon état", sellingPrice: 600);
        ModelFactory::createStockItem(article: $unrelated, condition: "Bon état", sellingPrice: 100);
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableUsedItemsFor($paperback, $currentSite);

        // then
        $this->assertCount(1, $items);
        $this->assertEquals($sameItem->getId(), $items[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testGetAvailableUsedItemsForExcludesCopiesOutsideActiveStock(): void
    {
        // given
        $article = ModelFactory::createArticle();
        $inActiveStock = ModelFactory::createStockItem(article: $article, condition: "Bon état", sellingPrice: 300, stockage: "A1");
        ModelFactory::createStockItem(article: $article, condition: "Bon état", sellingPrice: 300, stockage: "B2");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("active_stock")->willReturn("A1,C3");
        $repository = new StockRepository();

        // when
        $items = $repository->getAvailableUsedItemsFor($article, $currentSite);

        // then
        $this->assertCount(1, $items);
        $this->assertEquals($inActiveStock->getId(), $items[0]->getId());
    }
}
