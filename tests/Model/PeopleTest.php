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
