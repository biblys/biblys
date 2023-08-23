<?php

namespace Model;

use Biblys\Article\Type;
use Exception;
use Model\Base\Article as BaseArticle;
use Model\StockQuery as ChildStockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class Article extends BaseArticle
{
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

    public function getType(): Type
    {
        return Type::getById($this->getTypeId());
    }
}
