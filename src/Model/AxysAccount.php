<?php

namespace Model;

use Biblys\Service\Validator\Validator;
use Exception;
use Model\Base\AxysAccount as BaseAxysAccount;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'users' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AxysAccount extends BaseAxysAccount
{
    public function isAdminForSite(Site $site): bool
    {
        $adminRight = RightQuery::create()
            ->filterByAxysUserId($this->getId())
            ->filterBySiteId($site->getId())
            ->findOne();

        if($adminRight) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function hasRightForPublisher(Publisher $publisher): bool
    {
        $publisherRight = RightQuery::create()
            ->filterByAxysAccount($this)
            ->filterByPublisherId($publisher->getId())
            ->findOne();

        if ($publisherRight) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function hasPublisherRight(): bool
    {
        $publisherRight = RightQuery::create()
            ->filterByAxysAccount($this)
            ->filterByPublisherId(null, Criteria::NOT_EQUAL)
            ->findOne();

        if ($publisherRight) {
            return true;
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function getCurrentRight(): Right
    {
        return RightQuery::create()
            ->filterByAxysAccount($this)
            ->findOne();
    }

    /**
     * @param ConnectionInterface|null $con
     * @return bool
     * @throws Exception
     */
    public function preSave(ConnectionInterface $con = null): bool
    {
        Validator::validate($this);

        return true;
    }
}
