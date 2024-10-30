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
use Biblys\Test\EntityFactory;

require_once "setUp.php";

class CollectionTest extends PHPUnit\Framework\TestCase
{
    private static $cm, $publisher;

    public static function setUpBeforeClass(): void
    {
        CollectionTest::$cm = new PublisherManager();
        CollectionTest::$publisher = CollectionTest::$cm->create(['publisher_name' => 'ÉDITEUR TIMBRÉ']);
    }

    /**
     * Test creating a collection
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

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
     */
    public function testCreateCollectionWithoutAName()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("La collection doit avoir un nom.");

        $cm = new CollectionManager();

        $cm->create([
            'publisher_id' => CollectionTest::$publisher->get('id'),
            'collection_name' => ''
        ]);
    }

    /**
     * Test that collections cannot be created without a publisher
     */
    public function testCreateTagWithoutAPublisher()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("La collection doit être associée à un éditeur.");

        $cm = new CollectionManager();

        $collection = $cm->create(['collection_name' => 'Une collection']);
    }

    /**
     * Test that two collection cannot have the same name
     * @throws Exception
     */
    public function testDuplicateNameCheck()
    {
        // given
        $cm = new CollectionManager();
        $publisher = EntityFactory::createPublisher();
        $collection = $cm->create([
            'publisher_id' => $publisher->get('id'),
            'collection_name' => 'Une collection en double'
        ]);

        // then
        $this->expectException("\Biblys\Exception\EntityAlreadyExistsException");
        $this->expectExceptionMessage(
            sprintf(
                "Il existe déjà une collection avec le nom « Une collection en double » (n° %s) chez l'éditeur PARONYMIE (slug: paronymie-une-collection-en-double).",
                $collection->get("id"),
            )
        );

        // when
        $cm->create([
            'publisher_id' => $publisher->get('id'),
            'collection_name' => 'Une collection en double'
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
     */
    public function testBeforeDelete()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de supprimer la collection car des articles y sont associés.");

        $cm = new CollectionManager();

        $article = EntityFactory::createArticle();
        $collection = $article->get("collection");

        $cm->delete($collection);
    }

    /**
     * Test deleting a collection
     * @depends testGet
     */
    public function testDelete(Collection $collection)
    {
        // given
        $cm = new CollectionManager();
        $am = new ArticleManager();
        $article = EntityFactory::createArticle(["collection_id" => $collection->get("id")]);
        $am->delete($article);

        // when
        $cm->delete($collection);

        // then
        $collection = $cm->getById($collection->get('id'));
        $this->assertFalse($collection);
    }

    public static function tearDownAfterClass(): void
    {
        CollectionTest::$cm->delete(CollectionTest::$publisher);
    }
}
