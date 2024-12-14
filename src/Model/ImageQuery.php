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
    public function filterByModel(Article|Stock|Post|Publisher|People|Event $model): ImageQuery
    {
        return $this->filterByModelId(get_class($model), $model->getId());
    }

    /**
     * @throws PropelException
     */
    public function filterByModelId(string $modelType, int $modelId): ImageQuery
    {
        return match ($modelType) {
            Article::class => $this->filterByArticleId($modelId),
            Stock::class => $this->filterByStockItemId($modelId),
            Post::class => $this->filterByPostId($modelId),
            Publisher::class => $this->filterByPublisherId($modelId),
            People::class => $this->filterByContributorId($modelId),
            Event::class => $this->filterByEventId($modelId),
            default => $this,
        };
    }
}
