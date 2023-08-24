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
use Mockery;
use Model\InvitationQuery;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\ActiveQuery\Criteria;
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
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testNewAction()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $publisher = ModelFactory::createPublisher();
        ModelFactory::createArticle(title: "Book", publisher: $publisher);
        $ebook = ModelFactory::createArticle(title: "E-book", typeId: Type::EBOOK, publisher: $publisher);
        $controller = new InvitationController();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $templateService = $this->createMock(TemplateService::class);
        $templateService
            ->expects($this->once())
            ->method("render")
            ->with("AppBundle:Invitation:new.html.twig", [
                "downloadableArticles" => [$ebook],
            ])
            ->willReturn(new Response("Créer une invitation de téléchargement"));

        // when
        $response = $controller->newAction($request, $currentSite, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            "Créer une invitation de téléchargement",
            $response->getContent()
        );
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws SyntaxError
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     */
    public function testCreateAction()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->request->set("email_address", "user@example.org");
        $flashBag = $this->createMock(FlashBag::class);
        $flashBag->method("add")->with("success", "Une invitation pour Sent Book a été envoyée à user@example.org");
        $publisher = ModelFactory::createPublisher();
        $article = ModelFactory::createArticle(title: "Sent Book", typeId: Type::EBOOK, publisher: $publisher);
        $request->request->set("article_id", $article->getId());
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn($flashBag);
        $templateService = $this->createMock(TemplateService::class);
        $templateService->expects($this->once())->method("renderFromString")->willReturn(new Response("Invitation"));
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/invitation/new");
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
        $this->assertEquals("/invitation/new", $response->getTargetUrl());
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
        ModelFactory::createInvitation(site: $site, article: $article, code: "SHOWCODE");

        $currentSite = new CurrentSite($site);
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $currentUser = $this->createMock(CurrentUser::class);
        $templateService = $this->createMock(TemplateService::class);
        $templateService
            ->expects($this->once())
            ->method("render")
            ->with("AppBundle:Invitation:show.html.twig", [
                "articleTitle" => "The Code Show",
                "currentUser" => $currentUser,
                "code" => "SHOWCODE",
                "error" => null,
            ])
            ->willReturn(new Response("Télécharger The Code Show"));
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
        ModelFactory::createInvitation(site: $site, article: $article, code: "NONDOPUB");
        $currentSite = new CurrentSite($site);

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
        $invitation = ModelFactory::createInvitation(site: $site, article: $article, code: "PAPERBOO");
        $publisherId = $invitation->getArticle()->getPublisherId();
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
        $invitation = ModelFactory::createInvitation(site: $site, article: $article, code: "ELIBRARY");
        $publisherId = $invitation->getArticle()->getPublisherId();
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
        $invitation = ModelFactory::createInvitation(site: $site, article: $article, code: "ALLRIGHT");
        $publisherId = $invitation->getArticle()->getPublisherId();
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
}
