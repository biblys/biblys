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


namespace ApiBundle\Controller;

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\BookCollectionQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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

    /** searchAction */

    /**
     * @throws PropelException
     */
    public function testSearchForAdmin()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Searchable publisher");
        $collection = ModelFactory::createCollection(publisher: $publisher, name: "Searchable collection");
        $collection->setPricegridId(123);
        $collection->save();
        $controller = new CollectionController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("hasPublisherRight")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("allowsPublisher")->andReturn(true);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->with(["term" => ["type" => "string"]]);
        $queryParams->expects("get")->with("term")->andReturn("Searchable");

        // when
        $response = $controller->searchAction($currentUser, $currentSite, $queryParams);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $expectedResponse = '{"results":[{
    "label": "Searchable collection (Searchable publisher)",
    "value": '.$collection->getId().'
}]}';
        $this->assertJsonStringEqualsJsonString($expectedResponse, $response->getContent());
    }

    /**
     * @throws PropelException
     */
    public function testSearchForPublisher()
    {
        // given
        $userPublisher = ModelFactory::createPublisher(name: "User publisher");
        ModelFactory::createCollection(publisher: $userPublisher, name: "User collection");
        ModelFactory::createCollection(name: "Collection from other user");
        $publisherRight = ModelFactory::createRight(publisher: $userPublisher);
        $controller = new CollectionController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("hasPublisherRight")->andReturn(true);
        $currentUser->expects("getCurrentRight")->andReturn($publisherRight);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("allowsPublisher")->andReturn(true);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->with(["term" => ["type" => "string"]]);
        $queryParams->expects("get")->with("term")->andReturn("User");

        // when
        $response = $controller->searchAction($currentUser, $currentSite, $queryParams);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("User collection", $response->getContent());
        $this->assertStringNotContainsString("Collection from other user", $response->getContent());
    }

    /** createAction */

    /**
     * @throws PropelException
     */
    public function testCreateActionAsAdmin()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Éditeur de la nouvelle collection");

        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(true);
        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse")->with([
            "collection_name" => ["type" => "string"],
            "collection_publisher" => ["type" => "string"],
            "collection_publisher_id" => ["type" => "numeric"],
        ])->andReturn();
        $bodyParams->expects("get")->with("collection_name")
            ->andReturn("Nouvelle collection");
        $bodyParams->expects("get")->with("collection_publisher")
            ->andReturn($publisher->getName());
        $bodyParams->expects("getInteger")->with("collection_publisher_id")
            ->andReturn($publisher->getId());

        // when
        $response = $controller->createAction($currentUser, $bodyParams);

        // then
        $this->assertEquals(201, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("collection_id", $json);
        $this->assertEquals("Nouvelle collection", $json["collection_name"]);
        $this->assertEquals($publisher->getName(), $json["collection_publisher"]);
        $this->assertEquals($publisher->getId(), $json["collection_publisher_id"]);
    }

    /**
     * @throws Exception
     */
    public function testCreateActionWithInvalidData()
    {
        // given
        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(true);
        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse")->with([
            "collection_name" => ["type" => "string"],
            "collection_publisher" => ["type" => "string"],
            "collection_publisher_id" => ["type" => "numeric"],
        ])->andReturn();
        $bodyParams->expects("get")->with("collection_name")
            ->andReturn("Collection existante");
        $bodyParams->expects("get")->with("collection_publisher")
            ->andReturn("");
        $bodyParams->expects("getInteger")->with("collection_publisher_id")
            ->andReturn(0);

        // when
        $exception = Helpers::runAndCatchException(fn () => $controller->createAction($currentUser, $bodyParams));

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $exception);
        $this->assertStringContainsString(
            "La collection doit être associée à un éditeur",
            $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateActionWithAlreadyExistingCollection()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Éditeur de la collection existante");
        ModelFactory::createCollection(publisher: $publisher, name: "Collection existante");

        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(true);
        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse")->with([
            "collection_name" => ["type" => "string"],
            "collection_publisher" => ["type" => "string"],
            "collection_publisher_id" => ["type" => "numeric"],
        ])->andReturn();
        $bodyParams->expects("get")->with("collection_name")
            ->andReturn("Collection existante");
        $bodyParams->expects("get")->with("collection_publisher")
            ->andReturn($publisher->getName());
        $bodyParams->expects("getInteger")->with("collection_publisher_id")
            ->andReturn($publisher->getId());

        // when
        $exception = Helpers::runAndCatchException(fn () => $controller->createAction($currentUser, $bodyParams));

        // then
        $this->assertInstanceOf(ConflictHttpException::class, $exception);
        $this->assertStringContainsString("Il existe déjà une collection", $exception->getMessage());
    }

    /**
     * @throws PropelException
     */
    public function testCreateActionAsPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Éditeur de la nouvelle collection");
        $publisherRight = ModelFactory::createRight(publisher: $publisher);

        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(false);
        $currentUser->expects("hasPublisherRight")->andReturn(true);
        $currentUser->expects("getCurrentRight")->andReturn($publisherRight);
        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse")->with([
            "collection_name" => ["type" => "string"],
            "collection_publisher" => ["type" => "string"],
            "collection_publisher_id" => ["type" => "numeric"],
        ])->andReturn();
        $bodyParams->expects("get")->with("collection_name")
            ->andReturn("Nouvelle collection");
        $bodyParams->expects("get")->with("collection_publisher")
            ->andReturn($publisher->getName());
        $bodyParams->expects("getInteger")->with("collection_publisher_id")
            ->andReturn($publisher->getId());

        // when
        $response = $controller->createAction($currentUser, $bodyParams);

        // then
        $this->assertEquals(201, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("collection_id", $json);
        $this->assertEquals("Nouvelle collection", $json["collection_name"]);
        $this->assertEquals($publisher->getName(), $json["collection_publisher"]);
        $this->assertEquals($publisher->getId(), $json["collection_publisher_id"]);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateActionAsPublisherFailsIfPublisherIsNotUsers()
    {
        // given
        $targetPublisher = ModelFactory::createPublisher(name: "Éditeur de la nouvelle collection");
        $userPublisher = ModelFactory::createPublisher(name: "Éditeur de l'utilisateur");
        $publisherRight = ModelFactory::createRight(publisher: $userPublisher);

        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(false);
        $currentUser->expects("hasPublisherRight")->andReturn(true);
        $currentUser->expects("getCurrentRight")->andReturn($publisherRight);
        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse")->with([
            "collection_name" => ["type" => "string"],
            "collection_publisher" => ["type" => "string"],
            "collection_publisher_id" => ["type" => "numeric"],
        ])->andReturn();
        $bodyParams->expects("get")->with("collection_name")
            ->andReturn("Nouvelle collection");
        $bodyParams->expects("get")->with("collection_publisher")
            ->andReturn($targetPublisher->getName());
        $bodyParams->expects("getInteger")->with("collection_publisher_id")
            ->andReturn($targetPublisher->getId());

        // when
        $exception = Helpers::runAndCatchException(fn () => $controller->createAction($currentUser, $bodyParams));

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $exception);
        $this->assertStringContainsString(
            "Vous n'avez pas le droit de créer une collection pour cet éditeur",
            $exception->getMessage()
        );
    }
}
