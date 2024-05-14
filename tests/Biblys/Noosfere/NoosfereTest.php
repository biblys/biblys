<?php

namespace Biblys\Noosfere;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Publisher;

require_once __DIR__ . "/../../setUp.php";

class NoosfereTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCollectionWhenCollectionExists()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Imported");
        $publisherEntity = Publisher::buildFromModel($publisher);
        $existingCollection = ModelFactory::createCollection(publisher: $publisher, name: "from nooSFere");

        // when
        $returnedCollection = Noosfere::getOrCreateCollection(
            0,
            "from nooSFere",
            $publisherEntity
        );

        // then
        $this->assertEquals($existingCollection->getId(), $returnedCollection->get("id"));
    }
}
