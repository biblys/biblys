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

class LangTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $lm = new LangManager();

        $lang = $lm->create(['lang_name' => 'Esperanto']);

        $this->assertInstanceOf('Lang', $lang);
        $this->assertEquals($lang->get('name'), 'Esperanto');

        return $lang;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(Lang $lang)
    {
        $lm = new LangManager();

        $gotLang = $lm->getById($lang->get('id'));

        $this->assertInstanceOf('Lang', $lang);

        return $lang;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(Lang $lang)
    {
        $lm = new LangManager();

        $lang->set('lang_name', 'Commun');
        $lm->update($lang);

        $updatedLang = $lm->getById($lang->get('id'));

        $this->assertTrue($updatedLang->has('updated'));
        $this->assertEquals($updatedLang->get('name'), 'Commun');

        return $updatedLang;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testDelete(Lang $lang)
    {
        $lm = new LangManager();

        $lm->delete($lang);

        $lang = $lm->getById($lang->get('id'));

        $this->assertFalse($lang);
    }
}
