<?php

namespace Axys;

use Biblys\Service\Config;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../setUp.php";

class LegacyClientTest extends TestCase
{
    public function testGetSignUpUrl()
    {
        // given
        $client = new LegacyClient();

        // when
        $signUpUrl = $client->getSignUpUrl();

        // then
        $this->assertEquals(
            "https://axys.me/#Inscription",
            $signUpUrl
        );
    }

    public function testGetWidgetUrl()
    {
        // given
        $client = new LegacyClient();

        // when
        $widgetUrl = $client->getWidgetUrl();

        // then
        $this->assertEquals(
            "https://axys.me/widget.php",
            $widgetUrl
        );
    }
}
