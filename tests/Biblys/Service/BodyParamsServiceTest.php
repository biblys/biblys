<?php

namespace Biblys\Service;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BodyParamsServiceTest extends TestCase
{

    /**
     * Valid value
     */

    public function testValidValue()
    {
        // given
        $request = new Request();
        $request->request->set("login", "user313");

        $specs = [
            "login" => [
                "type" => "string",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // when
        $bodyParamsService->parse($specs);
        $param = $bodyParamsService->get("login");

        // then
        $this->assertEquals("user313", $param);
    }

    public function testUnexpectedBodyParam(): void
    {
        // given
        $request = new Request();
        $request->request->set("ref", "unexpected body param");

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Unexpected parameter 'ref'");

        // when
        $bodyParamsService->parse($specs);
    }

    public function testUnknownRule(): void {
        // given
        $request = new Request();
        $request->request->set("q", "11");

        $specs = [
            "q" => [
                "is_prime" => "true",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown validation rule 'is_prime'");

        // when
        $bodyParamsService->parse($specs);
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
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' is required");

        // when
        $bodyParamsService->parse($specs);
    }

    /** "type" rule */

    public function testUnknownType(): void
    {
        // given
        $request = new Request();
        $request->request->set("q", "search terms");

        $specs = [
            "q" => [
                "type" => "book",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 'book' for type rule");

        // when
        $bodyParamsService->parse($specs);
    }

    /** "type:string" rule */

    public function testInvalidValueTypeForString()
    {
        // given
        $request = new Request();
        $request->request->set("q", [1]);

        $specs = [
            "q" => [
                "type" => "string",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be of type string");

        // when
        $bodyParamsService->parse($specs);
    }

    /** "type:numeric" rule */

    public function testInvalidValueTypeForInt()
    {
        // given
        $request = new Request();
        $request->request->set("page", "abc");

        $specs = [
            "page" => [
                "type" => "numeric",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'page' must be of type numeric");

        // when
        $bodyParamsService->parse($specs);
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
        $bodyParamsService = new BodyParamsService($request);
        $bodyParamsService->parse($specs);

        // when
        $page = $bodyParamsService->get("page");

        // then
        $this->assertEquals("0", $page);
    }

    public function testProvidedParameterWithDefaultValue()
    {
        // given
        $request = new Request();
        $request->request->set("page", "1");

        $specs = [
            "page" => [
                "type" => "string",
                "default" => "0",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);
        $bodyParamsService->parse($specs);

        // when
        $page = $bodyParamsService->get("page");

        // then
        $this->assertEquals("1", $page);
    }

    /** "mb_min_length" rule */

    public function testLengthIsShorterThanMinimumLength()
    {
        // given
        $request = new Request();
        $request->request->set("q", "la");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_min_length" => 3,
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be at least 3 characters long");

        // when
        $bodyParamsService->parse($specs);
    }

    public function testLengthIsLongerThanMinimumLength()
    {
        // given
        $request = new Request();
        $request->request->set("q", "les");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_min_length" => 3,
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);
        $bodyParamsService->parse($specs);

        // when
        $q = $bodyParamsService->get("q");

        // then
        $this->assertEquals("les", $q);
    }

    /** "mb_max_length" rule */

    public function testLengthIsLongerThanMaximumLength()
    {
        // given
        $request = new Request();
        $request->request->set("q", "hiver");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_max_length" => 3,
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be 3 characters long or shorter");

        // when
        $bodyParamsService->parse($specs);
    }

    public function testLengthIsShorterThanMaximumLength()
    {
        // given
        $request = new Request();
        $request->request->set("q", "été");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_max_length" => 3,
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);
        $bodyParamsService->parse($specs);

        // when
        $q = $bodyParamsService->get("q");

        // then
        $this->assertEquals("été", $q);
    }

    /** getInt */

    public function testGetIntConvertsNumericStringToInt(): void
    {
        // given
        $request = new Request();
        $request->request->set("page", "123");

        $specs = [
            "page" => [
                "type" => "numeric",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);
        $bodyParamsService->parse($specs);

        // when
        $page = $bodyParamsService->getInteger("page");

        // then
        $this->assertEquals(123, $page);
    }



    public function testGetIntThrowsForNonNumericValue(): void
    {
        // given
        $request = new Request();
        $request->request->set("alphabet", "abc");

        $specs = [
            "alphabet" => [
                "type" => "string",
            ],
        ];
        $bodyParamsService = new BodyParamsService($request);
        $bodyParamsService->parse($specs);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot get non numeric parameter 'alphabet' as an integer");

        // when
        $bodyParamsService->getInteger("alphabet");
    }
}
