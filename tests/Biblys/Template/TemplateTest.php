<?php

namespace Biblys\Template;

use Exception;
use PHPUnit\Framework\TestCase;
use Site;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__."/../../setUp.php";

class TemplateTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUpdateContentForHomeTemplate()
    {
        // given
        $template = Template::get("home");
        $siteStub = $this->createMock(Site::class);
        $fileSystemStub = $this->createMock(Filesystem::class);

        // then
        $fileSystemStub->expects($this->exactly(1))
            ->method("dumpFile")
            ->with(
                $this->stringEndsWith("app/Resources/AppBundle/views/Main/home.html.twig"),
                "body { background-color: black; }"
            );

        // when
        $template->updateContent($siteStub, "body { background-color: black; }", $fileSystemStub);
    }
    /**
     * @throws Exception
     */
    public function testUpdateContentForStylesheet()
    {
        // given
        $template = Template::get("css");
        $siteStub = $this->createMock(Site::class);
        $siteStub->method("getOpt")->willReturn("11");
        $fileSystemStub = $this->createMock(Filesystem::class);

        // then
        $siteStub->expects($this->exactly(1))
            ->method("setOpt")
            ->with("assets_version", 12);
        $fileSystemStub->expects($this->exactly(1))
            ->method("dumpFile")
            ->with(
                $this->stringEndsWith("/src/Biblys/Template/../../../app/public/theme/styles.css"),
                "body { background-color: black; }"
            );
        $fileSystemStub->expects($this->exactly(1))
            ->method("copy")
            ->with(
                $this->stringEndsWith("/src/Biblys/Template/../../../app/public/theme/styles.css"),
                $this->stringEndsWith("/src/Biblys/Template/../../../public/theme/styles.css"),
            );

        // when
        $template->updateContent($siteStub, "body { background-color: black; }", $fileSystemStub);
    }
}
