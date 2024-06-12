<?php

namespace Biblys\Template;

use Exception;
use PHPUnit\Framework\TestCase;
use Site;

require_once __DIR__."/../../setUp.php";

class TemplateTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testUpdateContent()
    {
        // given
        $template = Template::get("css");
        $siteStub = $this->createMock(Site::class);
        $siteStub->method("getOpt")
            ->willReturn("11");

        // then
        $siteStub->expects($this->exactly(1))
            ->method("setOpt")
            ->with("assets_version", 12);

        // when
        $template->updateContent($siteStub, $template->getContent());
    }
}
