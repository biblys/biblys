<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class ListeTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $lm = new ListeManager();

        $list = $lm->create();

        $this->assertInstanceOf('Liste', $list);

        return $list;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Liste $list)
    {
        $lm = new ListeManager();

        $gotListe = $lm->getById($list->get('id'));

        $this->assertInstanceOf('Liste', $list);
        $this->assertEquals($list->get('id'), $gotListe->get('id'));

        return $list;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Liste $list)
    {
        $lm = new ListeManager();

        $list->set('list_title', 'Listetou');
        $lm->update($list);

        $updatedListe = $lm->getById($list->get('id'));

        $this->assertTrue($updatedListe->has('updated'));
        $this->assertEquals($updatedListe->get('title'), 'Listetou');

        return $updatedListe;
    }

    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Liste $list)
    {
        $lm = new ListeManager();

        $lm->delete($list, 'Test entity');

        $listExists = $lm->getById($list->get('id'));

        $this->assertFalse($listExists);
    }
}
