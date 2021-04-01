<?php


namespace Framework;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../setUp.php";

class ExceptionControllerTest extends TestCase
{
    public function testHandlePageNotFound()
    {
        // given
        $controller = new ExceptionController();
        $request = new Request();

        // when
        $response = $controller->handlePageNotFound($request,"Page not found");

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
        $controller = new ExceptionController();
        $request = new Request();
        $request->headers->set("Accept", "application/json");

        // when
        $response = $controller->handlePageNotFound($request, "Page not found");

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
}