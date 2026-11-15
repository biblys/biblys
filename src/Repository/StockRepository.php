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
use Model\Cart;
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
        return $this->buildAvailableItemsQuery($currentSite)
            ->filterByArticle($article)
            ->find()
            ->getArrayCopy();
    }

    /**
     * Available copies that are not reserved in a point of sale cart,
     * for several articles at once (a single query), grouped by article id.
     *
     * @param Article[] $articles
     * @return array<int, \Model\Stock[]>
     * @throws PropelException
     */
    public function getItemsAvailableAtPointOfSaleForArticles(array $articles, CurrentSite $currentSite): array
    {
        if (empty($articles)) {
            return [];
        }

        $articleIds = array_map(fn(Article $article) => $article->getId(), $articles);
        $items = $this->buildAvailableItemsQuery($currentSite)
            ->filterByArticleId($articleIds)
            ->useCartQuery()
                ->filterByType(Cart::TYPE_SHOP, Criteria::NOT_EQUAL)
                ->_or()
                ->filterByType(null, Criteria::ISNULL)
            ->endUse()
            ->find();

        $itemsByArticleId = [];
        foreach ($items as $item) {
            $itemsByArticleId[$item->getArticleId()][] = $item;
        }

        return $itemsByArticleId;
    }

    private function buildAvailableItemsQuery(CurrentSite $currentSite): StockQuery
    {
        $query = StockQuery::create()
            ->filterBySellingDate(null, Criteria::ISNULL)
            ->filterByReturnDate(null, Criteria::ISNULL)
            ->filterByLostDate(null, Criteria::ISNULL);

        $activeStock = $currentSite->getOption("active_stock");
        if ($activeStock) {
            $query->filterByStockage(explode(",", $activeStock));
        }

        return $query;
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
