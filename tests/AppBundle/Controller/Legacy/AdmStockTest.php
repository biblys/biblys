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


namespace AppBundle\Controller\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\Map\StockTableMap;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";

class AdmStockTest extends TestCase
{
    private function getController(): callable
    {
        static $controller = null;
        if ($controller === null) {
            $controller = require __DIR__ . "/../../../../controllers/common/php/adm_stock.php";
        }
        return $controller;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $_GET = [];
        $_POST = [];
    }

    private function buildSession(): object
    {
        $flashBag = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface")
            ->getMock();
        $flashBag->method("add")->willReturn(null);
        $session = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Session")
            ->getMock();
        $session->method("getFlashBag")->willReturn($flashBag);
        return $session;
    }

    private function buildCurrentSite(): CurrentSite
    {
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);
        $currentSite->shouldReceive("getTitle")->andReturn("Libraire Test");
        return $currentSite;
    }

    private function buildImagesService(bool $imageExists = false): ImagesService
    {
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->shouldReceive("imageExistsFor")->andReturn($imageExists);
        return $imagesService;
    }

    /**
     * @throws PropelException
     */
    public function testReturnActionRedirects(): void
    {
        // given
        $controller = $this->getController();
        ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        $stockItem = ModelFactory::createStockItem(article: $article);

        $request = new Request();
        $request->query->set("return", $stockItem->getId());
        $_GET["return"] = $stockItem->getId();

        // when
        $response = $controller(
            $request,
            $this->buildSession(),
            $this->buildCurrentSite(),
            Mockery::mock(Mailer::class),
            Mockery::mock(UrlGenerator::class),
            Mockery::mock(FlashMessagesService::class),
            $this->buildImagesService(),
            Mockery::mock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode(), "should redirect");
        $this->assertEquals(
            "/pages/adm_stock?id={$stockItem->getId()}&returned=1",
            $response->getTargetUrl(),
            "should redirect to the stock item page with returned=1"
        );
        $stockItem->reload();
        $this->assertNotNull(
            $stockItem->getReturnDate(),
            "should have marked the stock item as returned"
        );
    }

    /**
     * @throws PropelException
     */
    public function testLostActionMarksStockAsLostAndRedirects(): void
    {
        // given
        $controller = $this->getController();
        ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        $stockItem = ModelFactory::createStockItem(article: $article);

        $request = new Request();
        $request->query->set("lost", $stockItem->getId());

        $flashBag = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface")
            ->getMock();
        $flashBag->expects($this->once())->method("add")->with(
            "success",
            "L'exemplaire n° {$stockItem->getId()} a été marqué comme perdu."
        );
        $session = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Session")
            ->getMock();
        $session->method("getFlashBag")->willReturn($flashBag);

        // when
        $response = $controller(
            $request,
            $session,
            $this->buildCurrentSite(),
            Mockery::mock(Mailer::class),
            Mockery::mock(UrlGenerator::class),
            Mockery::mock(FlashMessagesService::class),
            $this->buildImagesService(),
            Mockery::mock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode(), "should redirect");
        $this->assertEquals(
            "/pages/adm_stock?id={$stockItem->getId()}",
            $response->getTargetUrl(),
            "should redirect to the stock item page"
        );
        $stockItem->reload();
        $this->assertNotNull(
            $stockItem->getLostDate(),
            "should have marked the stock item as lost"
        );
    }

    /**
     * @throws PropelException
     */
    public function testDisplayAddFormShowsArticle(): void
    {
        // given
        $controller = $this->getController();
        ModelFactory::createSite();
        $article = ModelFactory::createArticle(title: "Le livre à ajouter");

        $_GET["add"] = $article->getId();
        $_GET["id"] = 0;
        $request = new Request();
        $request->query->set("add", $article->getId());

        // when
        $response = $controller(
            $request,
            $this->buildSession(),
            $this->buildCurrentSite(),
            Mockery::mock(Mailer::class),
            Mockery::mock(UrlGenerator::class),
            Mockery::mock(FlashMessagesService::class),
            $this->buildImagesService(),
            Mockery::mock(TemplateService::class),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode(), "should respond with HTTP 200");
        $this->assertStringContainsString(
            "Le livre à ajouter",
            $response->getContent(),
            "should display the article title"
        );
        $this->assertStringContainsString(
            "Ajouter au stock",
            $response->getContent(),
            "should display the add to stock button"
        );
    }

    /**
     * @throws PropelException
     */
    public function testDisplayEditFormShowsStockItem(): void
    {
        // given
        $controller = $this->getController();
        ModelFactory::createSite();
        $article = ModelFactory::createArticle(title: "Le livre à modifier");
        $stockItem = ModelFactory::createStockItem(article: $article);

        $_GET["id"] = $stockItem->getId();
        $request = new Request();
        $request->query->set("id", $stockItem->getId());

        // when
        $response = $controller(
            $request,
            $this->buildSession(),
            $this->buildCurrentSite(),
            Mockery::mock(Mailer::class),
            Mockery::mock(UrlGenerator::class),
            Mockery::mock(FlashMessagesService::class),
            $this->buildImagesService(),
            Mockery::mock(TemplateService::class),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode(), "should respond with HTTP 200");
        $this->assertStringContainsString(
            "Le livre à modifier",
            $response->getContent(),
            "should display the article title"
        );
        $this->assertStringContainsString(
            "Enregistrer les modifications",
            $response->getContent(),
            "should display the save button"
        );
    }

    private function findStockItemsForArticle(int $articleId): ObjectCollection
    {
        StockTableMap::clearInstancePool();
        return StockQuery::create()->filterByArticleId($articleId)->orderById()->find();
    }

    private function callControllerWithPost(array $postData, FlashMessagesService $flashMessagesService): mixed
    {
        $_POST = $postData;
        $request = Request::create("/pages/adm_stock", "POST", $postData);

        return $this->getController()(
            $request,
            $this->buildSession(),
            $this->buildCurrentSite(),
            Mockery::mock(Mailer::class),
            Mockery::mock(UrlGenerator::class),
            $flashMessagesService,
            $this->buildImagesService(),
            Mockery::mock(TemplateService::class),
        );
    }

    /**
     * @throws PropelException
     */
    public function testPostCreatesOneStockItem(): void
    {
        // given
        ModelFactory::createSite();
        $article = ModelFactory::createArticle(title: "Le livre à stocker");
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->shouldReceive("add")->once()->with(
            "success",
            "Un exemplaire de Le livre à stocker a été ajouté au stock."
        );

        // when
        $response = $this->callControllerWithPost([
            "article_id" => $article->getId(),
            "stock_num" => 1,
            "stock_condition" => "Neuf",
            "stock_selling_price" => 1500,
            "stock_stockage" => "Étagère B",
        ], $flashMessagesService);

        // then
        $stockItems = $this->findStockItemsForArticle($article->getId());
        $this->assertCount(1, $stockItems, "should have created one stock item");
        $stockItem = $stockItems->getFirst();
        $this->assertEquals(1500, $stockItem->getSellingPrice(), "should save the selling price");
        $this->assertEquals("Neuf", $stockItem->getCondition(), "should save the condition");
        $this->assertEquals("Étagère B", $stockItem->getStockage(), "should save the stockage");
        $this->assertEquals(302, $response->getStatusCode(), "should redirect");
        $this->assertEquals(
            "/pages/adm_stock?id={$stockItem->getId()}",
            $response->getTargetUrl(),
            "should redirect to the new stock item page"
        );
    }

    /**
     * @throws PropelException
     */
    public function testPostCreatesSeveralStockItems(): void
    {
        // given
        ModelFactory::createSite();
        $article = ModelFactory::createArticle(title: "Le livre en série");
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->shouldReceive("add")->once()->with(
            "success",
            "3 exemplaires de Le livre en série ont été ajoutés au stock."
        );

        // when
        $response = $this->callControllerWithPost([
            "article_id" => $article->getId(),
            "stock_num" => 3,
            "stock_condition" => "Neuf",
            "stock_selling_price" => 900,
        ], $flashMessagesService);

        // then
        $stockItems = $this->findStockItemsForArticle($article->getId());
        $this->assertCount(3, $stockItems, "should have created three stock items");
        foreach ($stockItems as $stockItem) {
            $this->assertEquals(900, $stockItem->getSellingPrice(), "should save the price on each item");
        }
        $this->assertEquals(
            "/pages/adm_stock?id={$stockItems->getLast()->getId()}",
            $response->getTargetUrl(),
            "should redirect to the last created stock item"
        );
    }

    /**
     * @throws PropelException
     */
    public function testPostUpdatesExistingStockItem(): void
    {
        // given
        ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        $stockItem = ModelFactory::createStockItem(article: $article, sellingPrice: 1000);
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->shouldReceive("add")->once()->with(
            "success",
            "L'exemplaire n° {$stockItem->getId()} a été mis à jour."
        );

        // when
        $response = $this->callControllerWithPost([
            "article_id" => $article->getId(),
            "stock_id" => $stockItem->getId(),
            "stock_num" => 1,
            "stock_condition" => "Occasion",
            "stock_selling_price" => 450,
        ], $flashMessagesService);

        // then
        $stockItem->reload();
        $this->assertEquals(450, $stockItem->getSellingPrice(), "should update the selling price");
        $this->assertEquals("Occasion", $stockItem->getCondition(), "should update the condition");
        $this->assertCount(
            1,
            $this->findStockItemsForArticle($article->getId()),
            "should not have created another stock item"
        );
        $this->assertEquals(
            "/pages/adm_stock?id={$stockItem->getId()}",
            $response->getTargetUrl(),
            "should redirect to the stock item page"
        );
    }

    public function testPostThrowsWhenArticleDoesNotExist(): void
    {
        // given
        ModelFactory::createSite();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Cannot find article with id 999999");

        // when
        $this->callControllerWithPost([
            "article_id" => 999999,
            "stock_num" => 1,
        ], Mockery::mock(FlashMessagesService::class));
    }
}
