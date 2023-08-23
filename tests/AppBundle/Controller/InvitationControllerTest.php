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
use PHPUnit\Framework\TestCase;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
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
        $article = ModelFactory::createArticle(title: "The Code Show", typeId: Type::EBOOK);
        ModelFactory::createInvitation(site: $site, article: $article, code: "SHOWCODE");

        $currentSite = new CurrentSite($site);
        $currentUser = $this->createMock(CurrentUser::class);
        $templateService = $this->createMock(TemplateService::class);
        $templateService
            ->expects($this->once())
            ->method("render")
            ->with("AppBundle:Invitation:show.html.twig", [
                "articleTitle" => "The Code Show",
                "currentUser" => $currentUser,
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
}
