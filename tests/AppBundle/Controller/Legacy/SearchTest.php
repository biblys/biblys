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

namespace AppBundle\Controller\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../../../setUp.php";

class SearchTest extends TestCase
{
    public function testSearchAcceptsPageParameter()
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/search.php";
        $request = new Request(query: ["q" => "Fleuve Noir", "p" => "1"]);
        $currentSite = new CurrentSite(ModelFactory::createSite());

        // when
        $response = $controller($request, $currentSite, new QueryParamsService($request));

        // then
        $this->assertEquals(
            301,
            $response->getStatusCode(),
            "it should not reject the pagination parameter"
        );
    }
}
