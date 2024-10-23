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

use Biblys\Service\CurrentSite;
use Exception;
use Model\Base\ShippingFeeQuery as BaseShippingFeeQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'shipping' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ShippingFeeQuery extends BaseShippingFeeQuery
{
    public static function createForSite(CurrentSite $currentSite): ShippingFeeQuery
    {
        $query = parent::create();
        $query->filterBySiteId($currentSite->getSite()->getId());
        return $query;
    }

    /**
     * @param CurrentSite $currentSite
     * @param Country $country
     * @param int $weight
     * @param int $amount
     * @return ShippingFee[]
     * @throws Exception
     */
    public static function getForCountryWeightAndAmount(
        CurrentSite $currentSite,
        Country $country,
        int $weight,
        int $amount
    ): array
    {
        $weightIncludingWrapping = $weight * 1.05;
        $zone = $country->getShippingZone();

        $query = self::createForSite($currentSite);
        $fees = $query->orderByFee()->find();

        $shippingTypes = ['magasin', 'normal', 'suivi', 'mondial-relay'];

        $feesForEachTypes = array_map(
            function ($type) use ($fees, $zone, $weightIncludingWrapping, $amount, $currentSite) {
                foreach ($fees as $fee) {
                    // Keeps only fees for current type
                    if ($fee->getType() !== $type) {
                        continue;
                    }

                    // Keep only shipping without article
                    if ($fee->getArticleId()) {
                        continue;
                    }

                    // Keep only fees for destination country's zone or ALL zones
                    if ($fee->getZone() !== $zone && $fee->getZone() !== 'ALL') {
                        continue;
                    }

                    // Keep only fees for weight higher than order
                    if ($fee->getMaxWeight() !== null && $fee->getMaxWeight() <= $weightIncludingWrapping) {
                        continue;
                    }

                    // Keep only fees for which order's amount is higher than min amount
                    if ($fee->getMinAmount() !== null && $amount < $fee->getMinAmount()) {
                        continue;
                    }

                    // Keep only fees for which order's amount is lesser than max amount
                    if ($fee->getMaxAmount() !== null && $amount > $fee->getMaxAmount()) {
                        continue;
                    }

                    // Return first fee that survived until here
                    return $fee;
                }
                return null;
            },
            $shippingTypes
        );

        return array_filter(
            $feesForEachTypes,
            function ($fee) { return $fee !== null; }
        );
    }
}
