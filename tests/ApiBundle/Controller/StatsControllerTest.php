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


namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use DateTime;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use Mockery;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class StatsControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        StockQuery::create()->deleteAll();
    }

    /**
     * @throws Exception
     * @throws InvalidArgument
     * @throws PropelException
     * @throws \Exception
     */
    public function testSalesByArticleAction(): void
    {
        // given
        $controller = new StatsController();

        $article = ModelFactory::createArticle(title: "Sold article");
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime());
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime());

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->with([
            "year" => [
                "type" => "numeric",
                "default" => date("Y"),
                "mb_min_length" => 4,
                "mb_max_length" => 4,
            ]
        ])->andReturn();
        $queryParams->shouldReceive("getInteger")->with("year")->andReturn(date("Y"));

        // when
        $response = $controller->salesByArticleAction($currentUser, $queryParams);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('9781234567890;"Sold article";9.99;2;0;"Vente directe";5,50%;non', $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws InvalidArgument
     * @throws \Exception
     */
    public function testSalesByArticleActionWithYear(): void
    {
        // given
        $controller = new StatsController();

        $article = ModelFactory::createArticle(title: "Article sold in 2022");
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime("2022-05-01"));
        $article = ModelFactory::createArticle(title: "Article sold in 2023");
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime("2023-05-01"));
        ModelFactory::createStockItem(article: $article, sellingPrice: 1234, sellingDate: new DateTime("2023-06-01"));

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->andReturn();
        $queryParams->shouldReceive("getInteger")->with("year")->andReturn(2023);

        // when
        $response = $controller->salesByArticleAction($currentUser, $queryParams);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('9781234567890;"Article sold in 2023";9.99;2;0;"Vente directe";5,50%;non', $response->getContent());
        $this->assertStringNotContainsString("Article sold in 2022", $response->getContent());
    }
}
