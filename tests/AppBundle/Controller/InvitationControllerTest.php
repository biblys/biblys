<?php

namespace AppBundle\Controller;

use Biblys\Data\ArticleType;
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
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class InvitationControllerTest extends TestCase
{

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateAction()
    {
        // given
        $request = new Request();
        $request->request->set("email_addresses", "user@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à user@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: ArticleType::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "download");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->once())->method("renderResponse")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $controller = new InvitationController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $invitation = InvitationQuery::create()->findOneByEmail("user@example.org");
        $this->assertNotNull($invitation);
        $this->assertFalse($invitation->getAllowsPreDownload());
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithInvalidEmail()
    {
        // given
        $request = new Request();
        $request->request->set("mode", "send");
        $request->request->set("email_addresses", "first-valid@example.org\r\ninvalid@example.org\r\nsecond-valid@example.org");

        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à first-valid@example.org");
        $flashBag->shouldReceive("add")
            ->with("error", "La création de l'invitation pour invalid@example.org a échoué : L'adresse email n'est pas valide");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à second-valid@example.org");
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);

        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: ArticleType::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);

        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with("first-valid@example.org", "Téléchargez « Sent Book » en numérique", "Invitation");
        $mailer->shouldReceive("send")
            ->with("invalid@example.org", "Téléchargez « Sent Book » en numérique", "Invitation")
            ->andThrow(new InvalidEmailAddressException("L'adresse email n'est pas valide"));
        $mailer->shouldReceive("send")
            ->with("second-valid@example.org", "Téléchargez « Sent Book » en numérique", "Invitation");

        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(new Response("Invitation"));

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("first-valid@example.org"));
        $this->assertNull(InvitationQuery::create()->findOneByEmail("invvalid@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("second-valid@example.org"));
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithManualMode()
    {
        // given
        $request = new Request();
        $request->request->set("email_addresses", "manual1@example.org\r\nmanual2@example.org\r\nmanual3@example.org\r\n");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation à télécharger « Sent Book » a été créée pour manual1@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation à télécharger « Sent Book » a été créée pour manual2@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation à télécharger « Sent Book » a été créée pour manual3@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: ArticleType::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "manual");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->exactly(3))->method("renderResponse")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/admin/invitations");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
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
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithSendMode()
    {
        // given
        $request = new Request();
        $request->request->set("email_addresses", "send1@example.org\r\nsend2@example.org\r\nsend3@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à send1@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à send2@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Sent Book » a été envoyée à send3@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: ArticleType::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "send");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->exactly(3))->method("renderResponse")->willReturn(new Response("Invitation"));
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->exactly(3))->method("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/admin/invitations");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/invitations", $response->getTargetUrl());
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("send1@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("send2@example.org"));
        $this->assertNotNull(InvitationQuery::create()->findOneByEmail("send3@example.org"));
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithDownloadMode()
    {
        // given
        $request = new Request();
        $request->request->set("email_addresses", "download1@example.org\r\ndownload2@example.org\r\ndownload3@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à download1@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à download2@example.org");
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à download3@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: ArticleType::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article->getId()]);
        $request->request->set("mode", "download");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->exactly(3))->method("renderResponse")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("download1@example.org", $response->getContent());
        $this->assertStringContainsString("download2@example.org", $response->getContent());
        $this->assertStringContainsString("download3@example.org", $response->getContent());
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionWithAllowsPredownload()
    {
        // given
        $request = new Request();
        $request->request->set("email_addresses", "predownload@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour Sent Book a été envoyée à predownload@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: ArticleType::EBOOK, publisher: $publisher);
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
        $templateService->expects($this->once())->method("renderResponse")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldNotReceive("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $invitation = InvitationQuery::create()->findOneByEmail("predownload@example.org");
        $this->assertNotNull($invitation);
        $this->assertTrue($invitation->getAllowsPreDownload());
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     */
    public function testCreateActionForMultipleArticles()
    {
        // given
        $request = new Request();
        $request->request->set("email_addresses", "multiple@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Une invitation pour « Invited 1 » et 2 autres a été envoyée à multiple@example.org");
        $publisher = ModelFactory::createPublisher();
        $article1 = ModelFactory::createArticle(title: "Invited 1", typeId: ArticleType::EBOOK, publisher: $publisher);
        $article2 = ModelFactory::createArticle(title: "Invited 2", typeId: ArticleType::EBOOK, publisher: $publisher);
        $article3 = ModelFactory::createArticle(title: "Invited 3", typeId: ArticleType::EBOOK, publisher: $publisher);
        $request->request->set("article_ids", [$article1->getId(), $article2->getId(), $article3->getId()]);
        $request->request->set("mode", "send");
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->once())->method("renderResponse")->willReturn(new Response("Invitation"));
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with("multiple@example.org", "Téléchargez « Invited 1 » et 2 autres en numérique",
                Mockery::any());
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/invitation/ANEWCODE");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $controller = new InvitationController();

        // when
        $response = $controller->createAction(
            request: $request,
            currentSite: $currentSite,
            mailer: $mailer,
            templateService: $templateService,
            session: $session,
            urlGenerator: $urlGenerator,
            currentUser: $currentUser,
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
            title: "The Code Show", typeId: ArticleType::EBOOK, publisher: $publisher
        );
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "SHOWCODE", code: "SHOWCODE"
        );

        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthentified")->andReturn(true);
        $currentUser->shouldReceive("getUser")->andReturn($user);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->with("AppBundle:Invitation:show.html.twig", [
                "currentUser" => $currentUser,
                "invitation" => $invitation,
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
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testShowActionForAnonymousUser()
    {
        // given
        $controller = new InvitationController();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->with("AppBundle:Invitation:show-for-anonymous-user.html.twig")
            ->andReturn(new Response("Please log in."));

        // when
        $response = $controller->showAction(
            currentSite: $currentSite,
            currentUser: $currentUser,
            templateService: $templateService,
            code: "SHOWCODE"
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Please log in.", $response->getContent());
    }

    /**
     * #consume
     */

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
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $controller->consumeAction($request, $currentSite, $currentUser, $session, urlGenerator: $urlGenerator);
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
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $controller->consumeAction(request: $request, currentSite: $currentSite, currentUser: $currentUser, session: $session, urlGenerator: $urlGenerator);
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
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $controller->consumeAction(request: $request, currentSite: $currentSite, currentUser: $currentUser, session: $session, urlGenerator: $urlGenerator);
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
        $validPublisher = ModelFactory::createPublisher(name: "Éditeur autorisé");
        $validArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $validPublisher);
        $invalidPublisher = ModelFactory::createPublisher(name: "Éditeur filtré");
        $invalidArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $invalidPublisher);
        ModelFactory::createInvitation(
            site: $site, articles: [$validArticle, $invalidArticle], email: "UNAUTHPU", code: "UNAUTHPU"
        );
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $validPublisher->getId());
        $currentSite->setOption("downloadable_publishers", $validPublisher->getId());

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "UNAUTHPU");
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $controller->consumeAction(
            request: $request, currentSite: $currentSite, currentUser: $currentUser, session: $session, urlGenerator: $urlGenerator
        );
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
        $validPublisher = ModelFactory::createPublisher(name: "Éditeur autorisé");
        $validArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $validPublisher);
        $invalidPublisher = ModelFactory::createPublisher(name: "Éditeur non autorisé");
        $invalidArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $invalidPublisher);
        ModelFactory::createInvitation(
            site: $site, articles: [$validArticle, $invalidArticle], email: "NONDOPUB", code: "NONDOPUB"
        );
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", "{$validPublisher->getId()},{$invalidPublisher->getId()}");
        $currentSite->setOption("downloadable_publishers", $validPublisher->getId());

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "NONDOPUB");
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $controller->consumeAction(
            request: $request, currentSite: $currentSite, currentUser: $currentUser, session: $session, urlGenerator: $urlGenerator
        );
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
        $validArticle = ModelFactory::createArticle(title: "Livre papier", typeId: ArticleType::EBOOK);
        $invalidArticle = ModelFactory::createArticle(title: "Livre papier");
        ModelFactory::createInvitation(
            site: $site, articles: [$validArticle, $invalidArticle], email: "PAPERBOO", code: "PAPERBOO"
        );
        $publisherIds = "{$validArticle->getPublisherId()},{$invalidArticle->getPublisherId()}";
        $currentSite->setOption("publisher_filter", $publisherIds);
        $currentSite->setOption("downloadable_publishers", $publisherIds);

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "PAPERBOO");
        $currentUser = $this->createMock(CurrentUser::class);
        $session = $this->createMock(Session::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $controller->consumeAction(request: $request, currentSite: $currentSite, currentUser: $currentUser, session: $session, urlGenerator: $urlGenerator);
    }

    /**
     * @throws PropelException
     */
    public function testConsumeAction()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(title: "Livre numérique", typeId: ArticleType::EBOOK);
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "ALLRIGHT", code: "ALLRIGHT"
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);
        $user = ModelFactory::createUser();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "ALLRIGHT");
        $currentUser = new CurrentUser($user, "token");
        $flashBag = $this->createMock(FlashBag::class);
        $flashBag->expects($this->once())->method("add")
            ->with("success", "Livre numérique a été ajouté à votre bibliothèque.");
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method("getFlashBag")->willReturn($flashBag);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session, $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/user_library_url", $response->getTargetUrl());
        $consumedInvitation = InvitationQuery::create()->findPk($invitation->getId());
        $this->assertNotNull(
            $consumedInvitation->getConsumedAt(),
            "it consumes the invitation"
        );
        $articleInLibrary = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article)
            ->findOneByUserId($user->getId());
        $this->assertNotNull($articleInLibrary, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary->getSellingPrice());
        $this->assertNotNull($articleInLibrary->getSellingDate());
        $this->assertFalse($articleInLibrary->getAllowPredownload());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConsumeActionIgnoringArticleAlreadyInUserLibrary()
    {
        // given
        $user = ModelFactory::createUser();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $validArticle = ModelFactory::createArticle(title: "Autre article", typeId: ArticleType::EBOOK);
        $articleInLibrary = ModelFactory::createArticle(title: "Dans ma bibliothèque", typeId: ArticleType::EBOOK);
        ModelFactory::createInvitation(
            site: $site, articles: [$validArticle, $articleInLibrary], code: "ELIBRARY"
        );
        $publisherIds = "{$validArticle->getPublisherId()},{$articleInLibrary->getPublisherId()}";
        $currentSite->setOption("publisher_filter", $publisherIds);
        $currentSite->setOption("downloadable_publishers", $publisherIds);
        ModelFactory::createStockItem(site: $site, article: $articleInLibrary, user: $user);
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->once()
            ->with("success", "Autre article a été ajouté à votre bibliothèque.");
        $flashBag->shouldReceive("add")
            ->once()
            ->with("warning", "Dans ma bibliothèque était déjà dans votre bibliothèque.");
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->once()->andReturn($flashBag);

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest(user: $user);
        $request->request->set("code", "ELIBRARY");
        $currentUser = new CurrentUser($user, "token");

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session, urlGenerator: $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $newLibraryItem = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($validArticle)
            ->findOneByUserId($user->getId());
        $this->assertNotNull($newLibraryItem, "it adds the article to the user's library");
        $existingLibraryItemsCount = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($articleInLibrary)
            ->filterByUser($user)
            ->count();
        $this->assertEquals(1, $existingLibraryItemsCount);
    }

    /**
     * @throws PropelException
     */
    public function testConsumeActionWithAllowsPreDownloadOption()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $article = ModelFactory::createArticle(title: "Livre numérique", typeId: ArticleType::EBOOK);
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article], email: "ALLRIGHT", code: "ALLRIGHT",
            allowsPreDownload: true
        );
        $publisherId = $invitation->getArticles()->getFirst()->getPublisherId();
        $currentSite->setOption("publisher_filter", $publisherId);
        $currentSite->setOption("downloadable_publishers", $publisherId);
        $user = ModelFactory::createUser();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "ALLRIGHT");
        $currentUser = new CurrentUser($user, "token");
        $flashBag = $this->createMock(FlashBag::class);
        $flashBag->expects($this->once())->method("add")
            ->with("success", "Livre numérique a été ajouté à votre bibliothèque.");
        $session = $this->createMock(Session::class);
        $session->expects($this->once())->method("getFlashBag")->willReturn($flashBag);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session, urlGenerator: $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/user_library_url", $response->getTargetUrl());
        $consumedInvitation = InvitationQuery::create()->findPk($invitation->getId());
        $this->assertNotNull(
            $consumedInvitation->getConsumedAt(),
            "it consumes the invitation"
        );
        $articleInLibrary = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article)
            ->findOneByUserId($user->getId());
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
        $publisher = ModelFactory::createPublisher();
        $article1 = ModelFactory::createArticle(
            title: "Multiple 1", typeId: ArticleType::EBOOK, publisher: $publisher
        );
        $article2 = ModelFactory::createArticle(
            title: "Multiple 2", typeId: ArticleType::EBOOK, publisher: $publisher
        );
        $article3 = ModelFactory::createArticle(
            title: "Multiple 3", typeId: ArticleType::EBOOK, publisher: $publisher
        );
        $invitation = ModelFactory::createInvitation(
            site: $site, articles: [$article1, $article2, $article3],
            email: "multiple@example.org", code: "ALLRIGHT"
        );
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $user = ModelFactory::createUser();

        $controller = new InvitationController();
        $request = RequestFactory::createAuthRequest();
        $request->request->set("code", "ALLRIGHT");
        $currentUser = new CurrentUser($user, "token");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Multiple 1 a été ajouté à votre bibliothèque.");
        $flashBag->shouldReceive("add")
            ->with("success", "Multiple 2 a été ajouté à votre bibliothèque.");
        $flashBag->shouldReceive("add")
            ->with("success", "Multiple 3 a été ajouté à votre bibliothèque.");
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->andReturn($flashBag);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("/user_library_url");

        // when
        $response = $controller->consumeAction($request, $currentSite, $currentUser, $session, urlGenerator: $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/user_library_url", $response->getTargetUrl());
        $consumedInvitation = InvitationQuery::create()->findPk($invitation->getId());
        $this->assertNotNull(
            $consumedInvitation->getConsumedAt(),
            "it consumes the invitation"
        );

        $articleInLibrary1 = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article1)
            ->findOneByUserId($user->getId());
        $this->assertNotNull($articleInLibrary1, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary1->getSellingPrice());
        $this->assertNotNull($articleInLibrary1->getSellingDate());

        $articleInLibrary2 = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article2)
            ->findOneByUserId($user->getId());
        $this->assertNotNull($articleInLibrary2, "it adds the article to the user's library");
        $this->assertEquals(0, $articleInLibrary2->getSellingPrice());
        $this->assertNotNull($articleInLibrary2->getSellingDate());

        $articleInLibrary3 = StockQuery::create()
            ->filterBySite($site)
            ->filterByArticle($article3)
            ->findOneByUserId($user->getId());
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
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle(title: "Listed Book", typeId: ArticleType::EBOOK);
        ModelFactory::createInvitation(
            site: $site,
            articles: [$article],
            email: "listed-invitation@biblys.fr",
            code: "LISTEDIN",
        );
        $request = new Request();
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->once()->andReturn(new Response("LISTEDIN"));
        $controller = new InvitationController();

        // when
        $response = $controller->listAction($request, $currentSite, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("LISTEDIN", $response->getContent());
    }

    /**
     * @throws PropelException
     */
    public function testDeleteAction()
    {
        // given
        $invitation = ModelFactory::createInvitation(email: "delete.me@example.org");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")->with(
            "success",
            "L'invitation pour delete.me@example.org a été supprimée."
        );
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $controller = new InvitationController();

        // when
        $response = $controller->deleteAction($session, $currentUser, $invitation->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/invitations", $response->getTargetUrl());
        $this->assertNull(InvitationQuery::create()->findPk($invitation->getId()));
    }
}
