<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Article\Type;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\LoggerService;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class ArticleControllerTest extends TestCase
{
    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws ClientExceptionInterface
     */
    public function testShow()
    {
        // given
        $article = ModelFactory::createArticle(["title" => "Citoyens de demain"]);
        $request = new Request();
        $config = $this->createMock(Config::class);
        $currentSiteService = $this->createMock(CurrentSite::class);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $loggerService = $this->createMock(LoggerService::class);
        $controller = new ArticleController();

        // when
        $response = $controller->showAction(
            request: $request,
            config: $config,
            currentSiteService: $currentSiteService,
            urlGenerator:  $urlGenerator,
            loggerService: $loggerService,
            slug: $article->getSlug(),
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "respond http status 200"
        );
        $this->assertStringContainsString(
            "Citoyens de demain",
            $response->getContent(),
            "includes article title"
        );
    }

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
        $this->expectException(UnauthorizedHttpException::class);

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
     * @throws PropelException
     */
    public function testAddRayonActionForUser()
    {
        // given
        $controller = new ArticleController();
        $request = new Request();
        $currentSite = ModelFactory::createSite();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentSiteService->method("getSite")->willReturn($currentSite);

        // then
        $this->expectException(UnauthorizedHttpException::class);

        // when
        $controller->addRayonsAction($request, $currentSiteService, 1);
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
        $currentSite = ModelFactory::createSite();
        $category = ModelFactory::createArticleCategory($currentSite);
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentSiteService->method("getSite")->willReturn($currentSite);
        $request->request->set("rayon_id", $category->getId());

        // when
        $response = $controller->addRayonsAction($request, $currentSiteService, $article->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
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

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testSearchAction()
    {
        // given
        ModelFactory::createArticle(
            ["title" => "Résultat de recherche"],
            null,
            null,
            [ModelFactory::createPeople()]
        );
        $controller = new ArticleController();
        $request = new Request();
        $request->query->set("q", "Résultat de recherche");
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->searchAction($request, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
        $this->assertStringContainsString(
            "1 résultat",
            $response->getContent(),
            "return correct number of results"
        );
        $this->assertStringContainsString(
            "Résultat de recherche",
            $response->getContent(),
            "return article with matching title"
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testSearchActionWithSortOption()
    {
        // given
        ModelFactory::createArticle(
            ["title" => "Résultat de recherche trié"],
            null,
            null,
            [ModelFactory::createPeople()]
        );
        $controller = new ArticleController();
        $request = new Request();
        $request->query->set("q", "Résultat de recherche trié");
        $request->query->set("sort", "article_pubdate|desc");
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->searchAction($request, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
        $this->assertStringContainsString(
            "1 résultat",
            $response->getContent(),
            "return correct number of results"
        );
        $this->assertStringContainsString(
            "Résultat de recherche",
            $response->getContent(),
            "return article with matching title"
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testSearchActionWithAvailableStock()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(
            ["title" => "Résultat de recherche avec stock"],
            null,
            null,
            [ModelFactory::createPeople()]
        );
        ModelFactory::createStockItem([], $site, $article);
        $controller = new ArticleController();
        $request = new Request();
        $request->query->set("q", "Résultat de recherche avec stock");
        $request->query->set("in-stock", "1");

        // when
        $response = $controller->searchAction($request, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
        $this->assertStringContainsString(
            "1 résultat",
            $response->getContent(),
            "return correct number of results"
        );
        $this->assertStringContainsString(
            "Résultat de recherche",
            $response->getContent(),
            "return article with matching title"
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testSearchActionWithAvailableStockAndSortOption()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(
            ["title" => "Résultat de recherche trié avec stock"],
            null,
            null,
            [ModelFactory::createPeople()]
        );
        ModelFactory::createStockItem([], $site, $article);
        $controller = new ArticleController();
        $request = new Request();
        $request->query->set("q", "Résultat de recherche trié avec stock");
        $request->query->set("sort", "article_pubdate|asc");
        $request->query->set("in-stock", "1");

        // when
        $response = $controller->searchAction($request, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
        $this->assertStringContainsString(
            "1 résultat",
            $response->getContent(),
            "return correct number of results"
        );
        $this->assertStringContainsString(
            "Résultat de recherche",
            $response->getContent(),
            "return article with matching title"
        );
    }

    /**
     * @throws PropelException
     */
    public function testCheckIsbn()
    {
        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Cet ISBN est déjà utilisé par un autre article");

        // given
        ModelFactory::createArticle(["ean" => 9781234567897, "keywords" => "9781234567897"]);
        $otherArticle = ModelFactory::createArticle();
        $requestBody = json_encode(["article_id" => $otherArticle->getId(), "article_ean" => "9781234567897"]);
        $request = new Request([], [], [], [], [], [], $requestBody);
        $controller = new ArticleController();

        // when
        $controller->checkIsbn($request);
    }

    /**
     * @throws PropelException
     */
    public function testCheckIsbnIgnoresSameArticle()
    {
        // given
        $article = ModelFactory::createArticle(["ean" => 9781234567880, "keywords" => "9781234567880"]);
        $requestBody = json_encode(["article_id" => (string) $article->getId(), "article_ean" => "9781234567880"]);
        $request = new Request([], [], [], [], [], [], $requestBody);
        $controller = new ArticleController();

        // when
        $response = $controller->checkIsbn($request);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "should respond with 200"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testFreeDownloadAction()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $article = ModelFactory::createArticle(["type_id" => Type::EBOOK, "price" => 0]);
        $controller = new ArticleController();

        // when
        $response = $controller->freeDownloadAction($request, $article->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testFreeDownloadActionForAnonymousUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise");

        // given
        $request = new Request();
        $article = ModelFactory::createArticle(["type_id" => Type::EBOOK, "price" => 0]);
        $controller = new ArticleController();

        // when
        $controller->freeDownloadAction($request, $article->getId());
    }
}
