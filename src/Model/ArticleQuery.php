<?php

namespace Model;

use Biblys\Service\CurrentSite;
use Model\Base\ArticleQuery as BaseArticleQuery;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for performing query and update operations on the 'articles' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ArticleQuery extends BaseArticleQuery
{
    /**
     * @throws PropelException
     */
    public function filterForCurrentSite(CurrentSite $currentSite): ArticleQuery
    {
        $publisherFilter = $currentSite->getOption("publisher_filter");
        if (!$publisherFilter) {
            return $this;
        }

        $allowedPublishersIds = explode(",", $publisherFilter);
        return $this->filterByPublisherId($allowedPublishersIds);
    }
}
