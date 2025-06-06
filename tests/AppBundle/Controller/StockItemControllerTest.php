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


namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class StockItemControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testNewAction()
    {
        // given
        $controller = new StockItemController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();

        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->newAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testEditFreePriceAction()
    {
        // given
        $controller = new StockItemController();

        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(price: 500, isPriceEditable: true);
        $stock = ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);

        $request = new Request();
        $request->request->set("new_price", 6);
        $request->headers->set("Accept", "application/json");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("hasStockItemInCart")->andReturn(true);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn(null);

        // when
        $response = $controller->editFreePriceAction($request, $currentUser, $currentSite, $stock->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http status 200"
        );
        $stock->reload();
        $this->assertEquals(
            600,
            $stock->getSellingPrice(),
            "it should have updated stock selling price"
        );
        $cart->reload();
        $this->assertEquals(
            600,
            $cart->getAmount(),
            "it should have updated cart amount"
        );
    }

    /** StockItemController->deleteAction */

    /**
     * @throws PropelException
     */
    public function testDeleteAction()
    {
        // given
        $controller = new StockItemController();
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle(title: "Exemplaire à supprimer");
        $stockItem = ModelFactory::createStockItem(site: $site, article: $article);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $imagesService = Mockery::spy(ImagesService::class);
        $imagesService->expects("imageExistsFor")->andReturn(true);
        $flashMessages = Mockery::spy(FlashMessagesService::class);

        // when
        $response = $controller->deleteAction($currentSite, $imagesService, $flashMessages, $stockItem->getId());

        // then
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals("/pages/adm_stocks", $response->getTargetUrl());
        $this->assertTrue($stockItem->isDeleted(), "should have deleted the stock item");
        $imagesService->shouldHaveReceived("deleteImageFor", [$stockItem]);
        $flashMessages->shouldHaveReceived("add", [
            "success",
            "L'exemplaire n° ".$stockItem->getId()." (Exemplaire à supprimer) a été supprimé."
        ]);
    }

    /**
     * #StockItemController->cancelLostAction
     */


    /**
     * @throws PropelException
     */
    public function testCancelLostAction()
    {
        // given
        $controller = new StockItemController();
        $site = ModelFactory::createSite();
        $stockItem = ModelFactory::createStockItem(site: $site, lostDate: new DateTime());

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);

        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add")->with(
            "success",
            "L'exemplaire n° ".$stockItem->getId()." a été marqué comme retrouvé."
        );


        // when
        $response = $controller->cancelLostAction($currentUser, $currentSite, $flashMessages, $stockItem->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/pages/adm_stock?id={$stockItem->getId()}", $response->getTargetUrl());
        $this->assertFalse($stockItem->isLost(), "should have canceled the lost status");
    }
}
