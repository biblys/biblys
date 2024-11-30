<?php

namespace Model;

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class BookCollectionTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        BookCollectionQuery::create()->deleteAll();
    }

    /**
     * @throws Exception
     */
    public function testSaveFailsIfCollectionHasNoName()
    {
        // given
        $collection = new BookCollection();

        // when
        $exception = Helpers::runAndCatchException(fn () => $collection->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("La collection doit avoir un nom.", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSaveFailsIfCollectionHasNoPublisher()
    {
        // given
        $collection = new BookCollection();
        $collection->setName('Une collection');

        // when
        $exception = Helpers::runAndCatchException(fn () => $collection->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("La collection doit être associée à un éditeur.", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSaveFailsIfACollectionAlreadyExistsWithTheSameName()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "DOUBLON");
        $existingCollection = ModelFactory::createCollection(publisher: $publisher, name: "Une collection en double");
        $collection = new BookCollection();
        $collection->setName("Une collection en double");
        $collection->setPublisher($publisher);

        // when
        $exception = Helpers::runAndCatchException(fn () => $collection->save());

        // then
        $this->assertInstanceOf(EntityAlreadyExistsException::class, $exception);
        $this->assertEquals(
            "Il existe déjà une collection avec le nom « Une collection en double » (n° {$existingCollection->getId()}) ".
            "chez l'éditeur DOUBLON (slug: doublon-une-collection-en-double).",
            $exception->getMessage()
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSaveFailsIfACollectionAlreadyExistsWithTheSameNoosfereId()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "NOOSFERE");
        $existingCollection = ModelFactory::createCollection(publisher: $publisher, name: "Une collection noosfere", noosfereId: 1234);
        $collection = new BookCollection();
        $collection->setName("Une autre collection noosfere");
        $collection->setPublisher($publisher);
        $collection->setNoosfereId(1234);

        // when
        $exception = Helpers::runAndCatchException(fn () => $collection->save());

        // then
        $this->assertInstanceOf(EntityAlreadyExistsException::class, $exception);
        $this->assertEquals(
            "Il existe déjà une collection avec l'identifiant noosfere 1234: Collection n° {$existingCollection->getId()} ".
            "(slug: une-collection-noosfere).",
            $exception->getMessage()
        );
    }

    /**
     * @throws Exception
     */
    public function testSaveSucceedsIfCollectionIsValid()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "UN EDITEUR");
        $collection = new BookCollection();
        $collection->setName("Une collection");
        $collection->setPublisher($publisher);

        // when
        $collection->save();

        // then
        $this->assertNotNull($collection->getId());
        $this->assertEquals("un-editeur-une-collection", $collection->getUrl());
        $this->assertEquals("UN EDITEUR", $collection->getPublisherName());
    }
}
