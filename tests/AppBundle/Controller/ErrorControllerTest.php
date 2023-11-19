<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Test\ModelFactory;
use Biblys\Service\TemplateService;
use Exception;
use Framework\Exception\AuthException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class ErrorControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandlePageNotFound()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new ResourceNotFoundException("Page not found");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("publisher_filter")->andReturn(null);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Page not found"));
        $config = Mockery::mock(Config::class);
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            404,
            $response->getStatusCode(),
            "it should response with HTTP status 404"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testHandlePageNotFoundMatchingAnArticleUrl()
    {
        // given
        ModelFactory::createArticle(url: "author/article_404");
        $controller = new ErrorController();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive("getBaseUrl")->andReturn("");
        $request->shouldReceive("getPathInfo")->andReturn("/author/article_404");
        $exception = new ResourceNotFoundException("Page not found");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("publisher_filter")->andReturn(null);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("article_show", ["slug" => "author/article_404"])
            ->andReturn("/a/author/article_404");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Page not found"));
        $config = Mockery::mock(Config::class);
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            301,
            $response->getStatusCode(),
            "responds with HTTP status 301"
        );
        $this->assertEquals(
            "/a/author/article_404",
            $response->headers->get("Location"),
            "redirects to the article url"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testHandlePageNotFoundMatchingAContributorUrl()
    {
        // given
        ModelFactory::createContributor(url: "great-author");
        $controller = new ErrorController();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive("getBaseUrl")->andReturn("");
        $request->shouldReceive("getPathInfo")->andReturn("/great-author/");
        $exception = new ResourceNotFoundException("Page not found");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("publisher_filter")->andReturn(null);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("people_show", ["slug" => "great-author"])
            ->andReturn("/p/great-author/");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Page not found"));
        $config = Mockery::mock(Config::class);
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService, $exception
        );

        // then
        $this->assertEquals(
            301,
            $response->getStatusCode(),
            "responds with HTTP status 301"
        );
        $this->assertEquals(
            "/p/great-author/",
            $response->headers->get("Location"),
            "redirects to the article url"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandlePageNotFoundAsJson()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new ResourceNotFoundException("Page not found");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("publisher_filter")->andReturn(null);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            404,
            $response->getStatusCode(),
            "it should response with HTTP status 404"
        );
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\JsonResponse",
            $response,
            "it should return a JsonResponse"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleBadRequest()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new BadRequestHttpException("Bad request");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Requête invalide"));
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "it should response with HTTP status 400"
        );
        $this->assertStringContainsString(
            "Requête invalide",
            $response->getContent(),
            "it should response with HTTP status 400"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleUnauthorized()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new UnauthorizedHttpException("User should login.");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("user_login", [
                "return_url" => "/current",
            ])
            ->andReturn("/user/login?return_url=/current");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Erreur d'authentification"));
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $currentUrlService->shouldReceive("getRelativeUrl")
            ->andReturn("/current");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("info", "Vous devez vous connecter pour continuer.");
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->andReturn($flashBag);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should response with HTTP status 401"
        );
        $this->assertEquals(
            "/user/login?return_url=/current",
            $response->headers->get("Location"),
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleAccessDenied()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new AccessDeniedHttpException("Access if forbidden for user.");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Accès refusé"));
        $config = Mockery::mock(Config::class);
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            403,
            $response->getStatusCode(),
            "responds with HTTP status 403"
        );
        $this->assertStringContainsString(
            "Accès refusé",
            $response->getContent(),
            "returns the error title"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleLegacyAuthException()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new AuthException("Unauthorized");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Erreur d'authentification"));
        $urlGenerator->shouldReceive("generate")
            ->with("user_login", [
                "return_url" => "/current",
            ])
            ->andReturn("/user/login?return_url=/current");
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $currentUrlService->shouldReceive("getRelativeUrl")
            ->andReturn("/current");
        $session = Mockery::mock(Session::class);
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("info", "Vous devez vous connecter pour continuer.");
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")->andReturn($flashBag);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should response with HTTP status 302"
        );
        $this->assertEquals(
            "/user/login?return_url=/current",
            $response->headers->get("Location"),
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testMethodNotAllowedAsJson()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new MethodNotAllowedHttpException(["GET"], "Method PUT is not allowed");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            405,
            $response->getStatusCode(),
            "it should response with HTTP status 405"
        );
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\JsonResponse",
            $response,
            "it should return a JsonResponse"
        );
        $this->assertStringContainsString(
            "Method PUT is not allowed",
            $response->getContent(),
            "it should contain error message"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleConflictAsJson()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new ConflictHttpException("Cannot add article to cart because it is unavailable.");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            409,
            $response->getStatusCode(),
            "it should response with HTTP status 409"
        );
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\JsonResponse",
            $response,
            "it should return a JsonResponse"
        );
        $this->assertStringContainsString(
            "Cannot add article to cart because it is unavailable.",
            $response->getContent(),
            "it should contain error message"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleServerError()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new Exception("An error occurred");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("An error occurred"));
        $config = Mockery::mock(Config::class);
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            500,
            $response->getStatusCode(),
            "it should response with HTTP status 500"
        );
        $this->assertStringContainsString("An error occurred", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleServerErrorAsJson()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new Exception("An error occurred");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("environment")->andReturn("dev");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);
        $templateService = Mockery::mock(TemplateService::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            500,
            $response->getStatusCode(),
            "it should response with HTTP status 500"
        );
        $this->assertEquals("application/json", $response->headers->get("Content-Type"));
        $json = json_decode($response->getContent(), true);
        $this->assertEquals("An error occurred", $json["error"]["message"]);
        $this->assertEquals("Exception", $json["error"]["exception"]);
        $this->assertStringContainsString(
            "/tests/AppBundle/Controller/ErrorControllerTest.php",
            $json["error"]["file"]
        );
        $this->assertArrayHasKey("line", $json["error"]);
        $this->assertIsArray($json["error"]["trace"]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleServerErrorAsJsonInProductionEnvironment()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new Exception("An error occurred");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("environment")
            ->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);
        $templateService = Mockery::mock(TemplateService::class);


        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            500,
            $response->getStatusCode(),
            "it should response with HTTP status 500"
        );
        $this->assertEquals("application/json", $response->headers->get("Content-Type"));
        $json = json_decode($response->getContent(), true);
        $this->assertEquals("An error occurred", $json["error"]["message"]);
        $this->assertArrayNotHasKey("exception", $json["error"]);
        $this->assertArrayNotHasKey("file", $json["error"]);
        $this->assertArrayNotHasKey("line", $json["error"]);
        $this->assertArrayNotHasKey("trace", $json["error"]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function testHandleServiceUnavailable()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new ServiceUnavailableHttpException(60);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->once()
            ->andReturn(new Response("Service temporairement indisponible"));
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("environment")->andReturn("prod");
        $currentUrlService = Mockery::mock(CurrentUrlService::class);
        $session = Mockery::mock(Session::class);

        // when
        $response = $controller->exception(
            $request,
            $config,
            $currentSite,
            $currentUrlService,
            $urlGenerator,
            $session,
            $templateService,
            $exception
        );

        // then
        $this->assertEquals(
            503,
            $response->getStatusCode(),
            "it should response with HTTP status 500"
        );
        $this->assertStringContainsString(
            "Service temporairement indisponible",
            $response->getContent()
        );
        $this->assertEquals(
            60,
            $response->headers->get("Retry-After"),
            "it should include Retry-After header"
        );
    }
}
