<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\ArticleController;
use Symfony\Component\HttpFoundation\Request;

require_once "tests/setUp.php";

class ArticleControllerTest extends PHPUnit\Framework\TestCase
{
    public function testUpdatePublisherStock()
    {
        // given
        $am = new ArticleManager();
        $article = $am->create([
            "article_title" => "Out of stock!",
            "article_url" => "out-of-stock",
            "article_publisher_stock" => 0,
        ]);
        $controller = new ArticleController();
        $request = new Request();
        $request->request->set("article_publisher_stock", "     1   ");

        // when
        $response = $controller->updatePublisherStock($request, $article->get("id"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertEquals(
            "1",
            $am->getById($article->get("id"))->get("publisher_stock"),
            "it should update article publisher stock"
        );
    }
}
