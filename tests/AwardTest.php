<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once('inc/functions.php');

class AwardTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        global $site;

        $am = new AwardManager();

        $award = $am->create(['award_name' => 'Prix Goncourt']);

        $this->assertInstanceOf('Award', $award);
        $this->assertEquals($award->get('name'), 'Prix Goncourt');

        return $award;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(Award $award)
    {
        $am = new AwardManager();

        $gotAward = $am->getById($award->get('id'));

        $this->assertInstanceOf('Award', $award);

        return $award;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(Award $award)
    {
        $am = new AwardManager();

        $award->set('award_name', 'Prix Fémina');
        $am->update($award);

        $updatedAward = $am->getById($award->get('id'));

        $this->assertTrue($updatedAward->has('updated'));
        $this->assertEquals($updatedAward->get('name'), 'Prix Fémina');

        return $updatedAward;
    }

    /**
     * Test that awards cannot be created without a name
     * @expectedException Exception
     * @expectedExceptionMessage Le prix littéraire doit avoir un nom.
     */
    public function testCreateAwardWithoutAName()
    {
        $am = new AwardManager();

        $award = new Award(['award_name' => '']);

        $am->validate($award);
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testDelete(Award $award)
    {
        $am = new AwardManager();

        $am->delete($award);

        $award = $am->getById($award->get('id'));

        $this->assertFalse($award);
    }
}
