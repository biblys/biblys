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


namespace Legacy;

use Exception;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../setUp.php";

class DateFunctionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDateFunction()
    {
        // given
        $mysqlDate = "2019-04-28 02:42:29";

        // when
        $formattedDate = _date($mysqlDate, "j f Y à H:i");

        // then
        $this->assertEquals(
            "28 avril 2019 à 02:42",
            $formattedDate
        );
    }

    /**
     * @throws Exception
     */
    public function testDateFunctionWithoutTime()
    {
        // given
        $mysqlDate = "2019-04-28";

        // when
        $formattedDate = _date($mysqlDate, "j f Y à H:i");

        // then
        $this->assertEquals(
            "28 avril 2019 à 00:00",
            $formattedDate
        );
    }

    /**
     * @throws Exception
     */
    public function testDateFunctionWithoutDay()
    {
        // given
        $mysqlDate = "2019-04";

        // when
        $formattedDate = _date($mysqlDate, "f Y");

        // then
        $this->assertEquals(
            "avril 2019",
            $formattedDate
        );
    }

    /**
     * @throws Exception
     */
    public function testDateFunctionWithoutMonth()
    {
        // given
        $mysqlDate = "2019";

        // when
        $formattedDate = _date($mysqlDate, "Y");

        // then
        $this->assertEquals(
            "2019",
            $formattedDate
        );
    }

    /**
     * @throws Exception
     */
    public function testDateFunctionWithInvalidFormat()
    {
        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Cannot format date in unknown format: 201");

        // given
        $mysqlDate = 201;

        // when
        _date($mysqlDate, "j f Y à H:i");
    }
}
