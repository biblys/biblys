<?php

namespace Legacy;

use AppBundle\Controller\LegacyController;
use Biblys\Service\Config;
use Biblys\Service\Mailer;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use CartManager;
use EntityManager;
use Exception;
use Framework\Exception\AuthException;
use OrderManager;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use ShippingManager;
use Site;
use StockManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once __DIR__ . "/../../../setUp.php";


class OrderDeliveryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testValidatingAnOrder()
    {
        global $_SQL, $_V;

        // given
        $cart = $_V->getCart("create");
        $article = EntityFactory::createArticle();
        $sm = new StockManager();
        $sm->create(["article_id" => $article->get("id")]);
        $cm = new CartManager();
        $cm->addArticle($cart, $article);
        $shm = new ShippingManager();
        $shipping = ModelFactory::createShippingFee(["type" => "suivi"]);
        $site = new Site(["site_contact" => "merchant@biblys.fr"]);
        $_POST = ["order_email" => "customer@biblys.fr"];
        $_SITE = $site;

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", 1);
        $request->query->set("shipping_id", $shipping->getId());
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", 1);
        $request->request->set("cgv_checkbox", 1);
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();

        // when
        $response = $legacyController->defaultAction($request, $session, $mailer, $config);

        // then
        $om = new OrderManager();
        $order = $om->get(["order_email" => "customer@biblys.fr"]);
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
        $this->assertEquals(
            "/order/".$order->get("url")."?created=1",
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
        global $_SQL, $_V;

        // given
        $cart = $_V->getCart("create");
        $article = EntityFactory::createArticle([
            "article_title" => "Livre numérique téléchargeable",
            "type_id" => 2
        ]);
        $sm = new StockManager();
        $sm->create(["article_id" => $article->get("id")]);
        $cm = new CartManager();
        $cm->addArticle($cart, $article);
        $site = new Site(["site_contact" => "merchant@biblys.fr"]);
        $_POST = ["order_email" => "e-customer@biblys.fr"];
        $_SITE = $site;

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", 1);
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "e-customer@biblys.fr");
        $request->request->set("country_id", 1);
        $request->request->set("cgv_checkbox", 1);
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();

        // when
        $response = $legacyController->defaultAction($request, $session, $mailer, $config);

        // then
        $om = new OrderManager();
        $order = $om->get(["order_email" => "e-customer@biblys.fr"]);
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response,
            "it should redirect after order validation"
        );
        $this->assertEquals(
            "/order/".$order->get("url")."?created=1",
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
        global $_SQL, $_V;

        // given
        $cart = $_V->getCart("create");
        $article = EntityFactory::createArticle();
        EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cm = new CartManager();
        $shm = new ShippingManager();
        $shipping = $shm->create([]);
        $site = new Site(["site_contact" => "merchant@biblys.fr"]);
        $_POST = ["order_email" => "customer@biblys.fr"];
        $_SITE = $site;

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

        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();

        // when
        $response = $legacyController->defaultAction($request, $session, $mailer, $config);

        // then
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\Response",
            $response,
            "it should answer with a Response"
        );
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
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function testSendingOrderConfirmationMail()
    {
        global $_SQL, $_V;

        // given
        $cart = $_V->getCart("create");
        $article = EntityFactory::createArticle(["article_title" => "Le livre commandé"]);
        $sm = new StockManager();
        $sm->create(["article_id" => $article->get("id")]);
        $cm = new CartManager();
        $cm->addArticle($cart, $article);
        $shipping = ModelFactory::createShippingFee(["type" => "suivi"]);
        $_POST = ["order_email" => "customer@biblys.fr"];

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("page", "order_delivery");
        $request->query->set("country_id", 1);
        $request->query->set("shipping_id", $shipping->getId());
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", 1);
        $request->request->set("cgv_checkbox", 1);
        $session = new Session();
        $config = new Config();

        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->exactly(2))
            ->method("send")
            ->withConsecutive(
                [
                    "customer@biblys.fr",
                    $this->stringContains("YS | Commande n° "),
                    $this->stringContains("Livre commandé")
                ],
                [
                    "contact@biblys.fr",
                    $this->stringContains("YS | Commande n° "),
                    $this->stringContains("Livre commandé")
                ]
            )
            ->willReturn(true);

        // when
        $legacyController = new LegacyController();
        $response = $legacyController->defaultAction($request, $session, $mailer, $config);

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