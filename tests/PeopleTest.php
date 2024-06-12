<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once('inc/functions.php');

class PeopleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        global $site;

        $pm = new PeopleManager();

        $people = $pm->create(['people_last_name' => 'Socrate']);

        $this->assertInstanceOf('People', $people);

        return $people;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(People $people)
    {
        $pm = new PeopleManager();

        $gotPeople = $pm->getById($people->get('id'));

        $this->assertInstanceOf('People', $people);

        return $people;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(People $people)
    {
        $pm = new PeopleManager();

        $people->set('people_first_name', 'Vladimir');
        $pm->update($people);

        $updatedPeople = $pm->getById($people->get('id'));

        $this->assertTrue($updatedPeople->has('updated'));
        $this->assertEquals($updatedPeople->get('first_name'), 'Vladimir');

        return $updatedPeople;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testGetTwitterUrl(People $people)
    {
        $people->set('people_twitter', '@vladnabokov');

        $this->assertEquals($people->getTwitterUrl(), 'https://www.twitter.com/vladnabokov');
    }

    /**
     * Test slug creation for collection with name no different to publisher
     */
    public function testPreprocess()
    {
        $pm = new PeopleManager();

        $people = new People([
            'people_first_name' => 'Egdar Allan',
            'people_last_name' => 'Poe'
        ]);

        $people = $pm->preprocess($people);

        $this->assertEquals($people->get('name'), "Egdar Allan POE");
        $this->assertEquals($people->get('alpha'), "POE Egdar Allan");
        $this->assertEquals($people->get('url'), "egdar-allan-poe");
    }

    /**
     * Test getting a full name
     */
    public function testGetName()
    {
        $people = new People([]);

        $people->set('people_last_name', 'Voltaire');

        $this->assertEquals($people->getName(), 'Voltaire');

        $people->set('people_first_name', 'Vladimir');
        $people->set('people_last_name', 'Nabokov');

        $this->assertEquals($people->getName(), 'Vladimir Nabokov');
    }

    /**
     * Test that two tag cannot have the same name
     * @expectedException Exception
     * @expectedExceptionMessage Le contributeur doit avoir un nom.
     */
    public function testCreateTagWithoutAName()
    {
        $pm = new PeopleManager();

        $people = new People(['people_last_name' => '']);

        $pm->validate($people);
    }

    /**
     * Test that two publisher cannot have the same name
     * @expectedException Exception
     * @expectedExceptionMessage Il existe déjà un contributeur avec le nom Edgar Allan POE.
     */
    public function testDuplicateNameCheck()
    {
        $pm = new PeopleManager();

        $pm->create([
            'people_first_name' => 'Edgar Allan',
            'people_last_name' => 'Poe'
        ]);
    }

    /**
     * Test validate website url
     * @expectedException Exception
     * @expectedExceptionMessage L'adresse du site est invalide.
     */
    public function testValidateWebsiteUrl()
    {
        $pm = new PeopleManager();

        $pm->create([
            'people_first_name' => 'Edgar Allan',
            'people_last_name' => 'Poe',
            'people_site' => 'www.edgarallanpoe.com'
        ]);
    }

    /**
     * Test validate facebook page url
     * @expectedException Exception
     * @expectedExceptionMessage L'adresse de la page Facebook doit commencer par https://www.facebook.com/.
     */
    public function testValidateFacebookPageUrl()
    {
        $pm = new PeopleManager();

        $pm->create([
            'people_first_name' => 'Edgar Allan',
            'people_last_name' => 'Poe',
            'people_facebook' => 'www.facebook.com/edgarallanpoe'
        ]);
    }

    /**
     * Test validate Twitter username
     * @expectedException Exception
     * @expectedExceptionMessage Le compte Twitter doit commencer par @ et ne doit pas dépasser 15 caractères.
     */
    public function testValidateTwitterUsername()
    {
        $pm = new PeopleManager();

        $pm->create([
            'people_first_name' => 'Edgar Allan',
            'people_last_name' => 'Poe',
            'people_twitter' => 'edgarallanpoe'
        ]);
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testDelete(People $people)
    {
        $pm = new PeopleManager();

        $pm->delete($people);

        $people = $pm->getById($people->get('id'));

        $this->assertFalse($people);
    }
}
