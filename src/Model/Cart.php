<?php

namespace Model;

use Biblys\Article\Type;
use Model\Base\Cart as BaseCart;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'carts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Cart extends BaseCart
{
    /**
     * @throws PropelException
     */
    public function containsDownloadableArticles(): bool
    {
        $downloadableTypes = Type::getAllDownloadableTypes();
        $downloadableTypeIds = array_map(function ($type) {
            return $type->getId();
        }, $downloadableTypes);
        $items = StockQuery::create()
            ->filterByCart($this)
            ->useArticleQuery()
            ->filterByTypeId($downloadableTypeIds)
            ->count();

        return $items > 0;
    }
}
