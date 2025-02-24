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
        $isAdmin = RightQuery::create()->isUserAdmin($user);

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
        $isAdmin = RightQuery::create()->isUserAdmin($user);

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
        $isAdmin = RightQuery::create()->isUserAdmin($user);

        // then
        $this->assertTrue($isAdmin);
    }
}
