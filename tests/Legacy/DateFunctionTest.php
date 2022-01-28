<?php

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
    public function testDateFunctionWithDayIncludingZero()
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
    public function testDateFunctionWithInvalidFormat()
    {
        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Cannot format date in unknown format: 1987");

        // given
        $mysqlDate = 2019-04-28;

        // when
        _date($mysqlDate, "j f Y à H:i");
    }
}
