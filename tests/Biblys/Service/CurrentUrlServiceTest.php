<?php

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CurrentUrlServiceTest extends TestCase
{
    public function testGetRelativeUrl()
    {
        // given

        $request = $this->createMock(Request::class);
        $request->method("getSchemeAndHttpHost")->willReturn("https://www.paronymie.fr");
        $request->method("getBaseUrl")->willReturn("/admin");
        $request->method("getPathInfo")->willReturn("/current-page");
        $currentUrlService = new CurrentUrlService($request);

        // when
        $url = $currentUrlService->getRelativeUrl();

        // then
        $this->assertEquals(
            "/admin/current-page",
            $url,
            "returns current url"
        );
    }

    public function testGetAbsoluteUrl()
    {
        // given
        $request = $this->createMock(Request::class);
        $request->method("getSchemeAndHttpHost")->willReturn("https://www.paronymie.fr");
        $request->method("getBaseUrl")->willReturn("/admin");
        $request->method("getPathInfo")->willReturn("/current-page");
        $currentUrlService = new CurrentUrlService($request);

        // when
        $url = $currentUrlService->getAbsoluteUrl();

        // then
        $this->assertEquals(
            "https://www.paronymie.fr/admin/current-page",
            $url,
            "returns current url"
        );
    }
}
