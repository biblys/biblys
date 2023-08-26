<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Exception;
use League\Csv\CannotInsertRecord;
use Mockery;
use Model\ArticleQuery;
use Model\InvitationQuery;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class InvitationControllerTest extends TestCase
{

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function testCreateAction()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_addresses", "user@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à user@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "download");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->once())->method("render")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $invitation = InvitationQuery::create()->findOneByEmail("user@example.org");
        $this->assertNotNull($invitation);
        $this->assertFalse($invitation->getAllowsPreDownload());
    }

    /**
     * @throws CannotInsertRecord
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithManualMode()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_addresses", "manual1@example.org\r\nmanual2@example.org\r\nmanual3@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation à télécharger « Sent Book » a été créée pour manual1@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation à télécharger « Sent Book » a été créée pour manual2@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation à télécharger « Sent Book » a été créée pour manual3@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "manual");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->exactly(3))->method("render")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/admin/invitations");
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/invitations", $response->getTargetUrl());
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("manual1@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("manual2@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("manual3@example.org"));
    }

    /**
     * @throws CannotInsertRecord
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithSendMode()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_addresses", "send1@example.org\r\nsend2@example.org\r\nsend3@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à send1@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à send2@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à send3@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "send");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->exactly(3))->method("render")->willReturn(new Response("Invitation"));
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->exactly(3))->method("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/admin/invitations");
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/invitations", $response->getTargetUrl());
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("send1@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("send2@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("send3@example.org"));
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithDownloadMode()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_addresses", "download1@example.org\r\ndownload2@example.org\r\ndownload3@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à download1@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à download2@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à download3@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "download");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->exactly(3))->method("render")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("download1@example.org", $response->getContent());
        $this->assertStringContainsString("download2@example.org", $response->getContent());
        $this->assertStringContainsString("download3@example.org", $response->getContent());
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithAllowsPredownload()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_addresses", "predownload@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à predownload@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "download");
        $request->request->set("allows_pre_download", "1");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->once())->method("render")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $invitation = InvitationQuery::create()->findOneByEmail("predownload@example.org");
        $this->assertNotNull($invitation);
        $this->assertTrue($invitation->getAllowsPreDownload());
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function testCreateActionForMultipleArticles()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_addresses", "multiple@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Invited 1 » et 2 autres a été envoyée à multiple@example.org");
        $publisher = ModelFactory::createPublisher();
        $article1 = ModelFactory::createArticle(title: "Invited 1", typeId: Type::EBOOK, publisher: $publisher);
        $article2 = ModelFactory::createArticle(title: "Invited 2", typeId: Type::EBOOK, publisher: $publisher);
        $article3 = ModelFactory::createArticle(title: "Invited 3", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article1->getId(), $article2->getId(), $article3->getId()]);
        $request->request->set("mode", "send");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->once())->method("render")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with("multiple@example.org", "Téléchargez « Invited 1 » et 2 autres en numérique",
                Mockery::any());
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $invitation = InvitationQuery::create()->findOneByEmail("multiple@example.org");
        $this->assertNotNull($invitation);
        $this->assertEquals([$article1, $article2, $article3], $invitation->getArticles()->getData());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testShowAction()
    {
        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(
            title: "The Code Show", typeId: Type::EBOOK, publisher: $publisher
        );
        ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "SHOWCODE", code: "SHOWCODE"
        );

        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $currentUser = $this->createMock(CurrentUser::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("render")
            ->with("AppBundle:Invitation:show.html.twig", [
                "articles" => ArticleQuery::create()->findById($article->getId()),
                "currentUser" => $currentUser,
                "code" => "SHOWCODE",
                "error" => null,
            ])
            ->andReturn(new Response("Télécharger The Code Show"));
        $controller = new InvitationController();

        // when
        $response = $controller->showAction(
            currentSite: $currentSite,
            currentUser: $currentUser,
            templateService: $templateService,
            code: "SHOWCODE"
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Télécharger The Code Show", $response->getContent());
    }

    /**
     * #consume
     */

    /**
     * @throws PropelException
     */
    public function testConsumeActionForUnauthentifiedUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);

        // given
        $controller = new InvitationController();
        $request = new Request();
        $currenSite = $this->createMock(CurrentSite::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currenSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     */
    public function testConsumeActionForNonexistingInvitation()
    {
        // then
        $this->expectException(NotFoundHttpException::class);

        // given
        $site = ModelFactory::createSite();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "FAKECODE");
        $currentSite = new CurrentSite($site);
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionForAlreadyConsumedInvitation()
    {
        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Cette invitation a déjà été utilisée.");

        // given
        $site = ModelFactory::createSite();
        ModelFactory::createInvitation(
            site: $site,
            code: "CONSUMED",
            consumedAt: new DateTime("1 month ago"),
        );

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "CONSUMED");
        $currentSite = new CurrentSite($site);
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionForExpiredInvitation()
    {
        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Cette invitation a expiré");

        // given
        $site = ModelFactory::createSite();
        ModelFactory::createInvitation(
            site: $site,
            code: "EXPIRED1",
            expiresAt: new DateTime("1 month ago"),
        );

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "EXPIRED1");
        $currentSite = new CurrentSite($site);
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionForPublisherMissingInFilter()
    {
        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(
            "Ce site n'est pas autorisé à distribuer les articles de Éditeur filtré."
        );

        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher(name: "Éditeur filtré");
        $article = ModelFactory::createArticle(publisher: $publisher);
        ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "UNAUTHPU", code: "UNAUTHPU"
        );
        $currentSite = new CurrentSite($site);

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "UNAUTHPU");
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionForNonDownloadablePublisher()
    {
        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(
            "Le téléchargement des articles de Éditeur non autorisé n'est pas autorisé sur ce site."
        );

        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher(name: "Éditeur non autorisé");
        $article = ModelFactory::createArticle(publisher: $publisher);
        ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "NONDOPUB", code: "NONDOPUB"
        );
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "NONDOPUB");
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionForNonDownloadableArticle()
    {
        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("L'article Livre papier n'est pas téléchargeable.");

        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(title: "Livre papier");
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "PAPERBOO", code: "PAPERBOO"
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "PAPERBOO");
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionForArticleAlreadyInUserLibrary()
    {
        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("L'article Dans ma bibliothèque est déjà dans votre bibliothèque.");

        // given
        $axysAccount = ModelFactory::createAxysAccount();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(title: "Dans ma bibliothèque", typeId: Type::EBOOK);
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], code: "ELIBRARY"
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);
        ModelFactory::createStockItem(site: $site, article: $article, axysAccount: $axysAccount);

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest(user: $axysAccount);
        $request->request->set("code", "ELIBRARY");
        $currentUser = new CurrentUser($axysAccount, "token");
        $session = $this->createMock(Session::class);

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session);
    }

    /**
     * @throws PropelException
     */
    public function testConsumeAction()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(title: "Livre numérique", typeId: Type::EBOOK);
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "ALLRIGHT", code: "ALLRIGHT"
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);
        $axysAccount = ModelFactory::createAxysAccount();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "ALLRIGHT");
        $currentUser = new CurrentUser($axysAccount, "token");
        $flashBag = $this->createMock(FlashBag::class);
        $flashBag->expects($this->once())->method("add")
            ->with("success", "Livre numérique a été ajouté à votre bibliothèque.");
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method("getFlashBag")->willReturn($flashBag);

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/pages/log_myebooks", $response->getTargetUrl());
        $consumedInvitation = InvitationQuery::create()->findPk($invitation->getId());
        $this->assertNotNull(
            $consumedInvitation->getConsumedAt(),
            "it consumes the invitation"
        );
        $articleInLibrary = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article)
            ->findOneByAxysAccountId($axysAccount->getId());
        $this->assertNotNull($articleInLibrary, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary->getSellingPrice());
        $this->assertNotNull($articleInLibrary->getSellingDate());
        $this->assertFalse($articleInLibrary->getAllowPredownload());
    }

    /**
     * @throws PropelException
     */
    public function testConsumeActionWithAllowsPreDownloadOption()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(title: "Livre numérique", typeId: Type::EBOOK);
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "ALLRIGHT", code: "ALLRIGHT",
            allowsPreDownload: true
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);
        $axysAccount = ModelFactory::createAxysAccount();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "ALLRIGHT");
        $currentUser = new CurrentUser($axysAccount, "token");
        $flashBag = $this->createMock(FlashBag::class);
        $flashBag->expects($this->once())->method("add")
            ->with("success", "Livre numérique a été ajouté à votre bibliothèque.");
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method("getFlashBag")->willReturn($flashBag);

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/pages/log_myebooks", $response->getTargetUrl());
        $consumedInvitation = InvitationQuery::create()->findPk($invitation->getId());
        $this->assertNotNull(
            $consumedInvitation->getConsumedAt(),
            "it consumes the invitation"
        );
        $articleInLibrary = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article)
            ->findOneByAxysAccountId($axysAccount->getId());
        $this->assertNotNull($articleInLibrary, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary->getSellingPrice());
        $this->assertNotNull($articleInLibrary->getSellingDate());
        $this->assertTrue($articleInLibrary->getAllowPredownload());
    }

    /**
     * @throws PropelException
     */
    public function testConsumeActionWithMultipleArticles()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article1 = ModelFactory::createArticle(title: "Multiple 1", typeId: Type::EBOOK);
        $article2 = ModelFactory::createArticle(title: "Multiple 2", typeId: Type::EBOOK);
        $article3 = ModelFactory::createArticle(title: "Multiple 3", typeId: Type::EBOOK);
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article1, $article2, $article3],
            email: "multiple@example.org", code: "ALLRIGHT"
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);
        $axysAccount = ModelFactory::createAxysAccount();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "ALLRIGHT");
        $currentUser = new CurrentUser($axysAccount, "token");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Multiple 1 a été ajouté à votre bibliothèque.");
        $flashBag->shouldReceive("add")
            ->with("success", "Multiple 2 a été ajouté à votre bibliothèque.");
        $flashBag->shouldReceive("add")
            ->with("success", "Multiple 3 a été ajouté à votre bibliothèque.");
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->andReturn($flashBag);

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/pages/log_myebooks", $response->getTargetUrl());
        $consumedInvitation = InvitationQuery::create()->findPk($invitation->getId());
        $this->assertNotNull(
            $consumedInvitation->getConsumedAt(),
            "it consumes the invitation"
        );

        $articleInLibrary1 = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article1)
            ->findOneByAxysAccountId($axysAccount->getId());
        $this->assertNotNull($articleInLibrary1, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary1->getSellingPrice());
        $this->assertNotNull($articleInLibrary1->getSellingDate());

        $articleInLibrary2 = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article2)
            ->findOneByAxysAccountId($axysAccount->getId());
        $this->assertNotNull($articleInLibrary2, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary2->getSellingPrice());
        $this->assertNotNull($articleInLibrary2->getSellingDate());

        $articleInLibrary3 = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article3)
            ->findOneByAxysAccountId($axysAccount->getId());
        $this->assertNotNull($articleInLibrary3, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary3->getSellingPrice());
        $this->assertNotNull($articleInLibrary3->getSellingDate());
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testListAction()
    {
        // given
        $article = ModelFactory::createArticle(title: "Listed Book", typeId: Type::EBOOK);
        ModelFactory::createInvitation(
            articles: [$article], email: "listed-invitation@biblys.fr", code: "LISTEDIN",
        );
        $request = RequestFactory::createAuthRequestForAdminUser();
        $controller = new InvitationController();

        // when
        $response = $controller->listAction($request);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Listed Book", $response->getContent());
        $this->assertStringContainsString("listed-invitation@biblys.fr", $response->getContent());
        $this->assertStringContainsString("LISTEDIN", $response->getContent());
    }
}
