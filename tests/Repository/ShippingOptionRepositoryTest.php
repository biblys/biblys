<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace Repository;

use Biblys\Test\ModelFactory;
use Model\ShippingOptionQuery;
use Model\ShippingZoneQuery;
use Model\ShippingZonesCountriesQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class ShippingOptionRepositoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        ShippingOptionQuery::create()->deleteAll();
        ShippingZonesCountriesQuery::create()->deleteAll();
        ShippingZoneQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesReturnsLowestFeeOfTheZone(): void
    {
        // given
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 650, shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([["fee" => 450, "countryCodes" => ["FR"]]], $rates);
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesIgnoresArchivedAndPickupOptions(): void
    {
        // given
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 100, isArchived: true, shippingZone: $zone);
        ModelFactory::createShippingOption(type: "magasin", fee: 0, shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 500, shippingZone: $zone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([["fee" => 500, "countryCodes" => ["FR"]]], $rates);
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesReturnsNothingForZoneWithOnlyIgnoredOptions(): void
    {
        // given
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(type: "magasin", fee: 0, shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 300, isArchived: true, shippingZone: $zone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([], $rates);
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesIgnoresOptionWithoutZoneAndZoneWithoutCountry(): void
    {
        // given
        ModelFactory::createShippingOption(fee: 200, shippingZone: null);
        $emptyZone = ModelFactory::createShippingZone(name: "Zone vide");
        ModelFactory::createShippingOption(fee: 300, shippingZone: $emptyZone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([], $rates);
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesIgnoresCountryWithoutCode(): void
    {
        // given
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "Sans code", code: "", shippingZone: $zone);
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 500, shippingZone: $zone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([["fee" => 500, "countryCodes" => ["FR"]]], $rates);
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesKeepsLowestFeeForCountryInTwoZones(): void
    {
        // given
        $franceZone = ModelFactory::createShippingZone(name: "France");
        $europeZone = ModelFactory::createShippingZone(name: "Europe");
        $france = ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $franceZone);
        $france->addShippingZone($europeZone);
        $france->save();
        ModelFactory::createCountry(name: "Belgique", code: "BE", shippingZone: $europeZone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $franceZone);
        ModelFactory::createShippingOption(fee: 900, shippingZone: $europeZone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([
            ["fee" => 450, "countryCodes" => ["FR"]],
            ["fee" => 900, "countryCodes" => ["BE"]],
        ], $rates);
    }

    /**
     * @throws PropelException
     */
    public function testGetEntryRatesGroupsCountriesSharingTheSameFee(): void
    {
        // given
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $zone);
        ModelFactory::createCountry(name: "Belgique", code: "BE", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 500, shippingZone: $zone);
        $repository = new ShippingOptionRepository();

        // when
        $rates = $repository->getEntryRates();

        // then
        $this->assertEquals([["fee" => 500, "countryCodes" => ["BE", "FR"]]], $rates);
    }
}
