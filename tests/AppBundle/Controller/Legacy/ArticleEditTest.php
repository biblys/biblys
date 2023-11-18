<?php

namespace AppBundle\Controller\Legacy;

use AppBundle\Controller\LegacyController;
use Biblys\Article\Type;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";


class ArticleEditTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSimpleUserIsBlocked()
    {
        // given
        $article = ModelFactory::createArticle();

        $request = RequestFactory::createAuthRequest();
        $request->query->set("page", "article_edit");
        $request->query->set("id", $article->getId());

        $session = new Session();
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());
        $legacyController = new LegacyController();
        $config = Config::load();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas le droit d'accéder à cette page.");

        // when
        $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );
    }

    /**
     * @throws Exception
     */
    public function testPublisherUserHasAccess()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(publisher: $publisher);

        $request = RequestFactory::createAuthRequestForPublisherUser(publisher: $publisher);
        $request->query->set("page", "article_edit");
        $request->query->set("id", $article->getId());

        $session = new Session();
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());
        $legacyController = new LegacyController();
        $config = Config::load();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $response = $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
        );
    }

    /**
     * @throws Exception
     */
    public function testAdminUserHasAccess()
    {
        // given
        $article = ModelFactory::createArticle();

        $request = new Request();
        $request->query->set("page", "article_edit");
        $request->query->set("id", $article->getId());

        $session = new Session();
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());
        $legacyController = new LegacyController();
        $config = Config::load();
        $currentSite = CurrentSite::buildFromConfig($config);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $response = $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: CurrentUser::buildFromRequestAndConfig($request, $config),
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "it should respond with status code 200"
        );
    }

    /**
     * @throws PropelException
     */
    public function testLemoninkFieldIsDisplayed()
    {
        // given
        $controller = require __DIR__."/../../../../controllers/common/php/article_edit.php";

        $article = ModelFactory::createArticle(typeId: Type::EBOOK);
        $request = new Request();
        $request->query->set("id", $article->getId());

        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(true);
        $currentUser->shouldReceive("getCurrentRight")->andReturn(null);
        $currentUser->shouldReceive("getAxysAccount")->andReturn($user);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn("null");

        $urlgenerator = Mockery::mock(UrlGenerator::class);
        $urlgenerator->shouldReceive("generate")->andReturn("url");
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("lemonink.api_key")->andReturn("abcd1234");

        // when
        $response = $controller($request, $currentUser, $currentSite, $urlgenerator, $config);

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            "Identifiant LemonInk :",
            $response->getContent(),
            "displays the LemonInk field"
        );
    }
}
