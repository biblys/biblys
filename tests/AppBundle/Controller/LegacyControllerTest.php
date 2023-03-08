<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once(__DIR__."/../../setUp.php");

class LegacyControllerTest extends TestCase
{
    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultAction()
    {
        // given
        $request = new Request();
        $request->query->set("page", "bientot");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $response = $legacyController->defaultAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator,
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "it should respond with status code 200"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionRequiringLogin()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise");

        // given
        $request = new Request();
        $request->query->set("page", "log_page");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $legacyController->defaultAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator,
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionRequiringPublisherRight()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas l'autorisation de modifier un éditeur.");

        // given
        $request = RequestFactory::createAuthRequest();
        $request->query->set("page", "pub_page");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $legacyController->defaultAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator,
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionRequiringAdminRight()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Accès réservé aux administrateurs.");

        // given
        $request = RequestFactory::createAuthRequestForPublisherUser();
        $request->query->set("page", "adm_page");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $legacyController->defaultAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator,
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionForStaticPagesLegacyRoute()
    {
        // given
        $request = new Request();
        $request->query->set("page", "page-statique");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("static_page_show", ["slug" => "page-statique"])
            ->willReturn("/page/page-statique");

        // when
        $response = $legacyController->defaultAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator,
        );

        // then
        $this->assertEquals(
            "301",
            $response->getStatusCode(),
            "responds with status code 301"
        );
        $this->assertEquals(
            "/page/page-statique",
            $response->headers->get("location"),
            "redirects to new static page url"
        );
    }
}
