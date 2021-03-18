<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class TagTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        global $site;

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
     * @expectedException Exception
     * @expectedExceptionMessage Le mot-clé doit avoir un nom.
     */
    public function testCreateTagWithoutAName()
    {
        $tm = new TagManager();

        $tm->create(['tag_name' => '']);
    }

    /**
     * Test that two tag cannot have the same name
     * @expectedException Exception
     * @expectedExceptionMessage Il existe déjà un mot-clé avec le nom Science-fiction.
     */
    public function testDuplicateNameCheck()
    {
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
