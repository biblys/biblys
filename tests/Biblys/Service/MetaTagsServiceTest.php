<?php

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
