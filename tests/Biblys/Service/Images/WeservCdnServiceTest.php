<?php

namespace Biblys\Service\Images;

use PHPUnit\Framework\TestCase;

class WeservCdnServiceTest extends TestCase
{

    public function testBuildUrl()
    {
        // given
        $localUrl = "https://www.biblys.fr/images/1955.jpg";
        $service = new WeservCdnService();

        // when
        $url = $service->buildUrl($localUrl);

        // then
        $this->assertEquals(
            "//images.weserv.nl/?url=https%3A%2F%2Fwww.biblys.fr%2Fimages%2F1955.jpg",
            $url,
            "builds url for Weserv CDN",
        );
    }
}
