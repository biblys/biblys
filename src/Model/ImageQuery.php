<?php

namespace Model;

use Model\Base\ImageQuery as BaseImageQuery;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for performing query and update operations on the 'images' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ImageQuery extends BaseImageQuery
{
    /**
     * @throws PropelException
     */
    public function filterByModel(Article|Stock|Post|Publisher $model): ImageQuery
    {
        return match (get_class($model)) {
            Article::class => $this->filterByArticle($model),
            Stock::class => $this->filterByStockItem($model),
            Post::class => $this->filterByPost($model),
            Publisher::class => $this->filterByPublisher($model),
            default => $this,
        };
    }
}
