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


use PHPUnit\Framework\TestCase;

require_once __DIR__."/setUp.php";

class SiteTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAllowsPublisherWithIdWithoutFilter()
    {
        // given
        $sm = new SiteManager();
        $site = $sm->create();

        // when
        $publisherIsAllowed = $site->allowsPublisherWithId(1);

        // then
        $this->assertTrue(
            $publisherIsAllowed,
            "it should be allowed when there is no publisher filter"
        );
    }

    /**
     * @throws Exception
     */
    public function testAllowsPublisherWithIdWithAllowingFilter()
    {
        // given
        $sm = new SiteManager();
                $site = $sm->create();
        $site->setOpt("publisher_filter", "3,1,2");

        // when
        $publisherIsAllowed = $site->allowsPublisherWithId(1);

        // then
        $this->assertTrue(
            $publisherIsAllowed,
            "it should be allowed when publisher is in filter"
        );
    }

    /**
     * @throws Exception
     */
    public function testAllowsPublisherWithIdWithoutAllowingFilter()
    {
        // given
        $sm = new SiteManager();
                $site = $sm->create();
        $site->setOpt("publisher_filter", "3,2");

        // when
        $publisherIsAllowed = $site->allowsPublisherWithId(1);

        // then
        $this->assertFalse(
            $publisherIsAllowed,
            "it should not be allowed when publisher is not in filter"
        );
    }
}
