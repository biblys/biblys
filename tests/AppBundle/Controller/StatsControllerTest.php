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
}
