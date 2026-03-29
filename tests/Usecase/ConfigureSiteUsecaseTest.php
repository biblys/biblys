<?php

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