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


/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

namespace AppBundle\Controller;

use Biblys\Data\ArticleType;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class CartControllerTest extends TestCase
{
    /** addArticle */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddArticleWithJsonResponse()
    {

        // given
        $controller = new CartController();

        $request = new Request();
        $request->headers->set("Accept", "application/json");

        $article = ModelFactory::createArticle();
        $site = ModelFactory::createSite();
        ModelFactory::createSiteOption(site: $site, key: "virtual_stock", value: 1);
        LegacyCodeHelper::setGlobalSite($site);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        // when
        $response = $controller->addArticleAction($request, $currentUser ,$article->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "responds with http status 200"
        );
        $this->assertEquals(
            "application/json",
            $response->headers->get("Content-Type"),
            "responds with json"
        );
        $stockItemInCart = StockQuery::create()
            ->filterByArticle($article)
            ->filterByCart($cart)
            ->findOne();
        $this->assertNotNull(
            $stockItemInCart,
            "it should have added article to cart"
        );
        $cart->reload();
        $this->assertEquals(
            1,
            $cart->getCount(),
            "it should have updated cart article count"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddArticleWithRedirection()
    {
        // given
        $controller = new CartController();

        $request = new Request();
        $request->headers->set("Accept", "text/html");

        $article = ModelFactory::createArticle();
        $site = ModelFactory::createSite();
        ModelFactory::createSiteOption(site: $site, key: "virtual_stock", value: 1);
        LegacyCodeHelper::setGlobalSite($site);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        // when
        $response = $controller->addArticleAction($request, $currentUser, $article->getId());

        // then
        $this->assertTrue(
            $response->isRedirection(),
            "responds with HTTP status 301"
        );
        $this->assertTrue(
            $response->isRedirect("/pages/cart"),
            "redirects to cart page"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddArticleForDownloadableArticle()
    {
        // given
        $controller = new CartController();

        $article = ModelFactory::createArticle(price: 499, typeId: ArticleType::EBOOK);
        $cart = ModelFactory::createCart();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        // when
        $response = $controller->addArticleAction($request, $currentUser, $article->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(1, $cart->getCount());
        $this->assertEquals(499, $cart->getAmount());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddArticleForServiceArticle()
    {
        // given
        $controller = new CartController();

        $article = ModelFactory::createArticle(price: 599, typeId: ArticleType::SUBSCRIPTION);
        $site = ModelFactory::createSite();
        ModelFactory::createSiteOption(site: $site, key: "virtual_stock", value: 1);
        LegacyCodeHelper::setGlobalSite($site);
        $cart = ModelFactory::createCart(site: $site);
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        // when
        $response = $controller->addArticleAction($request, $currentUser, $article->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
    }

    /** addStock */

    /**
     * @throws Exception
     */
    public function testAddStockCopy()
    {
        // given
        $controller = new CartController();

        $site = ModelFactory::createSite();
        $stock = ModelFactory::createStockItem(site: $site);
        LegacyCodeHelper::setGlobalSite($site);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        // when
        $response = $controller->addStockAction($currentUser, $stock->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $stock->reload();
        $this->assertEquals(
            $cart->getId(),
            $stock->getCartId(),
            "it should have added article to cart"
        );
        $cart->reload();
        $this->assertEquals(
            1,
            $cart->getCount(),
            "it should have updated cart article count"
        );
    }

    /** addCrowdfundingReward */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddCrowdfundingReward()
    {
        // given
        $controller = new CartController();

        $site = ModelFactory::createSite();
        LegacyCodeHelper::setGlobalSite($site);
        ModelFactory::createSiteOption(site: $site, key: "virtual_stock", value: 1);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        $reward = ModelFactory::createCrowdfundingReward(["site_id" => $site->getId()]);

        // when
        $response = $controller->addCrowdfundingRewardAction($currentUser, $reward->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $stockItem = StockQuery::create()
            ->filterByRewardId($reward->getId())
            ->filterByCart($cart)
            ->findOne();
        $this->assertNotNull(
            $stockItem,
            "it should have added article to cart"
        );
        $cart->reload();
        $this->assertEquals(
            1,
            $cart->getCount(),
            "it should have updated cart article count"
        );
    }

    /** removeStock */

    /**
     * @throws Exception
     */
    public function testRemoveStock()
    {
        // given
        $controller = new CartController();

        $site = ModelFactory::createSite();
        LegacyCodeHelper::setGlobalSite($site);
        ModelFactory::createSiteOption(site: $site, key: "virtual_stock", value: 1);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        $stockItem = ModelFactory::createStockItem(site: $site, cart: $cart);

        $request = new Request();
        $request->headers->set("Accept", "application/json");

        // when
        $response = $controller->removeStockAction(
            $request,
            $currentUser,
            $stockItem->getId()
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $stockItem->reload();
        $this->assertNull(
            $stockItem->getCart(),
            "it should have removed stock from cart"
        );
        $cart->reload();
        $this->assertEquals(
            0,
            $cart->getCount(),
            "it should have updated cart article count"
        );
    }

    /**
     * @throws Exception
     */
    public function testRemoveStockLegacyUsage()
    {
        // given
        $controller = new CartController();

        $site = ModelFactory::createSite();
        LegacyCodeHelper::setGlobalSite($site);
        ModelFactory::createSiteOption(site: $site, key: "virtual_stock", value: 1);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);

        $stockItem = ModelFactory::createStockItem(site: $site, cart: $cart);

        $request = new Request();
        $request->headers->set("Accept", "text/html");

        // when
        $response = $controller->removeStockAction(
            $request,
            $currentUser,
            $stockItem->getId(),
        );

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should respond with http 302"
        );
        $this->assertEquals(
            "/pages/cart?removed=1",
            $response->headers->get("Location"),
            "it should redirect to cart page"
        );
        $stockItem->reload();
        $this->assertNull(
            $stockItem->getCart(),
            "it should have removed stock from cart"
        );
        $cart->reload();
        $this->assertEquals(
            0,
            $cart->getCount(),
            "it should have updated cart article count"
        );
    }

    /** getSummary */

    /**
     * @throws Exception
     */
    public function testGetSummaryWhenCartIsEmpty()
    {
        // given
        $site = ModelFactory::createSite();
        LegacyCodeHelper::setGlobalSite($site);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);

        $controller = new CartController();

        // when
        $response = $controller->summaryAction($currentUser);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with HTTP 200"
        );
        $this->assertEquals(
            '<a
                href="/pages/cart"
                rel="nofollow"
                class="btn btn-outline-secondary btn-sm empty"><span class="fa fa-shopping-cart"></span> Panier vide</a>',
            json_decode($response->getContent())->summary,
            "it should return cart summary"
        );
    }

    /**
     * @throws Exception
     */
    public function testGetSummaryWhenCartIsFull()
    {
        // given
        $controller = new CartController();

        $site = ModelFactory::createSite();
        LegacyCodeHelper::setGlobalSite($site);
        $cart = ModelFactory::createCart(site: $site, amount: 500, count: 1);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);

        // when
        $response = $controller->summaryAction($currentUser);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with HTTP 200"
        );
        $this->assertEquals(
            '<a
                href="/pages/cart"
                rel="nofollow"
                class="btn btn-outline-secondary btn-sm not-empty"><span class="fa fa-shopping-cart"></span> 1 article (5,00&nbsp;&euro;)</a>',
            json_decode($response->getContent())->summary,
            "it should return cart summary"
        );
    }
}
