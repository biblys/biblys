<?php

namespace Model;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class RightQueryTest extends TestCase
{

    /**
     * #isUserAdminForSite
     */

    /**
     * @throws PropelException
     */
    public function testIsUserAdminForSiteWithSimpleUser()
    {
        // given
        $user = ModelFactory::createUser();
        $site = ModelFactory::createSite();

        // when
        $isAdmin = RightQuery::create()->isUserAdminForSite($user, $site);

        // then
        $this->assertFalse($isAdmin);
    }

    /**
     * @throws PropelException
     */
    public function testIsUserAdminForSiteWithPublisherUser()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createPublisherUser(site: $site);

        // when
        $isAdmin = RightQuery::create()->isUserAdminForSite($user, $site);

        // then
        $this->assertFalse($isAdmin);
    }

    /**
     * @throws PropelException
     */
    public function testIsUserAdminForSiteWithAdminUser()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser(site: $site);

        // when
        $isAdmin = RightQuery::create()->isUserAdminForSite($user, $site);

        // then
        $this->assertTrue($isAdmin);
    }
}
