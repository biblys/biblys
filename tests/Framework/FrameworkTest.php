<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__."/../setUp.php";

class FrameworkTest extends TestCase
{
    public function testRedirectAfterAxysLogin()
    {
        // given
        $_GET = ["id" => 1, "UID" => "abcd1234"];
        $_SERVER = [
            "HTTP_HOST" => "example.org",
            "REQUEST_URI" => "/pages/adm_article?id=1&UID=abcd1234",
            "REQUEST_TIME" => 1616700639,
        ];
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);
        $controllerResolver = new ControllerResolver();
        $dispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
        $framework = new Framework\Framework($dispatcher, $controllerResolver);

        // when
        $response = $framework->handle($request);

        // then
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response
        );
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "/pages/adm_article?id=1",
            $response->headers->get("Location")
        );
        $this->assertEquals(
            "/pages/adm_article?id=1",
            $response->headers->get("Location")
        );
    }
}