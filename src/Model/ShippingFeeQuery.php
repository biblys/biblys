<?php

namespace Model;

use Biblys\Service\CurrentSite;
use InvalidArgumentException;
use Model\Base\ShippingFeeQuery as BaseShippingFeeQuery;
use Propel\Runtime\ActiveQuery\Criteria;

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
}
