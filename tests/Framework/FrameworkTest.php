<?php

use Symfony\Component\HttpFoundation\Request;

require_once "tests/setUp.php";

class FrameworkTest extends \PHPUnit\Framework\TestCase
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
        $framework = new Framework\Framework();

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