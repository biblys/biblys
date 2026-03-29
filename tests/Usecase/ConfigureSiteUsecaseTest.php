<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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


namespace Usecase;

use Biblys\Test\ModelFactory;
use Exception;
use Model\SiteQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class ConfigureSiteUsecaseTest extends TestCase
{

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testUsecaseUpdatesAlreadyExistingSite()
    {
        // given
        ModelFactory::createSite(title: "Existing site", domain: "https://existing-site.com", contact: "contact@existing-site.com");
        $usecase = new ConfigureSiteUsecase();

        // when
        $updatedSite = $usecase->execute(siteName: "New site", baseUrl: "https://new-site.com", contactEmail: "contact@new-site.com");

        // then
        $this->assertEquals("New site", $updatedSite->getTitle());
        $this->assertEquals("https://new-site.com", $updatedSite->getDomain());
        $this->assertEquals("contact@new-site.com", $updatedSite->getContact());
        $this->assertEquals("new-site", $updatedSite->getName());

        $siteInDB = SiteQuery::create()->findByTitle("New site")->getFirst();
        $this->assertNotNull($siteInDB);
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function testUsecaseSucceeds()
    {
        // given
        $usecase = new ConfigureSiteUsecase();

        // when
        $createdSite = $usecase->execute(siteName: "New site", baseUrl: "https://new-site.com", contactEmail: "contact@new-site.com");

        // then
        $this->assertEquals("New site", $createdSite->getTitle());
        $this->assertEquals("https://new-site.com", $createdSite->getDomain());
        $this->assertEquals("contact@new-site.com", $createdSite->getContact());
        $this->assertEquals("new-site", $createdSite->getName());

        $siteInDB = SiteQuery::create()->findByTitle("New site")->getFirst();
        $this->assertNotNull($siteInDB);
    }
}