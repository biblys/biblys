<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Exception;
use PHPUnit\Framework\TestCase;

class StatsControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testMatomo()
    {
        // given
        $controller = new StatsController();
        $config = new Config([
            "matomo" => [
                "domain" => "example.org",
                "login" => "login",
                "md5pass" => "password",
            ]
        ]);

        // when
        $response = $controller->matomo($config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "https://example.org/index.php?module=Login&action=logme&login=login&password=password",
            $response->getTargetUrl(),
        );
    }

    /**
     * @throws Exception
     */
    public function testUmami()
    {
        // given
        $controller = new StatsController();
        $config = new Config(["umami" => ["share_url" => "https://example.org/umami"]]);

        // when
        $response = $controller->umami($config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://example.org/umami", $response->getTargetUrl());
    }
}
