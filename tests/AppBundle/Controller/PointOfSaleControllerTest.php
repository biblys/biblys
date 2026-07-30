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

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
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
    public function testIndexActionReturnsPageWithCartIdWhenTvaEnabled(): void
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
            $this->_getCurrentSiteMock(tva: "fr"),
            Mockery::mock(ImagesService::class),
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
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );
    }

    /**
     * @throws PropelException
     */
    public function testAddItemActionAddsStockAndReturnsJsonLine(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(sellingPrice: 1899);

        $request = new Request(request: ['stock_id' => $stock->getId()]);
        $request->headers->set('Accept', 'application/json');

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->shouldReceive("getImageUrlFor")->andReturn(null);

        // when
        $response = $controller->addItemAction(
            $request,
            $currentUser,
            $imagesService,
            new BodyParamsService($request),
            $cart->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('line', $data);
        $this->assertStringContainsString("stock_" . $stock->getId(), $data['line']);
        $stock->reload();
        $this->assertEquals($cart->getId(), $stock->getCartId());
    }

    /**
     * @throws PropelException
     */
    public function testAddItemActionRedirectsWhenNotAcceptingJson(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem();

        $request = new Request(request: ['stock_id' => $stock->getId()]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->addItemAction(
            $request,
            $currentUser,
            Mockery::mock(ImagesService::class),
            new BodyParamsService($request),
            $cart->getId(),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "/admin/caisse?cart_id=" . $cart->getId(),
            $response->headers->get("Location")
        );
    }

    /**
     * @throws PropelException
     */
    public function testAddItemActionReturns404WhenCartNotFound(): void
    {
        // given
        $controller = $this->_getController();
        $stock = ModelFactory::createStockItem();
        $request = new Request(request: ['stock_id' => $stock->getId()]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->addItemAction(
            $request,
            $currentUser,
            Mockery::mock(ImagesService::class),
            new BodyParamsService($request),
            99999,
        );
    }

    /**
     * @throws PropelException
     */
    public function testAddItemActionReturns400WhenStockAlreadyInAnotherPointOfSaleCart(): void
    {
        // given
        $controller = $this->_getController();
        $firstCart = ModelFactory::createCart();
        $firstCart->setType("shop");
        $firstCart->save();
        $secondCart = ModelFactory::createCart();
        $secondCart->setType("shop");
        $secondCart->save();
        $stock = ModelFactory::createStockItem(cart: $firstCart);

        $request = new Request(request: ['stock_id' => $stock->getId()]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);

        // when
        $controller->addItemAction(
            $request,
            $currentUser,
            Mockery::mock(ImagesService::class),
            new BodyParamsService($request),
            $secondCart->getId(),
        );
    }

    /**
     * @throws PropelException
     */
    public function testRemoveItemActionRemovesStockAndReturnsJsonSuccess(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1899);

        $request = new Request();
        $request->headers->set('Accept', 'application/json');

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->removeItemAction(
            $request,
            $currentUser,
            $cart->getId(),
            $stock->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $data);
        $stock->reload();
        $this->assertNull($stock->getCartId());
    }

    /**
     * @throws PropelException
     */
    public function testRemoveItemActionRedirectsWhenNotAcceptingJson(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(cart: $cart);

        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->removeItemAction(
            $request,
            $currentUser,
            $cart->getId(),
            $stock->getId(),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "/admin/caisse?cart_id=" . $cart->getId(),
            $response->headers->get("Location")
        );
    }

    /**
     * @throws PropelException
     */
    public function testRemoveItemActionReturns404WhenCartNotFound(): void
    {
        // given
        $controller = $this->_getController();
        $stock = ModelFactory::createStockItem();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->removeItemAction(
            $request,
            $currentUser,
            99999,
            $stock->getId(),
        );
    }

    /**
     * @throws PropelException
     */
    public function testRemoveItemActionReturns404WhenStockNotInCart(): void
    {
        // given
        $controller = $this->_getController();
        $firstCart = ModelFactory::createCart();
        $firstCart->setType("shop");
        $firstCart->save();
        $secondCart = ModelFactory::createCart();
        $secondCart->setType("shop");
        $secondCart->save();
        $stock = ModelFactory::createStockItem(cart: $firstCart);

        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->removeItemAction(
            $request,
            $currentUser,
            $secondCart->getId(),
            $stock->getId(),
        );
    }

    /**
     * @throws PropelException
     */
    public function testClearCartActionClearsCartAndReturnsJsonSuccess(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1899);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->clearCartAction(
            $currentUser,
            $cart->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $data);
        $stock->reload();
        $this->assertNull($stock->getCartId());
        $cart->reload();
        $this->assertEquals(0, $cart->getCount());
    }

    /**
     * @throws PropelException
     */
    public function testClearCartActionReturns404WhenCartNotFound(): void
    {
        // given
        $controller = $this->_getController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->clearCartAction(
            $currentUser,
            99999,
        );
    }

    /**
     * @throws PropelException
     */
    public function testUpdateCartActionRenamesCartAndReturnsJsonSuccess(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();

        $request = new Request(request: ['title' => 'Nouveau nom']);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->updateCartAction(
            $request,
            $currentUser,
            $cart->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $data);
        $cart->reload();
        $this->assertEquals('Nouveau nom', $cart->getTitle());
    }

    /**
     * @throws PropelException
     */
    public function testUpdateCartActionSetsCustomerAndReturnsJsonSuccess(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $customer = ModelFactory::createCustomer();

        $request = new Request(request: ['customer_id' => $customer->getId()]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->updateCartAction(
            $request,
            $currentUser,
            $cart->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $cart->reload();
        $this->assertEquals($customer->getId(), $cart->getCustomerId());
    }

    /**
     * @throws PropelException
     */
    public function testUpdateCartActionUnsetsCustomerWhenCustomerIdIsEmpty(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $customer = ModelFactory::createCustomer();
        $cart->setCustomerId($customer->getId());
        $cart->save();

        $request = new Request(request: ['customer_id' => '0']);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->updateCartAction(
            $request,
            $currentUser,
            $cart->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $cart->reload();
        $this->assertNull($cart->getCustomerId());
    }

    /**
     * @throws PropelException
     */
    public function testUpdateCartActionReturns404WhenCartNotFound(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request(request: ['title' => 'Nouveau nom']);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->updateCartAction(
            $request,
            $currentUser,
            99999,
        );
    }

    /**
     * @throws PropelException
     */
    public function testUpdateCartActionReturns404WhenCustomerNotFound(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();

        $request = new Request(request: ['customer_id' => '99999']);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->updateCartAction(
            $request,
            $currentUser,
            $cart->getId(),
        );
    }
}
