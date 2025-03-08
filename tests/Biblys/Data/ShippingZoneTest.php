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


namespace Biblys\Data;

use PHPUnit\Framework\TestCase;

class ShippingZoneTest extends TestCase
{
    public function testGetAll()
    {
        // when
        $shippingZones = ShippingZone::getAll();

        // then
        $zoneF = array_filter($shippingZones, fn ($zone) => $zone->getCode() === "F")[0];
        $this->assertEquals("France métropolitaine, Andorre, Monaco", $zoneF->getDescription());
    }

    public function testGetCountries()
    {
        // given
        $shippingZones = ShippingZone::getAll();
        $zoneF = array_filter($shippingZones, fn ($zone) => $zone->getCode() === "F")[0];

        // when
        $countries = $zoneF->getCountries();

        // then
        $this->assertEquals("Andorre", $countries[0]->getName());
    }
}
