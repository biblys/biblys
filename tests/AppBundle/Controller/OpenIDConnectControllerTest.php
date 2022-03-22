<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use OpenIDConnectClient\Exception\InvalidTokenException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class OpenIDConnectControllerTest extends TestCase
{
    public function testAxys()
    {
        // given
        $controller = new OpenIDConnectController();
        $axysConfig = [
            "client_id" => "test_client_id",
            "client_secret" => "test_client_secret",
            "redirect_uri" => "https://librys.fr/callback",
        ];
        $config = $this->createMock(Config::class);
        $config->method("get")->willReturn($axysConfig);

        // when
        $response = $controller->axys($config);

        // then
        $authorizeUrlMatcher = '/https:\/\/axys.me\/openid\/authorize\?scope=openid%20email&state=[a-z0-9]{32}&response_type=code&approval_prompt=auto&redirect_uri=https%3A%2F%2Flibrys\.fr%2Fcallback&client_id=test_client_id/';
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertMatchesRegularExpression(
            $authorizeUrlMatcher,
            $response->headers->get("Location")
        );
    }
}
