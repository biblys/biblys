<?php

namespace Biblys\Service;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QueryParamsServiceTest extends TestCase
{

    /**
     * Valid value
     */

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
        $queryParamsService = new QueryParamsService($request);

        // when
        $queryParamsService->parse($specs);
        $searchQuery = $queryParamsService->get("q");

        // then
        $this->assertEquals("search terms", $searchQuery);
    }

    public function testUnexpectedQueryParam(): void
    {
        // given
        $request = new Request();
        $request->query->set("ref", "unexpected query param");

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Unexpected parameter 'ref'");

        // when
        $queryParamsService->parse($specs);
    }

    public function testUnknownRule(): void {
        // given
        $request = new Request();
        $request->query->set("q", "11");

        $specs = [
            "q" => [
                "is_prime" => "true",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown validation rule 'is_prime'");

        // when
        $queryParamsService->parse($specs);
    }

    /** "type" rule */

    public function testInvalidValueTypeForString()
    {
        // given
        $request = new Request();
        $request->query->set("q", [1]);

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be of type string");

        // when
        $queryParamsService->parse($specs);
    }

    /** "optional" rule */

    public function testMissingRequiredParameter()
    {
        // given
        $request = new Request();

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' is required");

        // when
        $queryParamsService->parse($specs);
    }

    public function testMissingOptionalParameter()
    {
        // given
        $request = new Request();

        $specs = [
            "q" => [
                "type" => "string",
                "optional" => true,
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $q = $queryParamsService->get("q");

        // then
        $this->assertNull($q);
    }

    /** "default" rule */

    public function testMissingOptionalParameterWithDefaultValue()
    {
        // given
        $request = new Request();

        $specs = [
            "page" => [
                "type" => "string",
                "optional" => true,
                "default" => "0",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $page = $queryParamsService->get("page");

        // then
        $this->assertEquals("0", $page);
    }

    public function testProvidedOptionalParameterWithDefaultValue()
    {
        // given
        $request = new Request();
        $request->query->set("page", "1");

        $specs = [
            "page" => [
                "type" => "string",
                "optional" => true,
                "default" => "0",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $page = $queryParamsService->get("page");

        // then
        $this->assertEquals("1", $page);
    }

    /** "mb_min_length" rule */

    public function testLengthIsShorterThanMinimumLength()
    {
        // given
        $request = new Request();
        $request->query->set("q", "la");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_min_length" => 3,
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be at least 3 characters long");

        // when
        $queryParamsService->parse($specs);
    }

    public function testLengthIsLongerThanMinimumLength()
    {
        // given
        $request = new Request();
        $request->query->set("q", "les");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_min_length" => 3,
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $q = $queryParamsService->get("q");

        // then
        $this->assertEquals("les", $q);
    }

    /** "mb_max_length" rule */

    public function testLengthIsLongerThanMaximumLength()
    {
        // given
        $request = new Request();
        $request->query->set("q", "hiver");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_max_length" => 3,
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be 3 characters long or shorter");

        // when
        $queryParamsService->parse($specs);
    }

    public function testLengthIsShorterThanMaximumLength()
    {
        // given
        $request = new Request();
        $request->query->set("q", "été");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_max_length" => 3,
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $q = $queryParamsService->get("q");

        // then
        $this->assertEquals("été", $q);
    }


}
