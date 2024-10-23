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

use DateTime;
use Model\Base\SpecialOfferQuery as BaseSpecialOfferQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'special_offers' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class SpecialOfferQuery extends BaseSpecialOfferQuery
{
    public function filterByActive(): self
    {
        $now = new DateTime();
        return $this
            ->filterByStartDate($now, self::LESS_EQUAL)
            ->filterByEndDate($now, self::GREATER_EQUAL);
    }
}
