<?php

namespace Biblys\Data;

use PHPUnit\Framework\TestCase;

class FileTypeTest extends TestCase
{
    /** FileType::getByMediaType */

    public function testGetByMediaTypeReturnsGivenType()
    {
        // when
        $fileType = FileType::getByMediaType("application/pdf");

        // then
        $this->assertEquals("PDF", $fileType->getName());
    }


    public function testGetByMediaTypeReturnsDefaultType()
    {
        // when
        $fileType = FileType::getByMediaType("application/pdf+jpg");

        // then
        $this->assertEquals("Inconnu", $fileType->getName());
    }
}
