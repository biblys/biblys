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

    public function testMissingParameter()
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

    /** "type" rule */

    public function testUnknownType(): void
    {
        // given
        $request = new Request();
        $request->query->set("q", "search terms");

        $specs = [
            "q" => [
                "type" => "book",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 'book' for type rule");

        // when
        $queryParamsService->parse($specs);
    }

    /** "type:string" rule */

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

    /** "type:numeric" rule */

    public function testInvalidValueTypeForInt()
    {
        // given
        $request = new Request();
        $request->query->set("page", "abc");

        $specs = [
            "page" => [
                "type" => "numeric",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'page' must be of type numeric");

        // when
        $queryParamsService->parse($specs);
    }

    /** "default" rule */

    public function testMissingParameterWithDefaultValue()
    {
        // given
        $request = new Request();

        $specs = [
            "page" => [
                "type" => "string",
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

    public function testProvidedParameterWithDefaultValue()
    {
        // given
        $request = new Request();
        $request->query->set("page", "1");

        $specs = [
            "page" => [
                "type" => "string",
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

    /** getInt */

    public function testGetIntConvertsNumericStringToInt(): void
    {
        // given
        $request = new Request();
        $request->query->set("page", "123");

        $specs = [
            "page" => [
                "type" => "numeric",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $page = $queryParamsService->getInteger("page");

        // then
        $this->assertEquals(123, $page);
    }



    public function testGetIntThrowsForNonNumericValue(): void
    {
        // given
        $request = new Request();
        $request->query->set("alphabet", "abc");

        $specs = [
            "alphabet" => [
                "type" => "string",
            ],
        ];
        $queryParamsService = new QueryParamsService($request);
        $queryParamsService->parse($specs);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot get non numeric parameter 'alphabet' as an integer");

        // when
        $queryParamsService->getInteger("alphabet");
    }
}
