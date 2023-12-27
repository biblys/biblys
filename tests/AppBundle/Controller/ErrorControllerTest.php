<?php

namespace AppBundle\Controller;

use Exception;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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
     * @throws PropelException
     */
    public function testHandlePageNotFound()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new ResourceNotFoundException("Page not found");

        // when
        $response = $controller->exception($request, $exception);

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
    public function testHandlePageNotFoundAsJson()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new ResourceNotFoundException("Page not found");

        // when
        $response = $controller->exception($request, $exception);

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
     * @throws PropelException
     */
    public function testHandleBadRequest()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new BadRequestHttpException("Bad request");

        // when
        $response = $controller->exception($request, $exception);

        // then
        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "it should response with HTTP status 400"
        );
        $this->assertStringContainsString(
            "Requête invalid",
            $response->getContent(),
            "it should response with HTTP status 400"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testHandleUnauthorized()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new UnauthorizedHttpException("User should login.");

        // when
        $response = $controller->exception($request, $exception);

        // then
        $this->assertEquals(
            401,
            $response->getStatusCode(),
            "it should response with HTTP status 401"
        );
        $this->assertStringContainsString(
            "Erreur d'authentification",
            $response->getContent(),
            "it should return the error message"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testHandleAccessDenied()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new AccessDeniedHttpException("Access if forbidden for user.");

        // when
        $response = $controller->exception($request, $exception);

        // then
        $this->assertEquals(
            403,
            $response->getStatusCode(),
            "it should response with HTTP status 403"
        );
        $this->assertStringContainsString(
            "Accès refusé",
            $response->getContent(),
            "it should return the error title"
        );
        $this->assertStringContainsString(
            "Vous n'êtes pas autorisé à accéder à cette page.",
            $response->getContent(),
            "it should return the error message"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testHandleLegacyAuthException()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new AuthException("Unauthorized");

        // when
        $response = $controller->exception($request, $exception);

        // then
        $this->assertEquals(
            401,
            $response->getStatusCode(),
            "it should response with HTTP status 401"
        );
        $this->assertStringContainsString(
            "Erreur d'authentification",
            $response->getContent(),
            "it should return the error message"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testMethodNotAllowed()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new MethodNotAllowedHttpException(["GET"],"Method PUT is not allowed");

        // when
        $response = $controller->exception($request, $exception);

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
     * @throws PropelException
     */
    public function testHandleConflict()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");
        $exception = new ConflictHttpException("Cannot add article to cart because it is unavailable.");

        // when
        $response = $controller->exception($request, $exception);

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
     * @throws PropelException
     */
    public function testHandleServerError()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new Exception("An error occurred");

        // when
        $response = $controller->exception($request, $exception);

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
     * @throws PropelException
     */
    public function testHandleServiceUnavailable()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new ServiceUnavailableHttpException(60);

        // when
        $response = $controller->exception($request, $exception);

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
        $this->assertStringContainsString(
            "Merci de réessayer dans quelques instants",
            $response->getContent()
        );
        $this->assertEquals(
            60,
            $response->headers->get("Retry-After"),
            "it should include Retry-After header"
        );
    }
}
