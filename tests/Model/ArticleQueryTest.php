<?php

namespace Model;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ArticleQueryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testFilterForSiteReturnsEverythingWithoutFilter()
    {
        // given
        $sitePublisher = ModelFactory::createPublisher();
        $articleForSitePublisher = ModelFactory::createArticle([], $sitePublisher);
        $otherPublisher = ModelFactory::createPublisher();
        $articleForOtherPublisher = ModelFactory::createArticle([], $otherPublisher);

        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        // when
        $articles = ArticleQuery::create()->filterForCurrentSite($currentSite)->find();

        // then
        $this->assertContains($articleForSitePublisher, $articles);
        $this->assertContains($articleForOtherPublisher, $articles);
    }

    /**
     * @throws PropelException
     */
    public function testFilterForSiteReturnsOnlyAllowedPublishers()
    {
        // given
        $sitePublisher = ModelFactory::createPublisher();
        $articleForSitePublisher = ModelFactory::createArticle([], $sitePublisher);
        $siteOtherPublisher = ModelFactory::createPublisher();
        $articleForOtherSitePublisher = ModelFactory::createArticle([], $siteOtherPublisher);
        $publisherFromOtherSite = ModelFactory::createPublisher();
        $articleForOtherPublisher = ModelFactory::createArticle([], $publisherFromOtherSite);

        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $authorizedPublishers = [$sitePublisher->getId(), $siteOtherPublisher->getId()];
        $currentSite->setOption("publisher_filter", join(",", $authorizedPublishers));

        // when
        $articles = ArticleQuery::create()->filterForCurrentSite($currentSite)->find();

        // then
        $this->assertContains($articleForSitePublisher, $articles);
        $this->assertContains($articleForOtherSitePublisher, $articles);
        $this->assertNotContains($articleForOtherPublisher, $articles);
    }
}
