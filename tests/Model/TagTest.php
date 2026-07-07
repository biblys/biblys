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

class TagTest extends TestCase
{
    public function setUp(): void
    {
        TagQuery::create()->deleteAll();
    }

    public function testSaveGeneratesUrlSlugFromName(): void
    {
        // given
        $tag = new Tag();
        $tag->setName('Science-fiction');

        // when
        $tag->save();

        // then
        $this->assertEquals('science-fiction', $tag->getUrl());
    }

    public function testUpdateRegeneratesUrlSlugFromName(): void
    {
        // given
        $tag = new Tag();
        $tag->setName('Science-fiction');
        $tag->save();

        // when
        $tag->setName('Histoire du futur');
        $tag->save();

        // then
        $this->assertEquals('histoire-du-futur', $tag->getUrl());
    }
}
