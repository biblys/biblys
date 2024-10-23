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

use Opengraph\Writer;
use PHPUnit\Framework\TestCase;

class MetaTagsServiceTest extends TestCase
{
    public function testSetTitle()
    {
        // given
        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("og:title"), $this->equalTo("Hello World"));
        $metaTagsService = new MetaTagsService($writer);

        // when
        $metaTagsService->setTitle("Hello World");

        // then
        $this->assertTrue(true);
    }
    public function testSetImage()
    {
        // given
        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("og:image"), $this->equalTo("cover.jpg"));
        $metaTagsService = new MetaTagsService($writer);

        // when
        $metaTagsService->setImage("cover.jpg");

        // then
        $this->assertTrue(true);
    }

    public function testDisallowSeoIndexing()
    {
        // given
        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("append")
            ->with($this->equalTo("robots"), $this->equalTo("noindex"));
        $metaTagsService = new MetaTagsService($writer);

        // when
        $metaTagsService->disallowSeoIndexing();

        // then
        $this->assertTrue(true);
    }

    public function testDump()
    {
        // given
        $writer = $this->createMock(Writer::class);
        $writer->expects($this->once())
            ->method("render")
            ->with()
            ->willReturn("tags");
        $metaTagsService = new MetaTagsService($writer);

        // when
        $result = $metaTagsService->dump();

        // then
        $this->assertEquals("tags", $result);
    }
}
