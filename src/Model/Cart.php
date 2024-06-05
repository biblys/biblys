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
    public function getPhysicalArticleCount(): int
    {
        $physicalTypes = Type::getAllPhysicalTypes();
        $physicalTypeIds = array_map(function ($type) {
            return $type->getId();
        }, $physicalTypes);
        return StockQuery::create()
            ->filterByCart($this)
            ->useArticleQuery()
            ->filterByTypeId($physicalTypeIds)
            ->endUse()
            ->count();
    }

    /**
     * @return int
     * @throws PropelException
     */
    public function getDownloadableArticleCount(): int
    {
        $downloadableTypes = Type::getAllDownloadableTypes();
        $downloadableTypeIds = array_map(function ($type) {
            return $type->getId();
        }, $downloadableTypes);
        return StockQuery::create()
            ->filterByCart($this)
            ->useArticleQuery()
            ->filterByTypeId($downloadableTypeIds)
            ->endUse()
            ->count();
    }

    /**
     * @throws PropelException
     */
    public function containsDownloadableArticles(): bool
    {
        return $this->getDownloadableArticleCount() > 0;
    }

    /**
     * @throws PropelException
     */
    public function containsPhysicalArticles(): bool
    {
        return $this->getPhysicalArticleCount();
    }
}
