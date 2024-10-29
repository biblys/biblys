<?php

namespace Model;

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testGetType(): void
    {
        // given
        $file = new File();
        $file->setType("application/epub+zip");

        // when
        $type = $file->getFileType();

        // then
        $this->assertEquals("ePub", $type->getName());
    }

    public function testGetFullPath(): void
    {
        // given
        $file = new File();
        $file->setArticleId(1234);
        $file->setHash("abcd");

        // when
        $path = $file->getFullPath();

        // then
        $this->assertStringEndsWith("/content/downloadable/1234/abcd", $path);
    }
}