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

namespace AppBundle\Controller\Legacy;

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

require_once __DIR__ . "/../../../setUp.php";

class ArticlePeopleCreateTest extends TestCase
{
    private function callController(string $firstName, string $lastName): mixed
    {
        $controller = require __DIR__ . "/../../../../controllers/common/php/article_people_create.php";
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authPublisher");
        $request = new Request([], [
            "people_first_name" => $firstName,
            "people_last_name" => $lastName,
        ]);

        return $controller($currentUser, new BodyParamsService($request));
    }

    public function testCreatesAContributor(): void
    {
        // given
        $lastName = "Contributeur" . uniqid();

        // when
        $response = $this->callController("Axel", $lastName);

        // then
        $data = json_decode($response->getContent(), true);
        $this->assertEquals("Axel $lastName", $data["people_name"]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAnExistingContributorIsAConflictNotAServerError(): void
    {
        // given
        $people = ModelFactory::createPeople(["first_name" => "Axel", "last_name" => "Honneth"]);

        // then
        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage("Il existe déjà un contributeur avec le nom {$people->getFullName()}.");

        // when
        $this->callController("Axel", "Honneth");
    }
}
