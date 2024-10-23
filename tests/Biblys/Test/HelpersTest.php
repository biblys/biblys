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
