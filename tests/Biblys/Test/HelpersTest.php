<?php

namespace Biblys\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class HelpersTest extends TestCase
{

    public function testRunAndCatchExceptionWhenNoExceptionIsThrown()
    {
        // given
        $testedFunction = function() {
            return "";
        };

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Excepted function to throw an exception, but none was.");

        // when
        Helpers::runAndCatchException($testedFunction);
    }

    /**
     * @throws Exception
     */
    public function testRunAndCatchException()
    {
        // given
        $testedFunction = function() {
            throw new BadRequestHttpException("Test exception");
        };

        // when
        $throwException = Helpers::runAndCatchException($testedFunction);

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $throwException);
        $this->assertEquals("Test exception", $throwException->getMessage());
    }
}
