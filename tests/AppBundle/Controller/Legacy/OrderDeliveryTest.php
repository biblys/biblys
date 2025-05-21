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

use Biblys\Data\ArticleType;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\QueryParamsService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use EntityManager;
use Mockery;
use Model\OrderQuery;
use Model\StockQuery;
use OrderManager;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use ShippingManager;
use SiteManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";

class OrderDeliveryTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidatingAnOrder()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $site = ModelFactory::createSite();
        $siteManager = new SiteManager();
        $GLOBALS["LEGACY_CURRENT_SITE"] = $siteManager->getById($site->getId());
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $shipping = ModelFactory::createShippingOption(type: "colissimo");
        $_POST["order_email"] = "customer@biblys.fr";
        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";
        $_POST["country_id"] = 1;
        $country = ModelFactory::createCountry();

        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse");
        $queryParamsService->shouldReceive("getInteger")->with("country_id")
            ->andReturn($country->getId());
        $queryParamsService->shouldReceive("getInteger")->with("shipping_id")
            ->andReturn($shipping->getId());

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", 1);
        $request->request->set("cgv_checkbox", 1);

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->andReturn(true);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("getUser")->andReturn(null);

        $config = new Config();

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config);

        // then
        $om = new OrderManager();
        $order = $om->get(["order_email" => "customer@biblys.fr"]);
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
        $this->assertEquals(
            "/order/" . $order->get("url") . "?created=1",
            $response->headers->get("Location"),
            "it should redirect to the correct url"
        );
        $this->assertEquals(
            "colissimo",
            $order->get("shipping_mode"),
            "it should set order's shipping mode"
        );
        $this->assertEquals(
            16,
            strlen($order->get("url")),
            "it should set order's slug"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidatingAnOrderWithoutShipping()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $site = ModelFactory::createSite();
        $siteManager = new SiteManager();
        $GLOBALS["LEGACY_CURRENT_SITE"] = $siteManager->getById($site->getId());
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $country = ModelFactory::createCountry();

        $_POST["order_email"] = "e-customer@biblys.fr";
        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";

        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse");
        $queryParamsService->shouldReceive("getInteger")->with("country_id")
            ->andReturn($country->getId());
        $queryParamsService->shouldReceive("getInteger")->with("shipping_id")
            ->andReturn(0);

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "e-customer@biblys.fr");
        $request->request->set("country_id", $country->getId());
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("downloadable_articles_checkbox", "1");

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->andReturn(true);

        $urlGenerator = $this->createMock(UrlGenerator::class);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);

        $config = new Config();

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config);

        // then
        $om = new OrderManager();
        $order = $om->get(["order_email" => "e-customer@biblys.fr"]);
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
        $this->assertEquals(
            "/order/" . $order->get("url") . "?created=1",
            $response->headers->get("Location"),
            "it should redirect to the correct url"
        );
        $this->assertEquals(
            null,
            $order->get("shipping_mode"),
            "it should set order's shipping mode"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidatingAnOrderWithoutShippingFeeWhenOneIsRequiredRedirectsToCart()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $site = ModelFactory::createSite();
        $siteManager = new SiteManager();
        $GLOBALS["LEGACY_CURRENT_SITE"] = $siteManager->getById($site->getId());
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $country = ModelFactory::createCountry();

        $_POST["order_email"] = "e-customer@biblys.fr";
        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";

        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse");
        $queryParamsService->shouldReceive("getInteger")->with("country_id")
            ->andReturn($country->getId());
        $queryParamsService->shouldReceive("getInteger")->with("shipping_id")
            ->andReturn(0);

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "e-customer@biblys.fr");
        $request->request->set("country_id", $country->getId());
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("downloadable_articles_checkbox", "1");

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->andReturn(true);

        $urlGenerator = $this->createMock(UrlGenerator::class);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);

        $config = new Config();

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config);

        // then
        $om = new OrderManager();
        $order = $om->get(["order_email" => "e-customer@biblys.fr"]);
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
        $this->assertEquals(
            "/pages/cart",
            $response->headers->get("Location"),
            "it should redirect to the correct url"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testValidatingAnOrderWithAnEmptyCart()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $shm = new ShippingManager();
        $shipping = $shm->create();

        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse");

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", 1);
        $request->query->set("shipping_id", $shipping->get("id"));
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", 1);
        $request->request->set("cgv_checkbox", 1);

        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";
        $_POST["order_address1"] = "123 rue des Peupliers";
        $_POST["order_postalcode"] = "69009";
        $_POST["order_city"] = "Lyon";
        $_POST["order_email"] = "customer@biblys.fr";
        $_POST["country_id"] = 1;
        $_POST["cgv_checkbox"] = 1;

        $mailer = Mockery::mock(Mailer::class);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);

        $config = new Config();

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "it should answer with http status 200");
        $this->assertStringContainsString(
            "Votre panier est vide",
            $response->getContent(),
            "it should contain an error message"
        );

        // cleanup
        $shm->delete($shipping);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSendingOrderConfirmationMail()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $site = ModelFactory::createSite();
        $siteManager = new SiteManager();
        $GLOBALS["LEGACY_CURRENT_SITE"] = $siteManager->getById($site->getId());
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(title: "Livre commandé");
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $country = ModelFactory::createCountry();

        $shipping = ModelFactory::createShippingOption(type: "colissimo");
        $_POST = [
            "order_email" => "customer@biblys.fr",
            "order_firstname" => "Barnabé",
            "order_lastname" => "Famagouste"
        ];

        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse");
        $queryParamsService->shouldReceive("getInteger")->with("country_id")
            ->andReturn($country->getId());
        $queryParamsService->shouldReceive("getInteger")->with("shipping_id")
            ->andReturn($shipping->getId());

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", $country->getId());
        $request->query->set("shipping_id", $shipping->getId());
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", $country->getId());
        $request->request->set("cgv_checkbox", 1);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);

        $urlGenerator = $this->createMock(UrlGenerator::class);

        $mailer = $this->createMock(Mailer::class);

        $mailer->expects($this->exactly(2))
            ->method('send')
            ->willReturnCallback(function($email, $subject, $body) {
                $this->assertTrue(
                    $email === 'customer@biblys.fr' || $email === 'contact@paronymie.fr',
                    "Email should be either 'customer@biblys.fr' or 'contact@paronymie.fr'"
                );
                $this->assertStringContainsString('Commande n° ', $subject, "Subject should contain 'Commande n° '");
                $this->assertStringContainsString('Livre commandé', $body, "Body should contain 'Livre commandé'");
                return true;
            });

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);

        $config = new Config();

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config);

        // then
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testOrderCreationIsRevertIfMailerFails()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $site = ModelFactory::createSite();
        $siteManager = new SiteManager();
        $GLOBALS["LEGACY_CURRENT_SITE"] = $siteManager->getById($site->getId());
        $cart = ModelFactory::createCart(site: $site, count: 1);
        $article = ModelFactory::createArticle(title: "Livre commandé");
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $country = ModelFactory::createCountry();

        $shipping = ModelFactory::createShippingOption(type: "colissimo");
        $_POST = [
            "order_email" => "customer@biblys.fr",
            "order_firstname" => "Barnabé",
            "order_lastname" => "Famagouste"
        ];

        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse");
        $queryParamsService->shouldReceive("getInteger")->with("country_id")
            ->andReturn($country->getId());
        $queryParamsService->shouldReceive("getInteger")->with("shipping_id")
            ->andReturn($shipping->getId());

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", $country->getId());
        $request->query->set("shipping_id", $shipping->getId());
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", $country->getId());
        $request->request->set("cgv_checkbox", 1);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);

        $urlGenerator = $this->createMock(UrlGenerator::class);

        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())
            ->method('send')
            ->willThrowException(new \Exception("Mail not sent"));

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);

        $config = new Config();

        // when
        $exception = Helpers::runAndCatchException(function() use ($controller, $request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config) {
            $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer, $queryParamsService, $config);
        });

        // then
        $this->assertEquals("Mail not sent", $exception->getMessage(), "Exception should be thrown");
        $ordersCount = OrderQuery::create()->filterBySiteId($site->getId())->count();
        $this->assertEquals(0, $ordersCount, "Order should not be created");
        $customersCount = OrderQuery::create()->filterBySiteId($site->getId())->count();
        $this->assertEquals(0, $customersCount, "Customer should not be created");
        $stockItemInCart = StockQuery::create()->filterByCart($cart)->exists();
        $this->assertTrue($stockItemInCart, "Stock item should still be in cart");
    }

    public function tearDown(): void
    {
        EntityManager::prepareAndExecute("TRUNCATE TABLE `shipping`", []);
    }
}