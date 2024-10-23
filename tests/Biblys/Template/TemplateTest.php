<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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
                $this->stringEndsWith("app/views/Main/home.html.twig"),
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
