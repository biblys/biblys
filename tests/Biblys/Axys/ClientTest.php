<?php

namespace Biblys\Axys;

use Axys\LegacyClient;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGetWidgetUrl()
    {
        // given
        $axys = new LegacyClient();

        // when
        $widgetUrl = $axys->getWidgetUrl();

        // then
        $this->assertEquals(
            "https://axys.me/widget.php?version=1",
            $widgetUrl,
            "it returns widget url"
        );
    }

    public function testGetWidgetUrlWithTokenAsInstanceProperty()
    {
        // given
        $axys = new LegacyClient([], "userToken12345");

        // when
        $widgetUrl = $axys->getWidgetUrl();

        // then
        $this->assertEquals(
            "https://axys.me/widget.php?version=1&UID=userToken12345",
            $widgetUrl,
            "it returns widget url with uid"
        );
    }
}
