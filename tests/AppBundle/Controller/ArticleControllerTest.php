<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Article\Type;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\LoggerService;
use Biblys\Service\MailingList\MailingListInterface;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Service\Watermarking\WatermarkedFile;
use Biblys\Service\Watermarking\WatermarkingService;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use LemonInk\Models\Transaction;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
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
        $article = ModelFactory::createArticle(title: "Citoyens de demain");
        $request = new Request();
        $config = $this->createMock(Config::class);
        $currentSiteService = $this->createMock(CurrentSite::class);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $loggerService = $this->createMock(LoggerService::class);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $controller = new ArticleController();
        $GLOBALS["urlgenerator"] = Mockery::mock(UrlGenerator::class);
        $GLOBALS["urlgenerator"]->shouldReceive("generate")
            ->andReturn("");

        // when
        $response = $controller->showAction(
            request: $request,
            config: $config,
            currentSiteService: $currentSiteService,
            urlGenerator:  $urlGenerator,
            loggerService: $loggerService,
            metaTags: $metaTagsService,
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
        $article = ModelFactory::createArticle(publisher: $publisher);

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
        $article = ModelFactory::createArticle(publisher: $publisher);
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
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testDeleteActionIsImpossibleIfStock()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentSite = $this->createMock(CurrentSite::class);
        $article = ModelFactory::createArticle();
        $controller = new ArticleController();
        ModelFactory::createStockItem(article: $article);

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
            title: "Résultat de recherche",
            authors : [ModelFactory::createPeople()],
        );
        $controller = new ArticleController();
        $request = new Request();
        $currentSite = $this->createMock(CurrentSite::class);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->andReturn();
        $queryParams->shouldReceive("get")->with("q")->andReturn("Résultat de recherche");
        $queryParams->shouldReceive("get")->with("in-stock")->andReturn("0");
        $queryParams->shouldReceive("get")->with("sort")->andReturn("pubdate|desc");
        $queryParams->shouldReceive("get")->with("p")->andReturn("0");

        // when
        $response = $controller->searchAction($request, $currentSite, $queryParams);

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
            title: "Résultat de recherche trié",
            authors: [ModelFactory::createPeople()]
        );
        $controller = new ArticleController();
        $request = new Request();
        $currentSite = $this->createMock(CurrentSite::class);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->andReturn();
        $queryParams->shouldReceive("get")->with("q")->andReturn("Résultat de recherche trié");
        $queryParams->shouldReceive("get")->with("in-stock")->andReturn("0");
        $queryParams->shouldReceive("get")->with("sort")->andReturn("pubdate|desc");
        $queryParams->shouldReceive("get")->with("p")->andReturn("0");

        // when
        $response = $controller->searchAction($request, $currentSite, $queryParams);

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
    public function testSearchActionWithIllegalSortOption()
    {
        // then
        $this->expectException(BadRequestHttpException::class);

        // given
        ModelFactory::createArticle(
            title: "Résultat de recherche trié",authors:
            [ModelFactory::createPeople()]
        );
        $controller = new ArticleController();
        $request = new Request();
        $currentSite = $this->createMock(CurrentSite::class);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->andReturn();
        $queryParams->shouldReceive("get")->with("q")->andReturn("Résultat de recherche trié");
        $queryParams->shouldReceive("get")->with("in-stock")->andReturn("0");
        $queryParams->shouldReceive("get")->with("sort")->andReturn("1AND+1%3D1+ORDERBY%281%2C2%2C3%2C4%2C5%29+--%3B|desc");
        $queryParams->shouldReceive("get")->with("p")->andReturn("0");

        // when
        $controller->searchAction($request, $currentSite, $queryParams);
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
            title: "Résultat de recherche avec stock",
            authors: [ModelFactory::createPeople()]
        );
        ModelFactory::createStockItem(site: $site, article: $article);user:
        $controller = new ArticleController();
        $request = new Request();
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->andReturn();
        $queryParams->shouldReceive("get")->with("q")->andReturn("Résultat de recherche avec stock");
        $queryParams->shouldReceive("get")->with("in-stock")->andReturn("1");
        $queryParams->shouldReceive("get")->with("sort")->andReturn("pubdate|desc");
        $queryParams->shouldReceive("get")->with("p")->andReturn("0");

        // when
        $response = $controller->searchAction($request, $currentSite, $queryParams);

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
            title: "Résultat de recherche trié avec stock",
            authors: [ModelFactory::createPeople()]
        );
        ModelFactory::createStockItem(site: $site, article: $article);user:
        $controller = new ArticleController();
        $request = new Request();
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->andReturn();
        $queryParams->shouldReceive("get")->with("q")->andReturn("Résultat de recherche trié avec stock");
        $queryParams->shouldReceive("get")->with("in-stock")->andReturn("1");
        $queryParams->shouldReceive("get")->with("sort")->andReturn("pubdate|asc");
        $queryParams->shouldReceive("get")->with("p")->andReturn("0");

        // when
        $response = $controller->searchAction($request, $currentSite, $queryParams);

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
        ModelFactory::createArticle(ean: "9781234567897", keywords: 9781234567897);
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
        $article = ModelFactory::createArticle(ean: "9781234567880", keywords: "9781234567880");
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
        $article = ModelFactory::createArticle(price: 0, typeId: Type::EBOOK);
        $controller = new ArticleController();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentUserService = $this->createMock(CurrentUser::class);
        $mailingListService = $this->createMock(MailingListService::class);

        // when
        $response = $controller->freeDownloadAction(
            $request,
            $currentSiteService,
            $currentUserService,
            $mailingListService,
            $article->getId(),
        );

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
    public function testFreeDownloadActionWithNewsletterPrompt()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $article = ModelFactory::createArticle(price: 0, typeId: Type::EBOOK);
        $user = ModelFactory::createAxysAccount(email: "free-reader@example.org", username: "Free Reader");
        $controller = new ArticleController();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentSiteService
            ->expects($this->once())
            ->method("getOption")
            ->with("newsletter")
            ->willReturn("1");
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->expects($this->once())->method("getAxysAccount")->willReturn($user);
        $mailingList = $this->createMock(MailingListInterface::class);
        $mailingList
            ->expects($this->once())
            ->method("hasContact")
            ->with("free-reader@example.org")
            ->willReturn(false);
        $mailingListService = $this->createMock(MailingListService::class);
        $mailingListService->expects($this->once())->method("isConfigured")->willReturn(true);
        $mailingListService->expects($this->once())->method("getMailingList")->willReturn($mailingList);

        // when
        $response = $controller->freeDownloadAction(
            $request,
            $currentSiteService,
            $currentUserService,
            $mailingListService,
            $article->getId(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString(
            "Je souhaite recevoir la newsletter pour être tenu·e",
            $response->getContent(),
            "includes newletter prompt"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testFreeDownloadActionWhileAlreadyInLibrary()
    {
        // given
        $controller = new ArticleController();
        $request = RequestFactory::createAuthRequest();
        $article = ModelFactory::createArticle(price: 0, typeId: Type::EBOOK);
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAxysAccount();
        ModelFactory::createStockItem(site: $site, article: $article, axysAccount: $user);
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentSiteService->expects($this->once())->method("getSite")->willReturn($site);
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->expects($this->once())->method("getAxysAccount")->willReturn($user);
        $mailingList = $this->createMock(MailingListInterface::class);
        $mailingListService = $this->createMock(MailingListService::class);
        $mailingListService->method("getMailingList")->willReturn($mailingList);

        // when
        $response = $controller->freeDownloadAction(
            $request,
            $currentSiteService,
            $currentUserService,
            $mailingListService,
            $article->getId(),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode(), "returns HTTP 302");
        $this->assertEquals("/pages/log_myebooks", $response->getTargetUrl(), "redirects to elibrary");
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
        $article = ModelFactory::createArticle(price: 0, typeId: Type::EBOOK);
        $controller = new ArticleController();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentUserService = $this->createMock(CurrentUser::class);
        $mailingListService = $this->createMock(MailingListService::class);

        // when
        $controller->freeDownloadAction(
            $request,
            $currentSiteService,
            $currentUserService,
            $mailingListService,
            $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testDownloadWithWatermarkForWatermarkedItem()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle(
            title: "A book about tatoo",
            lemoninkMasterId: "zyxwvuts",
        );
        $axysAccount = ModelFactory::createAxysAccount(email: "i.love.tatoo@biblys.fr");
        $site = ModelFactory::createSite();
        $stock = ModelFactory::createStockItem(
            site: $site,
            article: $article,
            axysAccount: $axysAccount,
            lemoninkTransactionId: "123456789",
            lemoninkTransactionToken: "abcdefgh",
        );

        $request = RequestFactory::createAuthRequest();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")->andReturn($article->getPublisherId());
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")->andReturn($axysAccount);
        $watermarkingFile = Mockery::mock(WatermarkedFile::class);
        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $watermarkingService->shouldReceive("getFiles")
            ->with("zyxwvuts", "123456789", "abcdefgh")
            ->andReturn([$watermarkingFile]);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->with("AppBundle:Article:download-with-watermark.html.twig", [
                "article_id" => $article->getId(),
                "article_title" => "A book about tatoo",
                "item_id" => $stock->getId(),
                "user_email" => "i.love.tatoo@biblys.fr",
                "isWatermarked" => true,
                "files" => [$watermarkingFile],
            ])
            ->andReturn(new Response());

        // when
        $response = $controller->downloadWithWatermarkAction(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            watermarkingService: $watermarkingService,
            templateService: $templateService,
            id: $article->getId(),
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http status 200"
        );
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testDownloadWithWatermarkForUnWatermarkedItem()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle(
            title: "A book about tatoo",
            lemoninkMasterId: "zyxwvuts",
        );
        $axysAccount = ModelFactory::createAxysAccount(email: "i.hate.tatoo@biblys.fr");
        $site = ModelFactory::createSite();
        $stock = ModelFactory::createStockItem(
            site: $site,
            article: $article,
            axysAccount: $axysAccount,
        );

        $request = RequestFactory::createAuthRequest();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")->andReturn($article->getPublisherId());
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")->andReturn($axysAccount);
        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $watermarkingService->shouldNotReceive("getFiles");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->with("AppBundle:Article:download-with-watermark.html.twig", [
                "article_id" => $article->getId(),
                "article_title" => "A book about tatoo",
                "item_id" => $stock->getId(),
                "user_email" => "i.hate.tatoo@biblys.fr",
                "isWatermarked" => false,
                "files" => [],
            ])
            ->andReturn(new Response());

        // when
        $response = $controller->downloadWithWatermarkAction(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            watermarkingService: $watermarkingService,
            templateService: $templateService,
            id: $article->getId(),
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http status 200"
        );
    }

    /**
     * #watermarkAction
     */

    /**
     * @throws PropelException
     */
    public function testWatermarkIfServiceIsNotConfigured()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle();

        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        // then
        $this->expectException(ServiceUnavailableHttpException::class);
        $this->expectExceptionMessage("Watermarking service is not configured (missing API key).");

        // when
        $controller->watermarkAction(
            request: RequestFactory::createAuthRequest(),
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testUnexistingArticle()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle();
        $otherPublisher = ModelFactory::createPublisher();

        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")
            ->andReturn($otherPublisher->getId());
        $currentUser = Mockery::mock(CurrentUser::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Article {$article->getId()} does not exist.");

        // when
        $controller->watermarkAction(
            request: RequestFactory::createAuthRequest(),
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testWatermarkWithoutMasterId()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle();

        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")
            ->andReturn($article->getPublisherId());
        $currentUser = Mockery::mock(CurrentUser::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Article {$article->getId()} does not have a watermark master id.");

        // when
        $controller->watermarkAction(
            request: RequestFactory::createAuthRequest(),
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testWatermarkIfNotInUserLibrary()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle(lemoninkMasterId: "youpla");

        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")
            ->andReturn($article->getPublisherId());
        $currentSite->shouldReceive("getSite")
            ->andReturn(ModelFactory::createSite());
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")
            ->andReturn(ModelFactory::createAxysAccount());
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        $request = RequestFactory::createAuthRequest();
        $request->request->set("consent", "given");

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Article {$article->getId()} is not in user library.");

        // when
        $controller->watermarkAction(
            request: $request,
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testWatermarkForAlreadyWatermarkedItem()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle(lemoninkMasterId: "youpla");
        $axysAccount = ModelFactory::createAxysAccount();
        $site = ModelFactory::createSite();
        $libraryItem = ModelFactory::createStockItem(
            site: $site,
            article: $article,
            axysAccount: $axysAccount,
            lemoninkTransactionId: "123456789",
            lemoninkTransactionToken: "abcdefgh",
        );

        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")
            ->andReturn($article->getPublisherId());
        $currentSite->shouldReceive("getSite")
            ->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")
            ->andReturn($axysAccount);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        $request = RequestFactory::createAuthRequest();
        $request->request->set("consent", "given");

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Item {$libraryItem->getId()} is already watermarked.");

        // when
        $controller->watermarkAction(
            request: $request,
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testWatermarkWithoutConsent()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle(lemoninkMasterId: "youpla");
        $axysAccount = ModelFactory::createAxysAccount("downloader@example.org");
        $site = ModelFactory::createSite();
        $libraryItem = ModelFactory::createStockItem(
            site: $site, article: $article, axysAccount: $axysAccount
        );
        $transaction = new Transaction();
        $transaction->setId("123456789");
        $transaction->setToken("abcdefgh");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")
            ->andReturn($article->getPublisherId());
        $currentSite->shouldReceive("getSite")
            ->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")
            ->andReturn($axysAccount);
        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $watermarkingService->shouldReceive("watermark")
            ->with("youpla", "Téléchargé par downloader@example.org (#{$libraryItem->getId()})")
            ->andReturns($transaction);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        $request = RequestFactory::createAuthRequest();

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Vous devez accepter le tatouage numérique du fichier pour continuer.");

        // when
        $controller->watermarkAction(
            request: RequestFactory::createAuthRequest(),
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testWatermarkSuccess()
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle(lemoninkMasterId: "youpla");
        $axysAccount = ModelFactory::createAxysAccount("downloader@example.org");
        $site = ModelFactory::createSite();
        $libraryItem = ModelFactory::createStockItem(
            site: $site, article: $article, axysAccount: $axysAccount
        );
        $transaction = new Transaction();
        $transaction->setId("123456789");
        $transaction->setToken("abcdefgh");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("publisher_filter")
            ->andReturn($article->getPublisherId());
        $currentSite->shouldReceive("getSite")
            ->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")
            ->andReturn($axysAccount);
        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $watermarkingService->shouldReceive("watermark")
            ->with("youpla", "Téléchargé par downloader@example.org (#{$libraryItem->getId()})")
            ->andReturns($transaction);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_download_with_watermark", ["id" => $article->getId()])
            ->andReturn("/articles/{}/download-with-watermark");

        $request = RequestFactory::createAuthRequest();
        $request->request->set("consent", "given");

        // when
        $response = $controller->watermarkAction(
            request: $request,
            watermarkingService: $watermarkingService,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            id: $article->getId(),
        );

        // then
        $libraryItem->reload();
        $this->assertEquals("123456789", $libraryItem->getLemoninkTransactionId());
        $this->assertEquals("abcdefgh", $libraryItem->getLemoninkTransactionToken());
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "responds with http status 302"
        );
        $this->assertEquals(
            "/articles/{}/download-with-watermark",
            $response->getTargetUrl(),
            "redirects to elibrary"
        );
    }
}
