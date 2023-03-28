<?php

namespace Model;

use Biblys\Service\CurrentSite;
use Model\Base\ArticleCategoryQuery as BaseArticleCategoryQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'rayons' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ArticleCategoryQuery extends BaseArticleCategoryQuery
{
    public static function createForSite(CurrentSite $currentSite): ArticleCategoryQuery
    {
        $query = parent::create();
        $query->filterBySiteId($currentSite->getSite()->getId());
        return $query;
    }
}
