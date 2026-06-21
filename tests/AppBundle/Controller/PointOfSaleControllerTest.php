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


namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\QueryParamsService;
use Biblys\Test\EntityFactory;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../setUp.php";

class PointOfSaleControllerTest extends TestCase
{
    private function _getController(): PointOfSaleController
    {
        return new PointOfSaleController();
    }

    private function _getCurrentSiteMock(string $tva = ""): CurrentSite
    {
        $site = ModelFactory::createSite();
        $site->setTva($tva);
        $site->save();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getTitle")->andReturn("Éditions Paronymie");
        $currentSite->shouldReceive("getOption")->andReturn(null);
        return $currentSite;
    }

    /**
     * @throws PropelException
     */
    public function testIndexActionReturnsTvaBlockWhenTvaEnabledWithoutBypass(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(tva: "fr"),
            Mockery::mock(ImagesService::class),
            Mockery::mock(FlashMessagesService::class),
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            "La caisse est désactivée, car la gestion de la TVA est activée.",
            $response->getContent()
        );
    }

    /**
     * @throws PropelException
     */
    public function testIndexActionRedirectsWhenTvaBypassIsRequested(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request(query: ['enable_temporary_access' => '1']);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add")->with("info", "La caisse a été réactivée jusqu'à demain.");

        // when
        $response = $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(tva: "fr"),
            Mockery::mock(ImagesService::class),
            $flashMessages,
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/caisse", $response->headers->get("Location"));
        $this->assertNotNull($response->headers->getCookies()[0] ?? null, "should set bypass cookie");
        $this->assertEquals("bypass_cash_register_check", $response->headers->getCookies()[0]->getName());
    }

    /**
     * @throws PropelException
     */
    public function testIndexActionRedirectsToCartWhenNoCartIdProvided(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $user = Mockery::mock(\Model\User::class);
        $user->shouldReceive("getId")->andReturn(999);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        // when
        $response = $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(),
            Mockery::mock(ImagesService::class),
            Mockery::mock(FlashMessagesService::class),
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringStartsWith("/admin/caisse?cart_id=", $response->headers->get("Location"));
    }

    /**
     * @throws PropelException
     */
    public function testIndexActionReturnsPageWithCartId(): void
    {
        // given
        $controller = $this->_getController();
        $cart = EntityFactory::createCart();
        $request = new Request(query: ['cart_id' => $cart->get('id')]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(),
            Mockery::mock(ImagesService::class),
            Mockery::mock(FlashMessagesService::class),
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Caisse", $response->getContent());
    }

    /**
     * @throws PropelException
     */
    public function testIndexActionReturns404WhenCartNotFound(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request(query: ['cart_id' => '99999']);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(),
            Mockery::mock(ImagesService::class),
            Mockery::mock(FlashMessagesService::class),
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );
    }
}
