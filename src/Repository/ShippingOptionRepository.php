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

use Model\CountryQuery;
use Model\ShippingOption;
use Model\ShippingOptionQuery;
use Model\ShippingZonesCountriesQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class ShippingOptionRepository
{
    /**
     * @return array<int, array{fee: int, countryCodes: string[]}>
     * @throws PropelException
     */
    public function getEntryRates(): array
    {
        $lowestFeeByZone = $this->_getLowestFeeByZone();
        if ($lowestFeeByZone === []) {
            return [];
        }

        $lowestFeeByCountryCode = $this->_getLowestFeeByCountryCode($lowestFeeByZone);

        $countryCodesByFee = [];
        foreach ($lowestFeeByCountryCode as $countryCode => $fee) {
            $countryCodesByFee[$fee][] = (string) $countryCode;
        }
        ksort($countryCodesByFee);

        $rates = [];
        foreach ($countryCodesByFee as $fee => $countryCodes) {
            sort($countryCodes);
            $rates[] = ["fee" => $fee, "countryCodes" => $countryCodes];
        }

        return $rates;
    }

    /**
     * @return array<int, int> fee in cents indexed by zone id
     * @throws PropelException
     */
    private function _getLowestFeeByZone(): array
    {
        $options = ShippingOptionQuery::create()
            ->filterByArchivedAt(null, Criteria::ISNULL)
            ->filterByShippingZoneId(null, Criteria::ISNOTNULL)
            ->find();

        $lowestFeeByZone = [];
        foreach ($options as $option) {
            if ($option->getType() === ShippingOption::PICKUP_TYPE || $option->getFee() === null) {
                continue;
            }

            $zoneId = $option->getShippingZoneId();
            $lowestFeeByZone[$zoneId] = min($lowestFeeByZone[$zoneId] ?? PHP_INT_MAX, $option->getFee());
        }

        return $lowestFeeByZone;
    }

    /**
     * @param array<int, int> $lowestFeeByZone
     * @return array<string, int> fee in cents indexed by country code
     * @throws PropelException
     */
    private function _getLowestFeeByCountryCode(array $lowestFeeByZone): array
    {
        $zoneCountries = ShippingZonesCountriesQuery::create()
            ->filterByShippingZoneId(array_keys($lowestFeeByZone))
            ->find();

        $countryIds = [];
        foreach ($zoneCountries as $zoneCountry) {
            $countryIds[] = $zoneCountry->getCountryId();
        }

        $codeByCountryId = [];
        foreach (CountryQuery::create()->filterById($countryIds)->find() as $country) {
            $codeByCountryId[$country->getId()] = $country->getCode();
        }

        $lowestFeeByCountryCode = [];
        foreach ($zoneCountries as $zoneCountry) {
            $countryCode = $codeByCountryId[$zoneCountry->getCountryId()] ?? null;
            if (!$countryCode) {
                continue;
            }

            $fee = $lowestFeeByZone[$zoneCountry->getShippingZoneId()];
            $lowestFeeByCountryCode[$countryCode] = min($lowestFeeByCountryCode[$countryCode] ?? PHP_INT_MAX, $fee);
        }

        return $lowestFeeByCountryCode;
    }
}
