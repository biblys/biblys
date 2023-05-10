<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\CartController;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\EntityFactory;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class CartControllerTest extends PHPUnit\Framework\TestCase
{
    public function testAddArticle()
    {
        global $_SITE;

        // given
        $cm = new CartManager();
        $_SITE->setOpt("virtual_stock", 1);
        $controller = new CartController();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");

        $cm->vacuum($cart);
        $article = EntityFactory::createArticle();

        // when
        $response = $controller->addArticleAction(
            $article->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertTrue(
            $cart->containsArticle($article),
            "it should have added article to cart"
        );
        $updatedCart = $cm->getById($cart->get("id"));
        $this->assertEquals(
            1,
            $updatedCart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testAddArticleNotYetAvailable()
    {
        global $_SITE;

        $this->expectException("Symfony\Component\HttpKernel\Exception\ConflictHttpException");
        $this->expectExceptionMessage(
            "L'article <a href=\"/\">L'Animalie</a> n'a pas pu être ajouté au panier car il n'est pas encore disponible."
        );

        // given
        $cm = new CartManager();
        $_SITE->setOpt("virtual_stock", 1);
        $controller = new CartController();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->vacuum($cart);
        $tomorrow = new DateTime('tomorrow');
        $article = EntityFactory::createArticle(["article_pubdate" => $tomorrow->format("Y-m-d")]);

        // when
        $response = $controller->addArticleAction(
            $article->get("id")
        );

        // then
        $this->assertEquals(
            409,
            $response->getStatusCode(),
            "it should respond with http 409"
        );
        $this->assertStringContainsString(
            "L'article n'a pas pu être ajouté au panier car il n'est pas encore disponible.",
            $response->getContent(),
            "it should contain error message"
        );
        $this->assertFalse(
            $cart->containsArticle($article),
            "it should not have added article to cart"
        );
    }

    public function testAddStockCopy()
    {
        global $_SITE;

        // given
        $cm = new CartManager();
        $_SITE->setOpt("virtual_stock", 0);
        $controller = new CartController();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->vacuum($cart);
        $stock = EntityFactory::createStock();

        // when
        $response = $controller->addStockAction(
            $stock->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertTrue(
            $cart->containsStock($stock),
            "it should have added article to cart"
        );
        $updatedCart = $cm->getById($cart->get("id"));
        $this->assertEquals(
            1,
            $updatedCart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testAddCrowdfundingReward()
    {
        // given
        $cm = new CartManager();
        $controller = new CartController();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->vacuum($cart);
        $reward = EntityFactory::createCrowdfundingReward();

        // when
        $response = $controller->addCrowdfundingRewardAction(
            $reward->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertTrue(
            $cart->containsReward($reward),
            "it should have added article to cart"
        );
        $updatedCart = $cm->getById($cart->get("id"));
        $this->assertEquals(
            1,
            $updatedCart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testRemoveStock()
    {
        // given
        $cm = new CartManager();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->vacuum($cart);
        $stock = EntityFactory::createStock();
        $cm->addStock($cart, $stock);
        $cm->updateFromStock($cart);
        $controller = new CartController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");

        // when
        $response = $controller->removeStockAction(
            $request,
            $stock->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertFalse(
            $cart->containsStock($stock),
            "it should have removed stock from cart"
        );
        $updatedCart = $cm->getById($cart->get("id"));
        $this->assertEquals(
            0,
            $updatedCart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testRemoveStockLegacyUsage()
    {
        // given
        $cm = new CartManager();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->vacuum($cart);
        $stock = EntityFactory::createStock();
        $cm->addStock($cart, $stock);
        $cm->updateFromStock($cart);
        $controller = new CartController();
        $request = new Request();
        $request->headers->set("Accept", "text/html");

        // when
        $response = $controller->removeStockAction(
            $request,
            $stock->get("id")
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
        $this->assertFalse(
            $cart->containsStock($stock),
            "it should have removed stock from cart"
        );
        $updatedCart = $cm->getById($cart->get("id"));
        $this->assertEquals(
            0,
            $updatedCart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testGetSummaryWhenCartIsEmpty()
    {
        

        // given
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm = new CartManager();
        $cm->vacuum($cart);
        $controller = new CartController();

        // when
        $response = $controller->summaryAction();

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
                class="btn btn-default btn-sm empty"><span class="fa fa-shopping-cart"></span> Panier vide</a>',
            json_decode($response->getContent())->summary,
            "it should return cart summary"
        );
    }

    public function testGetSummaryWhenCartIsFull()
    {
        

        // given
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm = new CartManager();
        $cm->vacuum($cart);
        $stock = EntityFactory::createStock(["stock_selling_price" => 500]);
        $cm->addStock($cart, $stock);
        $cm->updateFromStock($cart);
        $controller = new CartController();

        // when
        $response = $controller->summaryAction();

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
                class="btn btn-default btn-sm not-empty"><span class="fa fa-shopping-cart"></span> 1 article (5,00&nbsp;&euro;)</a>',
            json_decode($response->getContent())->summary,
            "it should return cart summary"
        );

        $cm->vacuum($cart);
    }
}
