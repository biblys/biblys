<?php

namespace AppBundle\Controller\Legacy;

use Biblys\Article\Type;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Test\ModelFactory;
use EntityManager;
use Exception;
use Mockery;
use OrderManager;
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
        $shipping = ModelFactory::createShippingFee(["type" => "suivi"]);
        $_POST["order_email"] = "customer@biblys.fr";
        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";
        $country = ModelFactory::createCountry();

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
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer);

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
            "suivi",
            $order->get("shipping_mode"),
            "it should set order's shipping mode"
        );
    }

    /**
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
        $article = ModelFactory::createArticle(typeId: Type::EBOOK);
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $country = ModelFactory::createCountry();

        $_POST["order_email"] = "e-customer@biblys.fr";
        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", $country->getId());
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "e-customer@biblys.fr");
        $request->request->set("country_id", $country->getId());
        $request->request->set("cgv_checkbox", 1);

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->andReturn(true);

        $urlGenerator = $this->createMock(UrlGenerator::class);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("hasOptionEnabled")->andReturn(false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer);

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
     * @throws Exception
     */
    public function testValidatingAnOrderWithAnEmptyCart()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/order_delivery.php";

        $shm = new ShippingManager();
        $shipping = $shm->create();

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

        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer);

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

        $shipping = ModelFactory::createShippingFee(["type" => "suivi"]);
        $_POST = [
            "order_email" => "customer@biblys.fr",
            "order_firstname" => "Barnabé",
            "order_lastname" => "Famagouste"
        ];

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
            ->method("send")
            ->withConsecutive(
                [
                    "customer@biblys.fr",
                    $this->stringContains("PAR | Commande n° "),
                    $this->stringContains("Livre commandé")
                ],
                [
                    "contact@paronymie.fr",
                    $this->stringContains("PAR | Commande n° "),
                    $this->stringContains("Livre commandé")
                ]
            )
            ->willReturn(true);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);

        // when
        $response = $controller($request, $currentSite, $urlGenerator, $currentUser, $mailer);

        // then
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
    }

    public function tearDown(): void
    {
        EntityManager::prepareAndExecute("TRUNCATE TABLE `shipping`", []);
    }
}