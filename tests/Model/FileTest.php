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
}