<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class CollectionTest extends PHPUnit_Framework_TestCase
{
    private static $cm, $publisher;

    public static function setUpBeforeClass()
    {
        CollectionTest::$cm = new PublisherManager();
        CollectionTest::$publisher = CollectionTest::$cm->create(['publisher_name' => 'ÉDITEUR TIMBRÉ']);
    }

    /**
     * Test creating a collection
     */
    public function testCreate()
    {
        global $site;

        $cm = new CollectionManager();

        $collection = $cm->create([
            'collection_name' => 'Une collection',
            'publisher_id' => CollectionTest::$publisher->get('id')
        ]);

        $this->assertInstanceOf('Collection', $collection);
        $this->assertEquals($collection->get('name'), 'Une collection');

        return $collection;
    }

    /**
     * Test getting a collection
     * @depends testCreate
     */
    public function testGet(Collection $collection)
    {
        $cm = new CollectionManager();

        $gotCollection = $cm->getById($collection->get('id'));

        $this->assertInstanceOf('Collection', $collection);

        return $collection;
    }

    /**
     * Test updating a collection
     * @depends testGet
     */
    public function testUpdate(Collection $collection)
    {
        $cm = new CollectionManager();

        $collection->set('collection_name', 'Ma collection de timbres');
        $cm->update($collection);

        $updatedCollection = $cm->getById($collection->get('id'));

        $this->assertTrue($updatedCollection->has('updated'));
        $this->assertEquals($updatedCollection->get('name'), 'Ma collection de timbres');
        $this->assertEquals($updatedCollection->get('url'), 'editeur-timbre-ma-collection-de-timbres');
    }

    /**
     * Test updating a collection
     * @depends testGet
     */
    public function testGetCollection(Collection $collection)
    {
        $publisher = $collection->getPublisher();

        $this->assertInstanceOf('Publisher', $publisher);
        $this->assertEquals($publisher->get('id'), CollectionTest::$publisher->get('id'));
    }

    /**
     * Test that collections cannot be created without a name
     * @expectedException Exception
     * @expectedExceptionMessage La collection doit avoir un nom.
     */
    public function testCreateCollectionWithoutAName()
    {
        $cm = new CollectionManager();

        $collection = $cm->create([
            'publisher_id' => CollectionTest::$publisher->get('id'),
            'collection_name' => ''
        ]);
    }

    /**
     * Test that collections cannot be created without a publisher
     * @expectedException Exception
     * @expectedExceptionMessage La collection doit être associée à un éditeur.
     */
    public function testCreateTagWithoutAPublisher()
    {
        $cm = new CollectionManager();

        $collection = $cm->create(['collection_name' => 'Une collection']);
    }

    /**
     * Test that two collection cannot have the same name
     * @expectedException Exception
     * @expectedExceptionMessage Il existe déjà une collection avec le nom Ma collection de timbres chez cet éditeur.
     */
    public function testDuplicateNameCheck()
    {
        $cm = new CollectionManager();

        $collection = $cm->create([
            'publisher_id' => CollectionTest::$publisher->get('id'),
            'collection_name' => 'Ma collection de timbres'
        ]);
    }

    /**
     * Test slug creation for collection with name no different to publisher
     */
    public function testSlugCreationForPublisherCollection()
    {
        $cm = new CollectionManager();

        $collection = new Collection([
            'publisher_id' => CollectionTest::$publisher->get('id'),
            'collection_name' => 'ÉDITEUR TIMBRÉ'
        ]);

        $collection = $cm->preprocess($collection);

        $this->assertEquals($collection->get('url'), 'editeur-timbre');
    }

    /**
     * Test deleting a collection with articles
     * @depends testGet
     * @expectedException Exception
     * @expectedExceptionMessage Impossible de supprimer la collection car des articles y sont associés.
     */
    public function testBeforeDelete(Collection $collection)
    {
        $am = new ArticleManager();
        $cm = new CollectionManager();

        $article = $am->create(['collection_id' => $collection->get('id')]);

        $cm->delete($collection);
    }

    /**
     * Test deleting a collection
     * @depends testGet
     */
    public function testDelete(Collection $collection)
    {
        $cm = new CollectionManager();
        $am = new ArticleManager();

        $article = $am->get(['collection_id' => $collection->get('id')]);
        $am->delete($article);

        $cm->delete($collection);

        $collection = $cm->getById($collection->get('id'));

        $this->assertFalse($collection);
    }

    public static function tearDownAfterClass()
    {
        CollectionTest::$cm->delete(CollectionTest::$publisher);
    }
}
