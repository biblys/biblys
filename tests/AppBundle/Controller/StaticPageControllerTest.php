<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class StaticPageControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testShowAction()
    {
        // given
        $controller = new StaticPageController();
        ModelFactory::createPage([
            "page_title" => "Mentions légales",
            "page_url" => "mentions-legales",
            "content" => "Merci de lire attentivement le texte suivant."
        ]);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        $request = new Request();

        // when
        $response = $controller->showAction($request, $currentSite, "mentions-legales");

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "responds with http status 400",
        );
        $this->assertStringContainsString(
            "Mentions légales",
            $response->getContent(),
            "inserts static page title in body"
        );
        $this->assertStringContainsString(
            "Merci de lire attentivement le texte suivant.",
            $response->getContent(),
            "insert static page content in body"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testShowActionForInvalidSlugs()
    {
        // then
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Cannot find a static page with slug \"page-inexistante\".");

        // given
        $controller = new StaticPageController();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        $request = new Request();

        // when
        $controller->showAction($request, $currentSite,  "page-inexistante");
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testShowActionForOfflinePages()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Page \"offline-page\" is offline.");

        // given
        $controller = new StaticPageController();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        ModelFactory::createPage(["page_url" => "offline-page", "status" => 0]);
        $request = RequestFactory::createAuthRequest();

        // when
        $controller->showAction($request, $currentSite, "offline-page");
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testShowActionForOfflinePageViewedByAdmin()
    {
        // given
        $controller = new StaticPageController();
        ModelFactory::createPage(["page_url" => "for-admin-only", "status" => 0]);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        $request = RequestFactory::createAuthRequestForAdminUser();

        // when
        $response = $controller->showAction($request, $currentSite, "for-admin-only");

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "responds with http status 200",
        );
        $this->assertStringContainsString(
            "Cette page est hors-ligne et n'est prévisualisable que par les administrateurs.",
            $response->getContent(),
            "inserts admin preview warning"
        );
    }
}
