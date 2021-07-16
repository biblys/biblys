<?php

namespace Model;

use Biblys\Service\Validator\Validator;
use Exception;
use Model\Base\User as BaseUser;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'users' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class User extends BaseUser
{
    public function isAdminForSite(Site $site): bool
    {
        $adminRight = RightQuery::create()
            ->filterByUserId($this->getId())
            ->filterBySiteId($site->getId())
            ->findOne();

        if($adminRight) {
            return true;
        }

        return false;
    }

    /**
     * @param ConnectionInterface|null $con
     * @return bool
     * @throws Exception
     */
    public function preSave(ConnectionInterface $con = null)
    {
        Validator::validate($this);

        return true;
    }
}
