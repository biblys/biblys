<?php

namespace AppBundle\Controller;

use Axys\AxysOpenIDConnectProvider;
use Biblys\Service\Axys;
use OpenIDConnectClient\AccessToken;
use OpenIDConnectClient\Exception\InvalidTokenException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class OpenIDConnectControllerTest extends TestCase
{
    public function testAxys()
    {
        // given
        $oidcProvider = $this->createMock(AxysOpenIDConnectProvider::class);
        $oidcProvider->method("getAuthorizationUrl")->willReturn("https://axys.me/authorize");
        $axys = $this->createMock(Axys::class);
        $axys->method("getOpenIDConnectProvider")->willReturn($oidcProvider);
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->axys($axys);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://axys.me/authorize", $response->getTargetUrl());
    }

    /**
     * @throws InvalidTokenException
     */
    public function testCallback()
    {
        // given
        $request = new Request();
        $request->query->set("code", "authorization_code");
        $controller = new OpenIDConnectController();
        $accessToken = $this->createMock(AccessToken::class);
        $accessToken->method("getIdToken")->willReturn("id_token_value");
        $oidcProvider = $this->createMock(AxysOpenIDConnectProvider::class);
        $oidcProvider->method("getAccessToken")
            ->with("authorization_code", ["code" => "authorization_code"])
            ->willReturn($accessToken);
        $axys = $this->createMock(Axys::class);
        $axys->method("getOpenIDConnectProvider")->willReturn($oidcProvider);

        // when
        $response = $controller->callback($request, $axys);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/", $response->getTargetUrl());
        $this->assertEquals("id_token", $response->headers->getCookies()[0]->getName());
        $this->assertEquals("id_token_value", $response->headers->getCookies()[0]->getValue());
    }
}
