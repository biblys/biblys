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

use Biblys\Data\ArticleType;
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
        $physicalTypes = ArticleType::getAllPhysicalTypes();
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
        $downloadableTypes = ArticleType::getAllDownloadableTypes();
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
