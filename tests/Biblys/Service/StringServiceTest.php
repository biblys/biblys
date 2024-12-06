<?php

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;

class StringServiceTest extends TestCase
{
    /** normalize */

    public function testNormalize()
    {
        // given
        $string = new StringService("Un seul être vous manque et tout est dépeuplé");

        // when
        $result = $string->normalize()->get();

        // then
        $this->assertEquals("Un seul etre vous manque et tout est depeuple", $result);
    }

    /** uppercase */

    public function testUppercase()
    {
        // given
        $string = new StringService("Un seul être vous manque et tout est dépeuplé");

        // when
        $result = $string->uppercase()->get();

        // then
        $this->assertEquals("UN SEUL ÊTRE VOUS MANQUE ET TOUT EST DÉPEUPLÉ", $result);
    }

    /** uppercase */

    public function testLowercase()
    {
        // given
        $string = new StringService("Un seul ÊTRE vous manque et tout est DÉPEUPLÉ");

        // when
        $result = $string->lowercase()->get();

        // then
        $this->assertEquals("un seul être vous manque et tout est dépeuplé", $result);
    }

    /** uppercase */

    public function testLimit()
    {
        // given
        $string = new StringService("Anticonstitutionnellement");

        // when
        $result = $string->limit(7)->get();

        // then
        $this->assertEquals("Anticon", $result);
    }
}
