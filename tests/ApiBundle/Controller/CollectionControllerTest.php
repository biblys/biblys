<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\BookCollectionQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

class CollectionControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        BookCollectionQuery::create()->deleteAll();
    }


    /**
     * @throws PropelException
     */
    public function testSearch()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Searchable publisher");
        $collection = ModelFactory::createCollection(publisher: $publisher, name: "Searchable collection");
        $collection->setPricegridId(123);
        $collection->save();
        $controller = new CollectionController();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("allowsPublisher")->andReturn(true);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->with(["term" => ["type" => "string", "mb_min_length" => 3]]);
        $queryParams->expects("get")->with("term")->andReturn("Searchable");

        // when
        $response = $controller->searchAction($currentSite, $queryParams);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $expectedResponse = '[{
    "label": "Searchable collection (Searchable publisher)",
    "value": "Searchable collection",
    "collection_name": "Searchable collection",
    "collection_publisher": "Searchable publisher",
    "collection_id": '.$collection->getId().',
    "publisher_id": '.$publisher->getId().',
    "pricegrid_id": 123,
    "publisher_allowed_on_site": 1
}, {
    "label": "=\u003E Cr\u00e9er : Searchable",
    "value": "Searchable",
    "create": 1
}]';
        $this->assertJsonStringEqualsJsonString($expectedResponse, $response->getContent());
    }
}
