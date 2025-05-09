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
use Biblys\Exception\ArticleIsUnavailableException;
use Biblys\Exception\CannotDeleteArticleWithStock;
use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
use DateTime;
use Exception;
use Model\Base\Article as BaseArticle;
use Model\StockQuery as ChildStockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

class Article extends BaseArticle
{
    const AVAILABILITY_AVAILABLE = 1;
    const AVAILABILITY_UPCOMING = 2;
    const AVAILABILITY_REPRINT_IN_PROGRESS = 3;
    const AVAILABILITY_TEMPORARILY_UNAVAILABLE = 4;
    const AVAILABILITY_OUT_OF_PRINT = 6;
    const AVAILABILITY_TO_BE_REISSUED = 8;
    const AVAILABILITY_SOON_OUT_OF_PRINT = 9;

    const AVAILABILITY_PRIVATELY_PRINTED = 10;

    public function isAvailable(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_AVAILABLE;
    }

    public function isUpcoming(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_UPCOMING;
    }

    public function isToBeReprinted(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_REPRINT_IN_PROGRESS;
    }

    public function isTemporarilyUnavailable(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_TEMPORARILY_UNAVAILABLE;
    }

    public function isOutOfPrint(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_OUT_OF_PRINT;
    }

    public function isToBeReissued(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_TO_BE_REISSUED;
    }

    public function isSoonOutOfPrint(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_SOON_OUT_OF_PRINT;
    }

    public function isPrivatelyPrinted(): bool
    {
        return $this->getAvailabilityDilicom() === self::AVAILABILITY_PRIVATELY_PRINTED;
    }

    /**
     * @throws ArticleIsUnavailableException
     * @throws PropelException
     */
    public function ensureAvailability(): void
    {
        if ($this->isUpcoming()) {
            throw new ArticleIsUnavailableException("à paraître");
        }

        if ($this->isToBeReprinted()) {
            throw new ArticleIsUnavailableException("en cours de réimpression");
        }

        if ($this->isTemporarilyUnavailable()) {
            throw new ArticleIsUnavailableException("momentanément indisponible");
        }

        if ($this->isOutOfPrint()) {
            throw new ArticleIsUnavailableException("épuisé");
        }

        if ($this->isToBeReissued()) {
            throw new ArticleIsUnavailableException("à reparaître");
        }

        if ($this->isPrivatelyPrinted()) {
            throw new ArticleIsUnavailableException("hors commerce");
        }

        if (!$this->isPublished() && !$this->isPreorder()) {
            throw new ArticleIsUnavailableException("à paraître");
        }
    }

    /**
     * Returns true if publication date is in the past
     *
     * @throws PropelException
     * @throws Exception
     */
    public function isPublished(): bool
    {
        return $this->getPubdate() <= new DateTime();
    }

    /**
     * @throws PropelException
     */
    public function isPurchasable(): bool
    {
        if (!$this->isPublished() and !$this->isPreorder()) {
            return false;
        }

        if ($this->isAvailable() || $this->isSoonOutOfPrint()) {
            return true;
        }

        return false;
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
    public function countAvailableStockItems(): int
    {
        $partial = $this->collStocksPartial && !$this->isNew();
        if (null === $this->collStocks || $partial) {
            if ($this->isNew() && null === $this->collStocks) {
                return 0;
            }

            return ChildStockQuery::create()
                ->filterByArticle($this)
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

    public function isBundle(): bool
    {
        if ($this->getTypeId() === ArticleType::BUNDLE) {
            return true;
        }

        return false;
    }

    public function getArticlesFromBundle(): Collection
    {
        return ArticleQuery::create()
            ->joinWithLink()
            ->useLinkQuery()
                ->filterByBundleId($this->getId())
            ->endUse()
            ->find();
    }

    /**
     * @throws PropelException
     */
    public function isInABundle(): bool
    {
        return LinkQuery::create()
            ->filterByArticle($this)
            ->filterByBundleId(null, Criteria::ISNOTNULL)
            ->exists();
    }

    /**
     * @throws PropelException
     */
    public function getBundles(): Collection
    {
        $linksId = LinkQuery::create()
            ->select(["bundle_id"])
            ->filterByArticle($this)
            ->filterByBundleId(null, Criteria::ISNOTNULL)
            ->find()
            ->getArrayCopy();

        return ArticleQuery::create()->filterById($linksId)->find();
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

    public function isService(): bool
    {
        if ($this->getType()->isService()) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function getCartButtonLabel(): string
    {
        if ($this->getPubdate() > new DateTime("now")) {
            return "Précommander";
        }

        return "Ajouter au panier";
    }

    /**
     * @return Article[]
     */
    public function getVersions(): array
    {
        if (!$this->getItem()) {
            return [$this];
        }

        return ArticleQuery::create()->filterByItem($this->getItem())->find()->getArrayCopy();
    }

    /**
     * @throws IsbnParsingException
     */
    public function getIsbn(): string
    {
        return Isbn::convertToIsbn13($this->getEan());
    }
}
