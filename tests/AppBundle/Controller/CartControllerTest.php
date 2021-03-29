<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\CartController;
use Biblys\Test\Factory;

require_once __DIR__."/../../setUp.php";

class CartControllerTest extends PHPUnit\Framework\TestCase
{
    public function testAddArticle()
    {
        global $_V, $site;

        // given
        $site->setOpt("virtual_stock", 1);
        $controller = new CartController();
        $cart = $_V->getCart("create");
        $article = Factory::createArticle();

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
            $cart->contains("article", $article->get("id")),
            "it should have added article to cart"
        );
        $this->assertEquals(
            1,
            $cart->get("count"),
            "it should have updated cart article count"
        );
    }
}
