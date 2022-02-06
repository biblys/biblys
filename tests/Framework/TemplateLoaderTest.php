<?php

namespace Framework;

use Biblys\Test\EntityFactory;
use Exception;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../setUp.php";

class TemplateLoaderTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testFindLayoutTemplate()
    {
        $this->markTestSkipped("Flaky test");

        // given
        $site = EntityFactory::createSite();
        $loader = new TemplateLoader($site);

        // when
        $templatePath = $loader->getCacheKey("layout:base.html.twig");

        // then
        $this->assertStringEndsWith(
            "AppBundle/Resources/layout/base.html.twig",
            $templatePath
        );
    }
}
