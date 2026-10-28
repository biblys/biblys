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


namespace Usecase;

use Model\Article;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;

class ChangeStockItemArticleUsecase
{
    /**
     * @throws PropelException
     */
    public function execute(Stock $stockItem, Article $article): void
    {
        $stockItem->setArticle($article);
        $stockItem->save();
    }
}
