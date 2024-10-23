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
    public function testFindTemplateForCustomView()
    {
        // given
        $currentSite = $this->createMock(CurrentSite::class);
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method("exists")->willReturnOnConsecutiveCalls( true);
        $loader = new TemplateLoader($currentSite, $filesystem);

        // when
        $templatePath = $loader->getCacheKey("AppBundle:Main:home.html.twig");

        // then
        $this->assertStringEndsWith(
            "app/views/Main/home.html.twig",
            $templatePath
        );
    }
}
