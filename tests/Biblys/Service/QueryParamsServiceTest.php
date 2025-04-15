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


namespace Biblys\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class QueryParamsServiceTest extends TestCase
{
    /** fbclid */

    public function testFbclidIsIgnored(): void
    {
        // given
        $request = new Request();
        $request->query->set("id", "123");
        $request->query->set("fbclid", "456");

        $queryParamsService = new QueryParamsService($request);

        // when
        $queryParamsService->parse([
            "id" => [
                "type" => "numeric",
            ],
        ]);

        // then
        $this->assertEquals("123", $queryParamsService->get("id"));
    }


    /** ttclid */

    public function testTtclidIsIgnored(): void
    {
        // given
        $request = new Request();
        $request->query->set("id", "123");
        $request->query->set("ttclid", "456");

        $queryParamsService = new QueryParamsService($request);

        // when
        $queryParamsService->parse([
            "id" => [
                "type" => "numeric",
            ],
        ]);

        // then
        $this->assertEquals("123", $queryParamsService->get("id"));
    }
}
