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

use Biblys\Data\ArticleType;
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
            ->filterByTypeId(ArticleType::EBOOK)
            ->findOne();
    }

    /**
     * Resets generated search terms of all articles with a single query,
     * without loading articles in memory.
     *
     * @throws PropelException
     */
    public function resetSearchTerms(): void
    {
        ArticleQuery::create()->update(["KeywordsGenerated" => null]);
    }

    /**
     * Searches articles whose search terms contain every word of the term,
     * restricted to the publishers and collections allowed on the current site.
     *
     * @return Article[]
     * @throws PropelException
     */
    public function search(string $term, CurrentSite $currentSite, int $limit = 100): array
    {
        $keywords = preg_split('/\s+/', trim($term), flags: PREG_SPLIT_NO_EMPTY);
        if (empty($keywords)) {
            return [];
        }

        $query = ArticleQuery::create();
        foreach ($keywords as $keyword) {
            $query->filterByKeywords("%$keyword%", Criteria::LIKE);
        }

        $publisherFilter = $currentSite->getOption("publisher_filter");
        if ($publisherFilter) {
            $query->filterByPublisherId(explode(",", $publisherFilter));
        }

        $hiddenCollections = $currentSite->getOption("collection_filter_hide");
        if ($hiddenCollections) {
            $query->filterByCollectionId(explode(",", $hiddenCollections), Criteria::NOT_IN);
        }

        return $query->limit($limit)->find()->getArrayCopy();
    }
}
