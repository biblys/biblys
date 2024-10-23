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