<?php

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
