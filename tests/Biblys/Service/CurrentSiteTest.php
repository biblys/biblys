<?php

namespace Biblys\Service;

use Biblys\Test\Factory;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../../setUp.php";

class CurrentSiteTest extends TestCase
{

    public function testBuildFromConfig()
    {
        // given
        $site = Factory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());

        // when
        $currentSite = CurrentSite::buildFromConfig($config);

        // then
        $this->assertEquals(
            $site->getId(),
            $currentSite->getSite()->getId(),
            "it returns site from Config"
        );
    }
}
