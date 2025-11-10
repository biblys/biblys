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


namespace Biblys\Service;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GenericParamsService extends ParamsService
{
    public function __construct(private readonly Request $request)
    {
        $params = $this->request->query->all();
        parent::__construct($params);
    }
}

class ParamsServiceTest extends TestCase
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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'q' must be of type string");

        // when
        $queryParamsService->parse($specs);
    }

    /** "type:numeric" rule */

    public function testInvalidValueTypeForNumeric()
    {
        // given
        $request = new Request();
        $request->query->set("page", "abc");

        $specs = [
            "page" => [
                "type" => "numeric",
            ],
        ];
        $queryParamsService = new GenericParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'page' must be of type numeric");

        // when
        $queryParamsService->parse($specs);
    }

    /** "type:boolean" rule */

    public function testInvalidValueTypeForBoolean()
    {
        // given
        $request = new Request();
        $request->query->set("is_enabled", "maybe");

        $specs = [
            "is_enabled" => [
                "type" => "boolean",
            ],
        ];
        $queryParamsService = new GenericParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'is_enabled' must be of type boolean");

        // when
        $queryParamsService->parse($specs);
    }

    /** "type:array" rule */

    public function testInvalidValueTypeForArray()
    {
        // given
        $request = new Request();
        $request->query->set("articles", "string");

        $specs = [
            "articles" => [
                "type" => "array",
            ],
        ];
        $queryParamsService = new GenericParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'articles' must be of type array");

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
        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $page = $queryParamsService->get("page");

        // then
        $this->assertEquals("0", $page);
    }

    public function testEmptyParameterWithDefaultValue()
    {
        // given
        $request = new Request();
        $request->query->set("page", "");

        $specs = [
            "page" => [
                "type" => "numeric",
                "default" => "0",
            ],
        ];
        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $page = $queryParamsService->get("page");

        // then
        $this->assertEquals(0, $page);
    }

    public function testNullAsDefaultValue()
    {
        // given
        $request = new Request();

        $specs = [
            "publisher_id" => [
                "type" => "numeric",
                "default" => null,
            ],
        ];
        $paramsService = new GenericParamsService($request);
        $paramsService->parse($specs);

        // when
        $value = $paramsService->getInteger("publisher_id");

        // then
        $this->assertNull($value);
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
        $queryParamsService = new GenericParamsService($request);
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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $q = $queryParamsService->get("q");

        // then
        $this->assertEquals("les", $q);
    }

    public function testLengthIsIgnoredIfParameterIsOptional()
    {
        // given
        $request = new Request();
        $request->query->set("q", "");

        $specs = [
            "q" => [
                "type" => "string",
                "mb_min_length" => 3,
                "default" => "",
            ],
        ];
        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $q = $queryParamsService->get("q");

        // then
        $this->assertEquals("", $q);
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
        $queryParamsService = new GenericParamsService($request);

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
        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse($specs);

        // when
        $q = $queryParamsService->get("q");

        // then
        $this->assertEquals("été", $q);
    }

    /** "min" rule */

    public function testIfValueIsLessThanMinimum()
    {
        // given
        $request = new Request();
        $request->query->set("p", "1");

        $specs = [
            "p" => [
                "type" => "numeric",
                "min" => 3,
            ],
        ];
        $queryParamsService = new GenericParamsService($request);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Parameter 'p' cannot be less than 3");

        // when
        $queryParamsService->parse($specs);
    }

    public function testIfValueEqualsMinimum()
    {
        // given
        $request = new Request();
        $request->query->set("p", "3");

        $specs = [
            "p" => [
                "type" => "numeric",
                "min" => 3,
            ],
        ];
        $queryParamsService = new GenericParamsService($request);

        // when
        $queryParamsService->parse($specs);

        // then
        $this->expectNotToPerformAssertions();
    }

    public function testIfValueIsGreaterThanMinimum()
    {
        // given
        $request = new Request();
        $request->query->set("p", "5");

        $specs = [
            "p" => [
                "type" => "numeric",
                "min" => 3,
            ],
        ];
        $queryParamsService = new GenericParamsService($request);

        // when
        $queryParamsService->parse($specs);

        // then
        $this->expectNotToPerformAssertions();
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
        $queryParamsService = new GenericParamsService($request);
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
        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse($specs);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot get non numeric parameter 'alphabet' as an integer");

        // when
        $queryParamsService->getInteger("alphabet");
    }


    /** getBool */

    public function testGetIntConvertsBooleanStringToBoolean(): void
    {
        // given
        $request = new Request();
        $request->query->set("true", "true");
        $request->query->set("false", "false");
        $request->query->set("one", "1");
        $request->query->set("zero", "0");
        $request->query->set("yes", "yes");
        $request->query->set("no", "no");
        $request->query->set("on", "on");
        $request->query->set("off", "off");

        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse([
            "true" => ["type" => "boolean"],
            "false" => ["type" => "boolean"],
            "one" => ["type" => "boolean"],
            "zero" => ["type" => "boolean"],
            "yes" => ["type" => "boolean"],
            "no" => ["type" => "boolean"],
            "on" => ["type" => "boolean"],
            "off" => ["type" => "boolean"],
        ]);

        // when
        $true = $queryParamsService->getBoolean("true");
        $false = $queryParamsService->getBoolean("false");
        $one = $queryParamsService->getBoolean("one");
        $zero = $queryParamsService->getBoolean("zero");
        $yes = $queryParamsService->getBoolean("yes");
        $no = $queryParamsService->getBoolean("no");
        $on = $queryParamsService->getBoolean("on");
        $off = $queryParamsService->getBoolean("off");

        // then
        $this->assertTrue($true);
        $this->assertFalse($false);
        $this->assertTrue($one);
        $this->assertFalse($zero);
        $this->assertTrue($yes);
        $this->assertFalse($no);
        $this->assertTrue($on);
        $this->assertFalse($off);
    }


    /** getArray */

    public function testGetArrayReturnsArray(): void
    {
        // given
        $request = new Request();
        $request->query->set("articles", [1, 2, 3]);

        $queryParamsService = new GenericParamsService($request);
        $queryParamsService->parse(["articles" => ["type" => "array"]]);

        // when
        $array = $queryParamsService->getArray("articles");

        // then
        $this->assertEquals([1, 2, 3], $array);
    }
}
