<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\PublisherQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PublisherControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PublisherQuery::create()->deleteAll();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testAdminAction()
    {
        // given
        $controller = new PublisherController();
        ModelFactory::createPublisher(name: "Éditeur dans la liste");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->once();
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse");
        $queryParams->expects("getInteger")->with("p")->andReturn(0);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->adminAction($currentUser, $queryParams, $templateService);

        // then
        $this->assertStringContainsString("Éditeurs (1)", $response->getContent());
        $this->assertStringContainsString("Éditeur dans la liste", $response->getContent());
    }
}
