<?php

namespace AppBundle\Controller;

use Axys\LegacyClient;
use Exception;
use Framework\Exception\AuthException;
use Framework\Exception\ServiceUnavailableException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . "/../../setUp.php";

class ErrorControllerTest extends TestCase
{
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

    public function testHandleUnauthorizedAccess()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new AuthException("Unauthorized");
        $axys = new LegacyClient();

        // when
        $response = $controller->exception($request, $exception, $axys);

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

    public function testHandleServiceUnavailable()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new ServiceUnavailableException("Site is closed");

        // when
        $response = $controller->exception($request, $exception);

        // then
        $this->assertEquals(
            503,
            $response->getStatusCode(),
            "it should response with HTTP status 503"
        );
    }

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

    public function testFallbackToLegacyController()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $request->query->set("page", "bientot");
        $exception = new NotFoundHttpException();
        $GLOBALS["originalRequest"] = $request;

        // when
        $response = $controller->exception($request, $exception);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should response with HTTP status 200"
        );
        $this->assertEquals(
            "true",
            $response->headers->get("SHOULD_RESET_STATUS_CODE_TO_200"),
            "it should include header SHOULD_RESET_STATUS_CODE_TO_200"
        );
    }
}