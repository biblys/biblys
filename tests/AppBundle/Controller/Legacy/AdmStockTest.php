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
use Mockery;
use PHPUnit\Framework\TestCase;
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
}
