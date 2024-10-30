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


namespace Biblys\Service;

use Biblys\Test\ModelFactory;
use Model\Option;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

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
    public function testGetOptionWithUnsetKey()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $option = $currentSite->getOption("shpping_notice");

        // then
        $this->assertNull(
            $option,
            "returns null if option is unset"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetOptionWithGivenDefaultValue()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $option = $currentSite->getOption("articles_per_page", "15");

        // then
        $this->assertEquals(
            "15",
            $option,
            "returns the given default value if option is unset"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetOptionWithPresetDefaultValue()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $option = $currentSite->getOption("articles_per_page");

        // then
        $this->assertEquals(
            "10",
            $option,
            "returns the present default value if option is unset"
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
    public function testSetOptionToEmptyValue()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("an_option_that_we_dont_want", "1");

        // when
        $currentSite->setOption("an_option_that_we_dont_want", "");

        // then
        $this->assertNull(
            $currentSite->getOption("an_option_that_we_dont_want"),
            "it deletes the option"
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

    /**
     * @throws PropelException
     */
    public function testGetTitle()
    {
        // given
        $site = ModelFactory::createSite(title: "Éditions Titre");
        $currentSite = new CurrentSite($site);

        // when
        $title = $currentSite->getTitle();

        // then
        $this->assertEquals("Éditions Titre", $title);
    }

    /**
     * allowsPublisher
     */

    /**
     * @throws PropelException
     */
    public function testAllowsPublisherWithNoFilter()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $allowsPublisher = $currentSite->allowsPublisher($publisher);

        // then
        $this->assertTrue($allowsPublisher);
    }

    /**
     * @throws PropelException
     */
    public function testAllowsPublisherWithIdInFilter()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $otherPublisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId() . "," . $otherPublisher->getId());

        // when
        $allowsPublisher = $currentSite->allowsPublisher($publisher);

        // then
        $this->assertTrue($allowsPublisher);
    }

    /**
     * @throws PropelException
     */
    public function testAllowsPublisherWithOtherIdInFilter()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $otherPublisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $otherPublisher->getId());

        // when
        $allowsPublisher = $currentSite->allowsPublisher($publisher);

        // then
        $this->assertFalse($allowsPublisher);
    }
}
