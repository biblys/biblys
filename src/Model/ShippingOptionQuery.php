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
use Model\Base\ShippingOptionQuery as BaseShippingOptionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'shipping' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ShippingOptionQuery extends BaseShippingOptionQuery
{
    public static function createForSite(CurrentSite $currentSite): ShippingOptionQuery
    {
        $query = parent::create();
        $query->filterBySiteId($currentSite->getSite()->getId());
        return $query;
    }

    /**
     * @return ShippingOption[]
     * @throws Exception
     */
    public static function getForCountryAndWeightAndAmountAndArticleCount(
        CurrentSite $currentSite,
        Country $country,
        int $weight,
        int $amount,
        int $articleCount
    ): array
    {
        $zone = $country->getShippingZoneCode();

        $query = self::createForSite($currentSite);
        $fees = $query->orderByFee()->find();

        $shippingTypes = ['magasin', 'normal', 'colissimo', 'mondial-relay'];

        $feesForEachTypes = array_map(
            function ($type) use ($fees, $zone, $weight, $amount, $currentSite, $articleCount) {
                /** @var ShippingOption $fee */
                foreach ($fees as $fee) {
                    // Keeps only active fees
                    if ($fee->isArchived()) {
                        continue;
                    }

                    // Keeps only fees for current type
                    if ($fee->getType() !== $type) {
                        continue;
                    }

                    // Keep only shipping without an article
                    if ($fee->getArticleId()) {
                        continue;
                    }

                    // Keep only fees for destination country's zone or ALL zones
                    if ($fee->getZoneCode() !== $zone && $fee->getZoneCode() !== 'ALL') {
                        continue;
                    }

                    // Keep only fees for weight higher than order
                    if ($fee->getMaxWeight() !== null && $fee->getMaxWeight() < $weight) {
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

                    // Keep only fees for which order's article count is lesser than max articles
                    if ($fee->getMaxArticles() !== null && $articleCount > $fee->getMaxArticles()) {
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
