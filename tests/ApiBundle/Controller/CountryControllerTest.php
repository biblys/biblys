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


namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\CountryQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class CountryControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testSearchAction(): void
    {
        // given
        $controller = new CountryController();

        $country = ModelFactory::createCountry(name: "Scarif", code: "SC");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")
            ->with(["term" => ["type" => "string", "mb_min_length" => 3]]);
        $queryParamsService->expects("get")
            ->with("term")
            ->andReturn("Scar");

        // when
        $response = $controller->searchAction($currentUser, $queryParamsService);

        // then
        $expectedResponse = [
            "label" => "Scarif (SC)",
            "value" => $country->getId(),
        ];

        $parsedResponse = json_decode($response->getContent(), true);
        $this->assertContains($expectedResponse, $parsedResponse["results"]);
    }
}
