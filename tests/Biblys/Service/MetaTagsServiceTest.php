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


namespace Biblys\Service;

use Biblys\Test\ModelFactory;
use Opengraph\Writer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class MetaTagsServiceTest extends TestCase
{

    /**
     * @throws Exception
     * @throws PropelException
     */
    public function testSetUrl()
    {
        // given
        $site = ModelFactory::createSite(domain: "example.org");
        $currentSite = new CurrentSite($site);

        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("og:url"), $this->equalTo("https://example.org/pages/about"));
        $metaTagsService = new MetaTagsService($writer, $currentSite);

        // when
        $metaTagsService->setUrl("/pages/about");

        // then
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     * @throws PropelException
     */
    public function testSetTitle()
    {
        // given
        $site = ModelFactory::createSite(domain: "example.org");
        $currentSite = new CurrentSite($site);

        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("og:title"), $this->equalTo("Hello World"));
        $metaTagsService = new MetaTagsService($writer, $currentSite);

        // when
        $metaTagsService->setTitle("Hello World");

        // then
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     * @throws PropelException
     */
    public function testSetImage()
    {
        // given
        $site = ModelFactory::createSite(domain: "example.org");
        $currentSite = new CurrentSite($site);

        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("og:image"), $this->equalTo("cover.jpg"));
        $metaTagsService = new MetaTagsService($writer, $currentSite);

        // when
        $metaTagsService->setImage("cover.jpg");

        // then
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     * @throws PropelException
     */
    public function testDisallowSeoIndexing()
    {
        // given
        $site = ModelFactory::createSite(domain: "example.org");
        $currentSite = new CurrentSite($site);

        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("robots"), $this->equalTo("noindex"));
        $metaTagsService = new MetaTagsService($writer, $currentSite);

        // when
        $metaTagsService->disallowSeoIndexing();

        // then
        $this->assertTrue(true);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDump()
    {
        // given
        $writer = $this->createMock(Writer::class);
        $site = ModelFactory::createSite(domain: "example.org");
        $currentSite = new CurrentSite($site);

        $writer->expects($this->once())
            ->method("render")
            ->with()
            ->willReturn("tags");
        $metaTagsService = new MetaTagsService($writer, $currentSite);
        $metaTagsService->setUrl("/pages/about");

        // when
        $dumper = new MetaTagsService($writer, $currentSite);
        $result = $dumper->dump();

        // then
        $this->assertStringContainsString("tags", $result);
        $this->assertStringContainsString('<link rel="canonical" href="https://example.org/pages/about" />', $result);
    }
}
