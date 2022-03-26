<?php

namespace Biblys\Service;

use Biblys\Test\ModelFactory;
use Model\Option;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../../setUp.php";

class CurrentSiteTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testBuildFromConfig()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());

        // when
        $currentSite = CurrentSite::buildFromConfig($config);

        // then
        $this->assertEquals(
            $site->getId(),
            $currentSite->getSite()->getId(),
            "it returns site from Config"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetOption()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $option = new Option();
        $option->setSite($site);
        $option->setKey("home_page_mode");
        $option->setValue("template");
        $option->save();

        // when
        $option = $currentSite->getOption("home_page_mode");

        // then
        $this->assertEquals(
            "template",
            $option,
            "it returns the value of the option"
        );
    }

    /**
     * @throws PropelException
     */
    public function testSetOption()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $currentSite->setOption("propose_newsletter_subscription", "1");

        // then
        $this->assertEquals(
            "1",
            $currentSite->getOption("propose_newsletter_subscription"),
            "it sets the value of the option"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasOptionEnabled()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("alerts_active", "1");

        // when
        $hasOptionEnabled = $currentSite->hasOptionEnabled("alerts_active");

        // then
        $this->assertTrue(
            $hasOptionEnabled,
            "it returns true if the option is enabled"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasOptionEnabledWhenNotEnabled()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $hasOptionEnabled = $currentSite->hasOptionEnabled("alerts_active");

        // then
        $this->assertFalse(
            $hasOptionEnabled,
            "it returns false when option is undefined"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetId()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $id = $currentSite->getId();

        // then
        $this->assertEquals($site->getId(), $id);
    }
}
