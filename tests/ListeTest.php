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

require_once "setUp.php";

class ListeTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $lm = new ListeManager();

        $list = $lm->create();

        $this->assertInstanceOf('Liste', $list);

        return $list;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Liste $list)
    {
        $lm = new ListeManager();

        $gotListe = $lm->getById($list->get('id'));

        $this->assertInstanceOf('Liste', $list);
        $this->assertEquals($list->get('id'), $gotListe->get('id'));

        return $list;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Liste $list)
    {
        $lm = new ListeManager();

        $list->set('list_title', 'Listetou');
        $lm->update($list);

        $updatedListe = $lm->getById($list->get('id'));

        $this->assertTrue($updatedListe->has('updated'));
        $this->assertEquals($updatedListe->get('title'), 'Listetou');

        return $updatedListe;
    }

    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Liste $list)
    {
        $lm = new ListeManager();

        $lm->delete($list, 'Test entity');

        $listExists = $lm->getById($list->get('id'));

        $this->assertFalse($listExists);
    }
}
