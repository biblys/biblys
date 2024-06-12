<?php

namespace Legacy;

use ArticleManager;
use CartManager;
use PHPUnit\Framework\TestCase;
use ShippingManager;
use Site;
use StockManager;
use Symfony\Component\HttpFoundation\Request;
use Visitor;

require_once __DIR__."/../setUp.php";


class OrderDeliveryTest extends TestCase
{
    public function testValidatingAnOrder()
    {
        global $_SQL;

        // given
        $_COOKIE['visitor_uid'] = "abcd1234";
        $_V = new Visitor(["email" => "customer@biblys.fr"]);
        $cm = new CartManager();
        $cart = $cm->create(["cart_uid" => "abcd1234"]);
        $am  = new ArticleManager();
        $article = $am->create(["type_id" => 1]);
        $sm = new StockManager();
        $sm->create(["article_id" => $article->get("id")]);
        $cm->addArticle($cart, $article);
        $shm = new ShippingManager();
        $shipping = $shm->create([]);
        $site = new Site(["site_contact" => "merchant@biblys.fr"]);
        $_POST = ["order_email" => "customer@biblys.fr"];
        $_SITE = $site;

        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->query->set("country_id", 67);
        $request->query->set("shipping_id", $shipping->get("id"));
        $request->request->set("order_firstname", "Barnabé");
        $request->request->set("order_lastname", "Famagouste");
        $request->request->set("order_address1", "123 rue des Peupliers");
        $request->request->set("order_postalcode", "69009");
        $request->request->set("order_city", "Lyon");
        $request->request->set("order_email", "customer@biblys.fr");
        $request->request->set("country_id", 67);
        $request->request->set("cgv_checkbox", 1);

        $_POST["order_firstname"] = "Barnabé";
        $_POST["order_lastname"] = "Famagouste";
        $_POST["order_address1"] = "123 rue des Peupliers";
        $_POST["order_postalcode"] = "69009";
        $_POST["order_city"] = "Lyon";
        $_POST["order_email"] = "customer@biblys.fr";
        $_POST["country_id"] = 67;
        $_POST["cgv_checkbox"] = 1;

        // when
        $response = require_once __DIR__."/../../controllers/common/php/order_delivery.php";

        // then
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
    }
}