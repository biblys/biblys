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


namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\BookCollectionQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CollectionControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        BookCollectionQuery::create()->deleteAll();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testAdminActionAsAdmin()
    {
        // given
        ModelFactory::createCollection(name: "Collection à lister");

        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(true);
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse");
        $queryParamsService->expects("getInteger")->with("p")->andReturn(0);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->adminAction($currentUser, $queryParamsService, $templateService);

        // then
        $this->assertStringContainsString("Collection à lister", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testAdminActionAsPublisher()
    {
        // given
        $userPublisher = ModelFactory::createPublisher(name: "CET UTILISATEUR");
        ModelFactory::createCollection(publisher: $userPublisher, name: "Collection utilisateur");
        $publisherRight = ModelFactory::createRight(publisher: $userPublisher);
        $otherPublisher = ModelFactory::createPublisher(name: "AUTRE UTILISATEUR");
        ModelFactory::createCollection(publisher: $otherPublisher, name: "Collection autre utilisateur");

        $controller = new CollectionController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(false);
        $currentUser->expects("getCurrentRight")->andReturn($publisherRight);
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse");
        $queryParamsService->expects("getInteger")->with("p")->andReturn(0);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->adminAction($currentUser, $queryParamsService, $templateService);

        // then
        $this->assertStringContainsString("Collection utilisateur", $response->getContent());
        $this->assertStringNotContainsString("Collection autre utilisateur", $response->getContent());
    }
}
