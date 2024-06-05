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
        $items = StockQuery::create()->filterByCart($this)->find();
        foreach ($items as $item) {
            $type = Type::getById($item->getArticle()->getTypeId());
            if ($type->isDownloadable()) {
                return true;
            }
        }

        return false;
    }
}
