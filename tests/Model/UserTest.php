<?php

namespace Model;

use Biblys\Test\Factory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class UserTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testIsAdminForSiteWithNonAdmin()
    {
        // when
        $site = Factory::createSite();
        $user = Factory::createUser();

        // when
        $isAdmin = $user->isAdminForSite($site);

        // then
        $this->assertFalse(
            $isAdmin,
            "it should return false if user is no admin"
        );
    }

    /**
     * @throws PropelException
     */
    public function testIsAdminForSiteWithAdmin()
    {
        // when
        $site = Factory::createSite();
        $user = Factory::createAdminUser($site);

        // when
        $isAdmin = $user->isAdminForSite($site);

        // then
        $this->assertTrue(
            $isAdmin,
            "it should return true if user is admin"
        );
    }
}