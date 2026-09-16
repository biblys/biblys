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

use Biblys\Service\CurrentSite;
use Model\Article;
use Model\Stock;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Model\StockQuery;

class StockRepository
{
    /**
     * @return \Model\Stock[]
     * @throws PropelException
     */
    public function getAvailableItemsFor(Article $article, CurrentSite $currentSite): array
    {
        $query = StockQuery::create()
            ->filterByArticle($article)
            ->filterBySellingDate(null, Criteria::ISNULL)
            ->filterByReturnDate(null, Criteria::ISNULL)
            ->filterByLostDate(null, Criteria::ISNULL);

        $activeStock = $currentSite->getOption("active_stock");
        if ($activeStock) {
            $query->filterByStockage(explode(",", $activeStock));
        }

        return $query->find()->getArrayCopy();
    }

    /**
     * @return \Model\Stock[]
     * @throws PropelException
     */
    public function getAvailableUsedItemsFor(Article $article, CurrentSite $currentSite): array
    {
        $item = $article->getItem();

        $articleQuery = StockQuery::create()
            ->filterBySellingDate(null, Criteria::ISNULL)
            ->filterByReturnDate(null, Criteria::ISNULL)
            ->filterByLostDate(null, Criteria::ISNULL)
            ->filterByCondition(Stock::CONDITION_NEW, Criteria::NOT_EQUAL)
            ->filterBySellingPrice(null, Criteria::ISNOTNULL)
            ->useArticleQuery()
                ->filterById($article->getId());

        if ($item) {
            $articleQuery = $articleQuery->_or()->filterByItem($item);
        }

        $stockQuery = $articleQuery->endUse()
            ->orderBy("SellingPrice", Criteria::ASC);

        $activeStock = $currentSite->getOption("active_stock");
        if ($activeStock) {
            $stockQuery->filterByStockage(explode(",", $activeStock));
        }

        return $stockQuery->find()->getArrayCopy();
    }
}
