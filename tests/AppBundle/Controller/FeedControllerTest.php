<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Test\ModelFactory;
use DateTime;
use Model\PostQuery;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../setUp.php";

class FeedControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        PostQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testBlogActionWithoutPosts()
    {
        // given
        $controller = new FeedController();
        $site = ModelFactory::createSite();

        $currentSite = new CurrentSite($site);
        $currentUrl = $this->createMock(CurrentUrlService::class);
        $currentUrl->method("getAbsoluteUrl")->willReturn("https://example.com/feed");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("https://example.com/post/1");

        // when
        $response = $controller->blogAction($currentSite, $currentUrl, $urlGenerator);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Éditions Paronymie</title>
    <description>Les derniers billets du blog</description>
    <generator>Laminas_Feed_Writer 2 (https://getlaminas.org)</generator>
    <link>https://paronymie.fr</link>
    <atom:link rel="self" type="application/rss+xml" href="https://example.com/feed"/>
  </channel>
</rss>

XML
            , $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testBlogActionWithPosts()
    {
        // given
        $controller = new FeedController();
        $site = ModelFactory::createSite();

        ModelFactory::createPost(date: new DateTime("2019-04-28 02:42:00"));
        ModelFactory::createPost(status: 0);

        $currentSite = new CurrentSite($site);
        $currentUrl = $this->createMock(CurrentUrlService::class);
        $currentUrl->method("getAbsoluteUrl")->willReturn("https://example.com/feed");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("https://example.com/post/1");

        // when
        $response = $controller->blogAction($currentSite, $currentUrl, $urlGenerator);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
  <channel>
    <title>Éditions Paronymie</title>
    <description>Les derniers billets du blog</description>
    <pubDate>Sun, 28 Apr 2019 02:42:00 +0000</pubDate>
    <generator>Laminas_Feed_Writer 2 (https://getlaminas.org)</generator>
    <link>https://paronymie.fr</link>
    <atom:link rel="self" type="application/rss+xml" href="https://example.com/feed"/>
    <item>
      <title>Une actualité</title>
      <pubDate>Sun, 28 Apr 2019 02:42:00 +0000</pubDate>
      <link>https://example.com/post/1</link>
      <guid>https://example.com/post/1</guid>
      <content:encoded><![CDATA[Un contenu d'actualité qui va vous étonner.]]></content:encoded>
      <slash:comments>0</slash:comments>
    </item>
  </channel>
</rss>

XML
            , $response->getContent());
    }
}
