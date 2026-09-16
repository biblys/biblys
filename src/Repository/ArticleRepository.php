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
use Model\ArticleQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class ArticleRepository
{
    /**
     * @throws PropelException
     */
    public function findEbookVersionFor(Article $article, CurrentSite $currentSite): ?Article
    {
        $item = $article->getItem();
        $ebookPublisherId = $currentSite->getOption("ebook_publisher_id");

        if (!$item || !$ebookPublisherId) {
            return null;
        }

        return ArticleQuery::create()
            ->filterByItem($item)
            ->filterById($article->getId(), Criteria::NOT_EQUAL)
            ->filterByPublisherId((int) $ebookPublisherId)
            ->findOne();
    }
}
