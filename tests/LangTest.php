<?php
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
