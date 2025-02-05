<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

class PostTest extends TestCase
{

    public function testIsOnlineReturnsFalseByDefault()
    {
        // given
        $post = new Post();

        // when
        $isOnline = $post->isPublished();

        // then
        $this->assertFalse($isOnline);
    }

    public function testIsOnlineReturnsFalseIfStatusIsO()
    {
        // given
        $post = new Post();
        $post->setStatus(0);

        // when
        $isOnline = $post->isPublished();

        // then
        $this->assertFalse($isOnline);
    }

    public function testIsOnlineReturnsTrueIfStatusIs1()
    {
        // given
        $post = new Post();
        $post->setStatus(1);

        // when
        $isOnline = $post->isPublished();

        // then
        $this->assertTrue($isOnline);
    }
}
