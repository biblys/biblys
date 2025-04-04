<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

class CustomerTest extends TestCase
{

    public function testGetFullName()
    {
        // given
        $customer = new Customer();
        $customer->setFirstName("Maude");
        $customer->setLastName("Zarella");

        // when
        $fullName = $customer->getFullName();

        // then
        $this->assertEquals("Maude Zarella", $fullName);
    }

    public function testGetFullNameWithEmptyFirstName()
    {
        // given
        $customer = new Customer();
        $customer->setFirstName("");
        $customer->setLastName("Voltaire");

        // when
        $fullName = $customer->getFullName();

        // then
        $this->assertEquals("Voltaire", $fullName);
    }
}
