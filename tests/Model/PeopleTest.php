<?php

namespace Model;

use PHPUnit\Framework\TestCase;

class PeopleTest extends TestCase
{
    public function testGetFullName()
    {
        // given
        $people = new People();
        $people->setFirstName("Mnémosyne");
        $people->setLastName("Pachidermata");

        // when
        $fullName = $people->getFullName();

        // then
        $this->assertEquals("Mnémosyne Pachidermata", $fullName);
    }

    public function testGetFullNameWithoutFirstName()
    {
        // given
        $people = new People();
        $people->setFirstName("");
        $people->setLastName("Y");

        // when
        $fullName = $people->getFullName();

        // then
        $this->assertEquals("Y", $fullName);
    }
}
