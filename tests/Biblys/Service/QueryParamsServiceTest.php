<?php

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QueryParamsServiceTest extends TestCase
{

    public function testValidValue()
    {
        // given
        $request = new Request();
        $request->query->set("q", "search terms");

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $queryParamsService = new QueryParamsService();

        // when
        $queryParamsService->parse($request, $specs);
        $searchQuery = $queryParamsService->get("q");

        // then
        $this->assertEquals("search terms", $searchQuery);
    }

    public function testInvalidValueType()
    {
        // given
        $request = new Request();
        $arrayParam = "return_url%5B%24testing%5D=1";
        $request->query->set("q", [1]);

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $queryParamsService = new QueryParamsService();

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Parameter "q" failed validation "types" for array value');

        // when
        $queryParamsService->parse($request, $specs);
    }
}
