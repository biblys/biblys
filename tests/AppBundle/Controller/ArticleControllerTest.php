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
use Biblys\Service\TemplateService;
use Biblys\Service\Watermarking\WatermarkingFile;
use Biblys\Service\Watermarking\WatermarkingService;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
            title: "Résultat de recherche trié",
            authors: [ModelFactory::createPeople()]
        );
        $controller = new ArticleController();
        $request = new Request();
        $request->query->set("q", "Résultat de recherche trié");
        $request->query->set("sort", "pubdate|desc");
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
        $request->query->set("q", "Résultat de recherche trié");
        $request->query->set("sort", "1AND+1%3D1+ORDERBY%281%2C2%2C3%2C4%2C5%29+--%3B|desc");
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $controller->searchAction($request, $currentSite);
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
            title: "Résultat de recherche trié avec stock",
            authors: [ModelFactory::createPeople()]
        );
        ModelFactory::createStockItem(site: $site, article: $article);user:
        $controller = new ArticleController();
        $request = new Request();
        $request->query->set("q", "Résultat de recherche trié avec stock");
        $request->query->set("sort", "pubdate|asc");
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
        $watermarkingFile = Mockery::mock(WatermarkingFile::class);
        $watermarkingService = Mockery::mock(WatermarkingService::class);
        $watermarkingService->shouldReceive("isConfigured")->andReturn(true);
        $watermarkingService->shouldReceive("getFiles")
            ->with("zyxwvuts", "123456789", "abcdefgh")
            ->andReturn([$watermarkingFile]);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("render")
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
        $templateService->shouldReceive("render")
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
}
