<?php

namespace AppBundle\Controller;

use Biblys\Test\EntityFactory;
use CartManager;
use PHPUnit\Framework\TestCase;
use StockManager;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class StockControllerTest extends TestCase
{
    public function testEditFreePriceAction()
    {
        

        // given
        $controller = new StockController();
        $article = EntityFactory::createArticle([
            "article_price" => 500,
            "article_price_editable" => 1
        ]);
        $stock = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cart = \Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm = new CartManager();
        $cm->vacuum($cart);
        $cm->addStock($cart, $stock);
        $request = new Request();
        $request->request->set("new_price", 6);
        $request->headers->set("Accept", "application/json");
        $sm = new StockManager();

        // when
        $response = $controller->editFreePriceAction($request, $stock->get("id"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http status 200"
        );
        $this->assertEquals(
            600,
            $sm->getById($stock->get("id"))->get("selling_price"),
            "it should have updated stock selling price"
        );
        $this->assertEquals(
            600,
            $cm->getById($cart->get("id"))->get("amount"),
            "it should have updated cart amount"
        );
    }

    public function testEditFreePriceActionNotInCart()
    {
        // then
        $this->expectException("Symfony\Component\HttpKernel\Exception\BadRequestHttpException");
        $this->expectExceptionMessage(
            "Impossible de modifier un exemplaire qui n'est pas dans votre panier."
        );

        // given
        $controller = new StockController();
        $article = EntityFactory::createArticle([
            "article_price" => 500,
            "article_price_editable" => 1
        ]);
        $stock = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $request = new Request();
        $request->request->set("new_price", 600);
        $sm = new StockManager();

        // when
        $controller->editFreePriceAction($request, $stock->get("id"));
    }

    public function testEditFreePriceActionLegacyUsage()
    {
        

        // given
        $controller = new StockController();
        $article = EntityFactory::createArticle([
            "article_price" => 500,
            "article_price_editable" => 1
        ]);
        $stock = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cart = \Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm = new CartManager();
        $cm->vacuum($cart);
        $cm->addStock($cart, $stock);
        $request = new Request();
        $request->request->set("new_price", 6);
        $sm = new StockManager();

        // when
        $response = $controller->editFreePriceAction($request, $stock->get("id"));

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should respond with http status 302"
        );
        $this->assertEquals(
            600,
            $sm->getById($stock->get("id"))->get("selling_price"),
            "it should have updated stock selling price"
        );
        $this->assertEquals(
            600,
            $cm->getById($cart->get("id"))->get("amount"),
            "it should have updated cart amount"
        );
    }
}