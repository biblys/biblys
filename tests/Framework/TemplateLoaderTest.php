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

    /**
     * @throws Exception
     */
    public function testFindLayoutTemplateUsingLegacyBuilder()
    {
        // given
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->willReturn("1");
        $filesystem = $this->createMock(Filesystem::class);
        $loader = new TemplateLoader($currentSite, $filesystem);

        // when
        $templatePath = $loader->getCacheKey("layout:base.html.twig");

        // then
        $this->assertStringEndsWith(
            "AppBundle/Resources/layout/base_for_legacy_builder.html.twig",
            $templatePath
        );
    }

    /**
     * @throws Exception
     */
    public function testFindDefaultViewTemplateUsingLegacyBuilder()
    {
        // given
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->willReturn("1");
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method("exists")->willReturnOnConsecutiveCalls(false, true);
        $loader = new TemplateLoader($currentSite, $filesystem);

        // when
        $templatePath = $loader->getCacheKey("AppBundle:Main:home.html.twig");

        // then
        $this->assertStringEndsWith(
            "AppBundle/Resources/views/Main/home.html.twig",
            $templatePath
        );
    }

    /**
     * @throws Exception
     */
    public function testFindCustomViewTemplateUsingLegacyBuilder()
    {
        // given
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->willReturn("1");
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method("exists")->willReturnOnConsecutiveCalls(true, false);
        $loader = new TemplateLoader($currentSite, $filesystem);

        // when
        $templatePath = $loader->getCacheKey("AppBundle:Main:home.html.twig");

        // then
        $this->assertStringEndsWith(
            "app/Resources/AppBundle/views/Main/home.html.twig",
            $templatePath
        );
    }
}
