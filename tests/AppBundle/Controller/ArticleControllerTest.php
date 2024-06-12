<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\ArticleController;
use Biblys\Test\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__."/../../setUp.php";

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

    public function testByIsbn()
    {
        // given
        Factory::createArticle(["article_ean" => "9781234567895"]);
        $controller = new ArticleController();

        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method('generate')
            ->willReturn('/a/article');

        // when
        $response = $controller->byIsbn("9781234567895", $urlGenerator);

        // then
        $this->assertEquals(
            301,
            $response->getStatusCode(),
            "it should return HTTP 301"
        );
        $this->assertEquals(
            "/a/article",
            $response->headers->get("Location"),
            "it should redirect to article page"
        );
    }

    public function testByIsbnWithInvalidEan()
    {
        // then
        $this->expectException("\Symfony\Component\HttpKernel\Exception\BadRequestHttpException");
        $this->expectExceptionMessage("Product code should be 978 or 979");

        // given
        $controller = new ArticleController();

        // when
        $controller->byIsbn("7908026792240");
    }

    public function testByIsbnWithUnexistingArticle()
    {
        // then
        $this->expectException("\Symfony\Component\Routing\Exception\ResourceNotFoundException");
        $this->expectExceptionMessage("Article with ISBN 9781233456789 not found.");

        // given
        $controller = new ArticleController();

        // when
        $controller->byIsbn("9781233456789");
    }
}
