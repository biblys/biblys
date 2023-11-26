<?php

namespace Model;

use Model\Base\RightQuery as BaseRightQuery;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for performing query and update operations on the 'rights' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class RightQuery extends BaseRightQuery
{
    /**
     * @throws PropelException
     */
    public function isUserAdminForSite(User $user, Site $site): bool
    {
        $adminRight = RightQuery::create()
            ->filterByUser($user)
            ->filterBySite($site)
            ->filterByIsAdmin(true)
            ->findOne();

        if($adminRight) {
            return true;
        }

        return false;
    }
}
