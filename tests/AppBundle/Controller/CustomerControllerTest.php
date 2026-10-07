<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../../setUp.php";

class CustomerControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testSearchActionReturnsMatchingCustomers(): void
    {
        // given
        $controller = new CustomerController();
        $customer = ModelFactory::createCustomer(
            firstName: "Silas",
            lastName: "Quillfeather",
            email: "silas.quillfeather@paronymie.fr",
        );
        $request = new Request(query: ["term" => "Quillfeather"]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->searchAction(
            $currentUser,
            new QueryParamsService($request),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $results = json_decode($response->getContent(), true)["results"];
        $this->assertContains(
            ["label" => "Quillfeather, Silas (silas.quillfeather@paronymie.fr)", "value" => $customer->getId()],
            $results,
        );
    }

    /**
     * @throws PropelException
     */
    public function testSearchActionReturnsEmptyResultsWhenNoCustomerMatches(): void
    {
        // given
        $controller = new CustomerController();
        $request = new Request(query: ["term" => "personne-ne-s-appelle-comme-ca"]);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");

        // when
        $response = $controller->searchAction(
            $currentUser,
            new QueryParamsService($request),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(["results" => []], json_decode($response->getContent(), true));
    }
}
