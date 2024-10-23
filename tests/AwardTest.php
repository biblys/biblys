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

use Biblys\Legacy\LegacyCodeHelper;

require_once "setUp.php";

class AwardTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $am = new AwardManager();

        $award = $am->create(['award_name' => 'Prix Goncourt']);

        $this->assertInstanceOf('Award', $award);
        $this->assertEquals($award->get('name'), 'Prix Goncourt');

        return $award;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(Award $award)
    {
        $am = new AwardManager();

        $gotAward = $am->getById($award->get('id'));

        $this->assertInstanceOf('Award', $award);

        return $award;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(Award $award)
    {
        $am = new AwardManager();

        $award->set('award_name', 'Prix Fémina');
        $am->update($award);

        $updatedAward = $am->getById($award->get('id'));

        $this->assertTrue($updatedAward->has('updated'));
        $this->assertEquals($updatedAward->get('name'), 'Prix Fémina');

        return $updatedAward;
    }

    /**
     * Test that awards cannot be created without a name
     */
    public function testCreateAwardWithoutAName()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Le prix littéraire doit avoir un nom.");

        $am = new AwardManager();

        $award = new Award(['award_name' => '']);

        $am->validate($award);
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testDelete(Award $award)
    {
        $am = new AwardManager();

        $am->delete($award);

        $award = $am->getById($award->get('id'));

        $this->assertFalse($award);
    }
}
