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
use Biblys\Service\FlashMessagesService;
use Biblys\Service\QueryParamsService;
use Biblys\Test\EntityFactory;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\Cart;
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
    public function testIndexActionRendersCartLineForStockItemInCart(): void
    {
        // given
        $controller = $this->_getController();
        $cart = EntityFactory::createCart();
        $article = ModelFactory::createArticle(title: "Le Horla", url: "maupassant/le-horla");
        $stockItem = ModelFactory::createStockItem(article: $article, sellingPrice: 1899);
        $stockItem->setCartId($cart->get("id"));
        $stockItem->save();
        $request = new Request(query: ['cart_id' => $cart->get('id')]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(),
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );

        // then
        $content = $response->getContent();
        $this->assertStringContainsString("stock_{$stockItem->getId()}", $content);
        $this->assertStringContainsString("Le Horla", $content);
        $this->assertStringContainsString('data-price="1899"', $content);
    }

    /**
     * @throws PropelException
     */
    public function testIndexActionRendersFallbackTitleForOtherCartWithoutTitle(): void
    {
        // given
        $controller = $this->_getController();
        $cart = EntityFactory::createCart();
        $request = new Request(query: ['cart_id' => $cart->get('id')]);

        $article = ModelFactory::createArticle(title: "Le Horla", url: "maupassant/le-horla");
        $stockItem = ModelFactory::createStockItem(article: $article, sellingPrice: 1899);
        $otherCart = new Cart();
        $otherCart->setType('shop');
        $otherCart->setCount(1);
        $otherCart->setAmount(1899);
        $otherCart->save();
        $stockItem->setCartId($otherCart->getId());
        $stockItem->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->indexAction(
            $request,
            $currentUser,
            $this->_getCurrentSiteMock(),
            Helpers::getTemplateService(),
            Mockery::mock(UrlGenerator::class),
            new QueryParamsService($request),
        );

        // then
        $content = $response->getContent();
        $this->assertStringContainsString("Panier n° {$otherCart->getId()}", $content);
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

        // when
        $response = $controller->addItemAction(
            $request,
            $currentUser,
            Helpers::getTemplateService(),
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
     * Un exemplaire sans prix de vente doit rendre data-price="0" et non un
     * attribut vide : le JS de la caisse applique parseInt sur cet attribut
     * pour calculer le total du panier, et un attribut vide produirait NaN.
     *
     * @throws PropelException
     */
    public function testAddItemActionReturnsLineWithZeroDataPriceForFreeStockItem(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem();
        $stock->setSellingPrice(null);
        $stock->save();

        $request = new Request(request: ['stock_id' => $stock->getId()]);
        $request->headers->set('Accept', 'application/json');

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->addItemAction(
            $request,
            $currentUser,
            Helpers::getTemplateService(),
            new BodyParamsService($request),
            $cart->getId(),
        );

        // then
        $data = json_decode($response->getContent(), true);
        $this->assertStringContainsString(
            'data-price="0"',
            $data['line'],
            "Un exemplaire gratuit doit avoir data-price=\"0\" et non un attribut vide, sinon le total calculé côté JS devient NaN"
        );
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
            Helpers::getTemplateService(),
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
            Helpers::getTemplateService(),
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
            Helpers::getTemplateService(),
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

    /**
     * @throws PropelException
     */
    public function testCreateSaleActionCreatesOrderAndReturnsJson(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $stock = ModelFactory::createStockItem(cart: $cart, sellingPrice: 1899);

        $request = new Request(request: [
            'cart_cash' => '1899',
            'cart_topay' => '0',
            'cart_togive' => '0',
        ]);
        $request->headers->set('Accept', 'application/json');

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")->with("success", "La vente a été enregistrée.");

        // when
        $response = $controller->createSaleAction(
            $request,
            $currentUser,
            $flashMessagesService,
            $cart->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('order_id', $data);
        $stock->reload();
        $this->assertEquals($data['order_id'], $stock->getOrderId());
    }

    /**
     * @throws PropelException
     */
    public function testCreateSaleActionRedirectsWhenNotAcceptingJson(): void
    {
        // given
        $controller = $this->_getController();
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();

        $request = new Request(request: [
            'cart_cash' => '0',
            'cart_topay' => '0',
            'cart_togive' => '0',
        ]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")->with("success", "La vente a été enregistrée.");

        // when
        $response = $controller->createSaleAction(
            $request,
            $currentUser,
            $flashMessagesService,
            $cart->getId(),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/caisse", $response->headers->get("Location"));
    }

    /**
     * @throws PropelException
     */
    public function testCreateSaleActionReturns404WhenCartNotFound(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // when
        $controller->createSaleAction(
            $request,
            $currentUser,
            Mockery::mock(FlashMessagesService::class),
            99999,
        );
    }

    /**
     * @throws PropelException
     */
    public function testLegacyRedirectActionDisplaysAddressChangedNoticeWithLinkToNewUrl(): void
    {
        // given
        $controller = $this->_getController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("point_of_sale_index")
            ->andReturn("/admin/caisse");

        // when
        $response = $controller->legacyRedirectAction(
            $request,
            $currentUser,
            $urlGenerator,
            Helpers::getTemplateService(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("a changé d'adresse", $response->getContent());
        $this->assertStringContainsString('href="/admin/caisse"', $response->getContent());
    }

    /**
     * @throws PropelException
     */
    public function testLegacyRedirectActionPreservesCartIdQueryParamInLink(): void
    {
        // given
        $controller = $this->_getController();
        $cart = EntityFactory::createCart();
        $request = new Request(query: ["cart_id" => $cart->get("id")]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("point_of_sale_index")
            ->andReturn("/admin/caisse");

        // when
        $response = $controller->legacyRedirectAction(
            $request,
            $currentUser,
            $urlGenerator,
            Helpers::getTemplateService(),
        );

        // then
        $this->assertStringContainsString(
            'href="/admin/caisse?cart_id=' . $cart->get("id") . '"',
            $response->getContent()
        );
    }
}
