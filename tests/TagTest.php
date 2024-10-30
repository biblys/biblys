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

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Legacy\LegacyCodeHelper;

require_once "setUp.php";

class TagTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $tm = new TagManager();

        $tag = $tm->create(['tag_name' => 'Histoire du passé']);

        $this->assertInstanceOf('Tag', $tag);
        $this->assertEquals($tag->get('name'), 'Histoire du passé');

        return $tag;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(Tag $tag)
    {
        $tm = new TagManager();

        $gotTag = $tm->getById($tag->get('id'));

        $this->assertInstanceOf('Tag', $tag);

        return $tag;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(Tag $tag)
    {
        $tm = new TagManager();

        $tag->set('tag_name', 'Histoire du futur');
        $tm->update($tag);

        $updatedTag = $tm->getById($tag->get('id'));

        $this->assertTrue($updatedTag->has('updated'));
        $this->assertEquals($updatedTag->get('name'), 'Histoire du futur');
        $this->assertEquals($updatedTag->get('url'), 'histoire-du-futur');

        return $updatedTag;
    }

    /**
     * Test that two tag cannot have the same name
     */
    public function testCreateTagWithoutAName()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Le mot-clé doit avoir un nom.");

        $tm = new TagManager();

        $tm->create(['tag_name' => '']);
    }

    /**
     * Test that two tag cannot have the same name
     */
    public function testDuplicateNameCheck()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Il existe déjà un mot-clé avec le nom Science-fiction.");

        $tm = new TagManager();

        $tm->create(['tag_name' => 'Science-fiction']);
        $tm->create(['tag_name' => 'Science-fiction']);
    }

    /**
     * Test searching tag even if the name is not exact
     */
    public function testSearchTag()
    {
        $tm = new TagManager();

        $tag = $tm->create(['tag_name' => 'Mordor de l’Est']);
        $tm->update($tag);

        $search = $tm->search('mordor de l\'est');

        $this->assertInstanceOf('Tag', $search);
        $this->assertEquals($search->get('name'), 'Mordor de l’Est');

        $tm->delete($tag);
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testDelete(Tag $tag)
    {
        $tm = new TagManager();

        $tm->delete($tag);

        $tag = $tm->getById($tag->get('id'));

        $this->assertFalse($tag);
    }
}
