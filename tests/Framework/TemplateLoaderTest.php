<?php

namespace Framework;

use Biblys\Service\CurrentSite;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__."/../setUp.php";

class TemplateLoaderTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testFindLayoutTemplateDefault()
    {
        // given
        $currentSite = $this->createMock(CurrentSite::class);
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method("exists")->willReturnOnConsecutiveCalls(false, true);
        $loader = new TemplateLoader($currentSite, $filesystem);

        // when
        $templatePath = $loader->getCacheKey("layout:base.html.twig");

        // then
        $this->assertStringEndsWith(
            "AppBundle/Resources/layout/base.html.twig",
            $templatePath
        );
    }

    /**
     * @throws Exception
     */
    public function testFindLayoutTemplateCustom()
    {
        // given
        $currentSite = $this->createMock(CurrentSite::class);
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method("exists")->willReturnOnConsecutiveCalls(true, false);
        $loader = new TemplateLoader($currentSite, $filesystem);

        // when
        $templatePath = $loader->getCacheKey("layout:base.html.twig");

        // then
        $this->assertStringEndsWith(
            "../../app/layout/base.html.twig",
            $templatePath
        );
    }
}
