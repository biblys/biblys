<?php

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
