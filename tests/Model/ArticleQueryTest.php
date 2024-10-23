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
        $articleForSitePublisher = ModelFactory::createArticle(publisher: $sitePublisher);
        $otherPublisher = ModelFactory::createPublisher();
        $articleForOtherPublisher = ModelFactory::createArticle(publisher: $otherPublisher);

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
        $articleForSitePublisher = ModelFactory::createArticle(publisher: $sitePublisher);
        $siteOtherPublisher = ModelFactory::createPublisher();
        $articleForOtherSitePublisher = ModelFactory::createArticle(publisher: $siteOtherPublisher);
        $publisherFromOtherSite = ModelFactory::createPublisher();
        $articleForOtherPublisher = ModelFactory::createArticle(publisher: $publisherFromOtherSite);

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
