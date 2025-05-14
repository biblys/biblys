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

use DateTime;
use Model\Base\Post as BasePost;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'posts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Post extends BasePost
{
    public const STATUS_OFFLINE = false;
    public const STATUS_ONLINE = true;

    public function getSlug(): string
    {
        return $this->getUrl();
    }

    public function isPublished(): bool
    {
        return !!$this->getStatus();
    }

    /**
     * @throws PropelException
     */
    public function isScheduled(): bool
    {
        return $this->getDate() > new DateTime();
    }

    /**
     * @throws PropelException
     */
    public function getNextPost(): ?Post
    {
        return PostQuery::create()
            ->filterByStatus(self::STATUS_ONLINE)
            ->filterByDate($this->getDate(), Criteria::GREATER_THAN)
            ->orderByCreatedAt()
            ->findOne();
    }

    /**
     * @throws PropelException
     */
    public function getPreviousPost(): ?Post
    {
        return PostQuery::create()
            ->filterByStatus(self::STATUS_ONLINE)
            ->filterByDate($this->getDate(), Criteria::LESS_THAN)
            ->orderByCreatedAt(Criteria::DESC)
            ->findOne();
    }
}
