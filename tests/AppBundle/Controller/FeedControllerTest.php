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
use Biblys\Service\Images\ImagesService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
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
        ArticleQuery::create()->deleteAll();
        BookCollectionQuery::create()->deleteAll();
    }

    /** index */

    /**
     * @throws PropelException
     * @throws \Exception
     */
    public function testIndex(): void
    {
        // given
        $controller = new FeedController();

        $site = ModelFactory::createSite(domain: "example.org");
        $currentSite = new CurrentSite($site);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentSite, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Les flux RSS de example.org", $response->getContent());
    }

    /** blog */

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
        $imagesService = $this->createMock(ImagesService::class);

        // when
        $response = $controller->blogAction($currentSite, $currentUrl, $urlGenerator, $imagesService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Éditions Paronymie · Le blog</title>
    <description>Les derniers billets du blog</description>
    <generator>Biblys (https://biblys.org)</generator>
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

        $post = ModelFactory::createPost(
            title: "Une actualité dans le flux",
            date: new DateTime("2019-04-28 02:42:00"),
            content: "<p>Un contenu d'actualité qui va vous étonner.</p>",
        );
        ModelFactory::createImage(post: $post, type: "illustration");
        ModelFactory::createPost(title: "Une actualité non publiée", status: 0);
        ModelFactory::createPost(title: "Une actualité du futur", date: new DateTime("+1 day"));

        $currentSite = new CurrentSite($site);
        $currentUrl = $this->createMock(CurrentUrlService::class);
        $currentUrl->method("getAbsoluteUrl")->willReturn("https://example.com/feed");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("https://example.com/post/1");
        $imagesService = $this->createMock(ImagesService::class);
        $imagesService->method("imageExistsFor")->willReturn(true);
        $imagesService->method("getImageUrlFor")->willReturn("/images/post/1.jpg");

        // when
        $response = $controller->blogAction($currentSite, $currentUrl, $urlGenerator, $imagesService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
  <channel>
    <title>Éditions Paronymie · Le blog</title>
    <description>Les derniers billets du blog</description>
    <pubDate>Sun, 28 Apr 2019 02:42:00 +0000</pubDate>
    <generator>Biblys (https://biblys.org)</generator>
    <link>https://paronymie.fr</link>
    <atom:link rel="self" type="application/rss+xml" href="https://example.com/feed"/>
    <item>
      <title>Une actualité dans le flux</title>
      <pubDate>Sun, 28 Apr 2019 02:42:00 +0000</pubDate>
      <link>https://example.com/post/1</link>
      <guid>https://example.com/post/1</guid>
      <content:encoded><![CDATA[<img src="/images/post/1.jpg" alt="" role="presentation" /><p>Un contenu d'actualité qui va vous étonner.</p>]]></content:encoded>
      <slash:comments>0</slash:comments>
    </item>
  </channel>
</rss>

XML
            , $response->getContent());
    }



    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testBlogActionWithEmptyPosts()
    {
        // given
        $controller = new FeedController();
        $site = ModelFactory::createSite();

        ModelFactory::createPost(date: new DateTime("2019-04-28 02:42:00"), content: "");

        $currentSite = new CurrentSite($site);
        $currentUrl = $this->createMock(CurrentUrlService::class);
        $currentUrl->method("getAbsoluteUrl")->willReturn("https://example.com/feed");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("https://example.com/post/1");
        $imagesService = $this->createMock(ImagesService::class);
        $imagesService->method("imageExistsFor")->willReturn(false);

        // when
        $response = $controller->blogAction($currentSite, $currentUrl, $urlGenerator, $imagesService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Éditions Paronymie · Le blog</title>
    <description>Les derniers billets du blog</description>
    <pubDate>Sun, 28 Apr 2019 02:42:00 +0000</pubDate>
    <generator>Biblys (https://biblys.org)</generator>
    <link>https://paronymie.fr</link>
    <atom:link rel="self" type="application/rss+xml" href="https://example.com/feed"/>
  </channel>
</rss>

XML
            , $response->getContent());
    }

    /** articles */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testArticlesActionWithoutArticles()
    {
        // given
        $controller = new FeedController();
        $site = ModelFactory::createSite();

        $currentSite = new CurrentSite($site);
        $currentUrl = $this->createMock(CurrentUrlService::class);
        $currentUrl->method("getAbsoluteUrl")->willReturn("https://example.com/feed");
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $response = $controller->catalogAction($currentSite, $currentUrl, $urlGenerator);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Éditions Paronymie · Les parutions</title>
    <description>Les derniers articles du catalogue</description>
    <generator>Biblys (https://biblys.org)</generator>
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
    public function testArticlesActionWithArticles()
    {
        // given
        $controller = new FeedController();
        $site = ModelFactory::createSite();

        ModelFactory::createArticle(
            title: "Un article dans le flux",
            publicationDate: new DateTime("2013-05-22 21:59:00"),
            summary: "<p>Ce livre paraît aujourd'hui.</p>",
        );
        ModelFactory::createArticle(
            title: "Un article sans quatrième",
            publicationDate: new DateTime("2013-05-22 21:58:00"),
            summary: ""
        );
        ModelFactory::createArticle(title: "Un article à paraître", publicationDate: new DateTime("+1 day"));

        $currentSite = new CurrentSite($site);
        $currentUrl = $this->createMock(CurrentUrlService::class);
        $currentUrl->method("getAbsoluteUrl")->willReturn("https://example.com/feed");
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("https://example.com/post/1");

        // when
        $response = $controller->catalogAction($currentSite, $currentUrl, $urlGenerator);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/rss+xml", $response->headers->get("Content-Type"));
        $this->assertEquals(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
  <channel>
    <title>Éditions Paronymie · Les parutions</title>
    <description>Les derniers articles du catalogue</description>
    <pubDate>Wed, 22 May 2013 21:59:00 +0000</pubDate>
    <generator>Biblys (https://biblys.org)</generator>
    <link>https://paronymie.fr</link>
    <atom:link rel="self" type="application/rss+xml" href="https://example.com/feed"/>
    <item>
      <title>Un article dans le flux</title>
      <pubDate>Wed, 22 May 2013 21:59:00 +0000</pubDate>
      <link>https://example.com/post/1</link>
      <guid>https://example.com/post/1</guid>
      <content:encoded><![CDATA[<p>Ce livre paraît aujourd'hui.</p>]]></content:encoded>
      <slash:comments>0</slash:comments>
    </item>
  </channel>
</rss>

XML
            , $response->getContent());
    }
}
