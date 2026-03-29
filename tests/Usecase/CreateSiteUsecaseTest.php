<?php

namespace Usecase;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Model\SiteQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class CreateSiteUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        SiteQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testUsecaseFailsIfASiteAlreadyExists()
    {
        // given
        ModelFactory::createSite(title: "Existing site", domain: "https://existing-site.com");
        $usecase = new CreateSiteUsecase();

        // when
        $exception = Helpers::runAndCatchException(fn() =>
            $usecase->execute(siteName: "New site", baseUrl: "https://new-site.com", contactEmail: "contact@new-site.com")
        );

        // then
        $this->assertInstanceOf(BusinessRuleException::class, $exception);
        $this->assertEquals("Un site est déjà configuré : Existing site", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function testUsecaseSucceeds()
    {
        // given
        $usecase = new CreateSiteUsecase();

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
