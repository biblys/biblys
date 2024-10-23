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
use Biblys\Exception\CannotDeleteArticleWithStock;
use DateTime;
use Exception;
use Model\Base\Article as BaseArticle;
use Model\StockQuery as ChildStockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

class Article extends BaseArticle
{
    public static int $AVAILABILITY_PRIVATELY_PRINTED = 10;

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function isPublished(): bool
    {
        return $this->getPubdate() <= new DateTime();
    }

    public function isPrivatelyPrinted(): bool
    {
        return $this->getAvailabilityDilicom() === self::$AVAILABILITY_PRIVATELY_PRINTED;
    }

    /**
     * @throws Exception
     */
    public function addContributor(People $contributor, \Biblys\Contributor\Job $job): Role
    {
        $role = RoleQuery::create()
            ->filterByArticleId($this->getId())
            ->filterByPeopleId($contributor->getId())
            ->filterByJobId($job->getId())
            ->findOne();

        if ($role) {
            return $role;
        }

        $role = new Role();
        $role->setArticleId($this->getId());
        $role->setPeopleId($contributor->getId());
        $role->setJobId($job->getId());
        $role->save();

        return $role;
    }

    /**
     * @throws PropelException
     */
    public function countAvailableStockItemsForSite(Site $site): int
    {
        $partial = $this->collStocksPartial && !$this->isNew();
        if (null === $this->collStocks || $partial) {
            if ($this->isNew() && null === $this->collStocks) {
                return 0;
            }

            return ChildStockQuery::create()
                ->filterByArticle($this)
                ->filterBySite($site)
                ->filterBySellingDate(null, Criteria::ISNULL)
                ->filterByReturnDate(null, Criteria::ISNULL)
                ->filterByLostDate(null, Criteria::ISNULL)
                ->count();
        }

        return count($this->collStocks);
    }

    public function getType(): ArticleType
    {
        return ArticleType::getById($this->getTypeId());
    }

    public function isWatermarkable(): bool
    {
        return $this->getLemoninkMasterId() !== null;
    }

    /**
     * @throws PropelException
     * @throws CannotDeleteArticleWithStock
     */
    public function preDelete(ConnectionInterface|null $con = null): bool
    {
        $stockItems = StockQuery::create()->filterByArticle($this)->count();
        if ($stockItems > 0) {
            throw new CannotDeleteArticleWithStock();
        }

        return true;
    }
}
