<?php

use AppBundle\Controller\ErrorController;
use Framework\Exception\AuthException;
use Framework\Exception\ServiceUnavailableException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    }

    public function testHandleUnauthorizedAccess()
    {
        // given
        $controller = new ErrorController();
        $request = new Request();
        $exception = new AuthException("Unauthorized");
        $axys = new Biblys\Axys\Client();

        // when
        $response = $controller->exception($request, $exception, $axys);

        // then
        $this->assertEquals(
            401,
            $response->getStatusCode(),
            "it should response with HTTP status 401"
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
}