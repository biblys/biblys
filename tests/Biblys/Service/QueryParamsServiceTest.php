<?php

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
