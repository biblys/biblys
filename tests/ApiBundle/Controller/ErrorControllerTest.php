<?php

namespace ApiBundle\Controller;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . "/../../setUp.php";

class ErrorControllerTest extends TestCase
{
    public function testResourceNotFoundException()
    {
        // given
        $controller = new ErrorController();
        $exception = new ResourceNotFoundException("Resource not found");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            404,
            $response->getStatusCode(),
            "it should response with HTTP status 404"
        );
        $this->assertEquals(
            "Resource not found",
            $json->error->message,
            "it should display error message"
        );
    }

    public function testNotFoundHttpException()
    {
        // given
        $controller = new ErrorController();
        $exception = new NotFoundHttpException("Not found HTTP");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            404,
            $response->getStatusCode(),
            "it should response with HTTP status 404"
        );
        $this->assertEquals(
            "Not found HTTP",
            $json->error->message,
            "it should display error message"
        );
    }

    public function testHandleServerError()
    {
        // given
        $controller = new ErrorController();
        $exception = new Exception("Internal server error");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            500,
            $response->getStatusCode(),
            "it should response with HTTP status 500"
        );
        $this->assertEquals(
            "Internal server error",
            $json->error->message,
            "it should display error message"
        );
        $this->assertEquals(
            "Exception",
            $json->error->exception,
        );
    }
}