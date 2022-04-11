<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class ArticleControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUpdatePublisherStock()
    {
        // given
        $am = new ArticleManager();
        $article = EntityFactory::createArticle();
        $controller = new ArticleController();
        $request = new Request([], [], [], [], [], [], "     1   ");

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

    /**
     * @throws Exception
     */
    public function testByIsbn()
    {
        // given
        EntityFactory::createArticle(["article_ean" => "9781234567895"]);
        $controller = new ArticleController();

        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method('generate')
            ->willReturn('/a/article');

        // when
        $response = $controller->byIsbn($urlGenerator, "9781234567895");

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
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/a/article");

        // given
        $controller = new ArticleController();

        // when
        $controller->byIsbn($urlGenerator, "7908026792240");
    }

    public function testByIsbnWithUnexistingArticle()
    {
        // then
        $this->expectException("\Symfony\Component\Routing\Exception\ResourceNotFoundException");
        $this->expectExceptionMessage("Article with ISBN 9781233456789 not found.");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/a/article");

        // given
        $controller = new ArticleController();

        // when
        $controller->byIsbn($urlGenerator, "9781233456789");
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddTagsActionForUser()
    {
        // given
        $controller = new ArticleController();
        $request = new Request();

        // then
        $this->expectException(AuthException::class);

        // when
        $controller->addTagsAction($request, 1);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddTagsActionForPublisher()
    {
        // given
        $controller = new ArticleController();
        $publisher = ModelFactory::createPublisher();
        $request = RequestFactory::createAuthRequestForPublisherUser($publisher);
        $article = ModelFactory::createArticle([], $publisher);

        // when
        $response = $controller->addTagsAction($request, $article->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
    }

    /**
     * @throws AuthException
     */
    public function testAddRayonActionForUser()
    {
        // given
        $controller = new ArticleController();
        $request = new Request();

        // then
        $this->expectException(AuthException::class);

        // when
        $controller->addRayonsAction($request, 1);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddRayonActionForPublisher()
    {
        // given
        $controller = new ArticleController();
        $publisher = ModelFactory::createPublisher();
        $request = RequestFactory::createAuthRequestForPublisherUser($publisher);
        $article = ModelFactory::createArticle([], $publisher);
        $category = ModelFactory::createArticleCategory();
        $request->request->set("rayon_id", $category->getId());

        // when
        $response = $controller->addRayonsAction($request, $article->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testDeleteAction()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentSite = $this->createMock(CurrentSite::class);
        $article = ModelFactory::createArticle();
        $controller = new ArticleController();

        // when
        $response = $controller->deleteAction(
            $request,
            $urlGenerator,
            $currentSite,
            $article->getId()
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
    }
}
