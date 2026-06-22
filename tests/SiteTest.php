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

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Model\OptionQuery;

require_once "setUp.php";

class SiteTest extends PHPUnit\Framework\TestCase
{
    public function testGetOptReturnsFalseWhenOptionDoesNotExist(): void
    {
        // given
        $site = EntityFactory::createSite();

        // when
        $value = $site->getOpt('nonexistent_key');

        // then
        $this->assertFalse($value);
    }

    public function testGetOptReturnsOptionValue(): void
    {
        // given
        $site = EntityFactory::createSite();
        ModelFactory::createSiteOption(key: 'my_option', value: 'my_value');

        // when
        $value = $site->getOpt('my_option');

        // then
        $this->assertEquals('my_value', $value);
    }

    public function testSetOptCreatesNewOption(): void
    {
        // given
        $site = EntityFactory::createSite();

        // when
        $site->setOpt('new_option', 'new_value');

        // then
        $option = OptionQuery::create()->filterByKey('new_option')->findOne();
        $this->assertNotNull($option);
        $this->assertEquals('new_value', $option->getValue());
    }

    public function testSetOptUpdatesExistingOption(): void
    {
        // given
        $site = EntityFactory::createSite();
        ModelFactory::createSiteOption(key: 'existing_option', value: 'old_value');

        // when
        $site->setOpt('existing_option', 'new_value');

        // then
        $option = OptionQuery::create()->filterByKey('existing_option')->findOne();
        $this->assertEquals('new_value', $option->getValue());
    }
}
