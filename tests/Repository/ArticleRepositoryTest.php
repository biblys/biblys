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

namespace Repository;

use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use Model\ArticleQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class ArticleRepositoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        ArticleQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testFindEbookVersionForReturnsMatchingEbook(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $paperback = ModelFactory::createArticle(item: 42, publisher: $publisher);
        $ebook = ModelFactory::createArticle(
            item: 42,
            url: "author/article-ebook",
            publisher: $publisher,
            typeId: ArticleType::EBOOK,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("ebook_publisher_id")->willReturn((string) $publisher->getId());
        $repository = new ArticleRepository();

        // when
        $result = $repository->findEbookVersionFor($paperback, $currentSite);

        // then
        $this->assertEquals($ebook->getId(), $result->getId());
    }

    /**
     * @throws PropelException
     */
    public function testFindEbookVersionForIgnoresNonEbookArticles(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $paperback = ModelFactory::createArticle(item: 42, publisher: $publisher);
        ModelFactory::createArticle(
            item: 42,
            url: "author/article-audiobook",
            publisher: $publisher,
            typeId: ArticleType::BOOK,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("ebook_publisher_id")->willReturn((string) $publisher->getId());
        $repository = new ArticleRepository();

        // when
        $result = $repository->findEbookVersionFor($paperback, $currentSite);

        // then
        $this->assertNull($result);
    }

    /**
     * @throws PropelException
     */
    public function testFindEbookVersionForReturnsNullWhenNoOptionConfigured(): void
    {
        // given
        $article = ModelFactory::createArticle(item: 42);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("ebook_publisher_id")->willReturn(null);
        $repository = new ArticleRepository();

        // when
        $result = $repository->findEbookVersionFor($article, $currentSite);

        // then
        $this->assertNull($result);
    }

    /**
     * @throws PropelException
     */
    public function testFindEbookVersionForReturnsNullWhenNoSharedItem(): void
    {
        // given
        $article = ModelFactory::createArticle(item: null);
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new ArticleRepository();

        // when
        $result = $repository->findEbookVersionFor($article, $currentSite);

        // then
        $this->assertNull($result);
    }

    /**
     * @throws PropelException
     */
    public function testResetSearchTerms(): void
    {
        // given
        $article = ModelFactory::createArticle(keywordsGeneratedAt: new DateTime());
        $otherArticle = ModelFactory::createArticle(url: "author/other", keywordsGeneratedAt: new DateTime());
        $repository = new ArticleRepository();

        // when
        $repository->resetSearchTerms();

        // then
        $this->assertNull(ArticleQuery::create()->findPk($article->getId())->getKeywordsGenerated());
        $this->assertNull(ArticleQuery::create()->findPk($otherArticle->getId())->getKeywordsGenerated());
    }

    /**
     * @throws PropelException
     */
    public function testSearchFindsArticlesMatchingAllWords(): void
    {
        // given
        $matching = ModelFactory::createArticle(title: "Le Cycle de la Pierre", keywords: "Le Cycle de la Pierre 9781234567897");
        ModelFactory::createArticle(url: "author/other", title: "Le Cycle du Feu", keywords: "Le Cycle du Feu");
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new ArticleRepository();

        // when
        $articles = $repository->search("cycle  pierre", $currentSite);

        // then
        $this->assertCount(1, $articles);
        $this->assertEquals($matching->getId(), $articles[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testSearchFindsArticlesByEan(): void
    {
        // given
        $article = ModelFactory::createArticle(keywords: "Titre 9781234567897");
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new ArticleRepository();

        // when
        $articles = $repository->search("9781234567897", $currentSite);

        // then
        $this->assertCount(1, $articles);
        $this->assertEquals($article->getId(), $articles[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testSearchReturnsNothingForAnEmptyTerm(): void
    {
        // given
        ModelFactory::createArticle();
        $currentSite = $this->createMock(CurrentSite::class);
        $repository = new ArticleRepository();

        // when
        $articles = $repository->search("   ", $currentSite);

        // then
        $this->assertSame([], $articles);
    }

    /**
     * @throws PropelException
     */
    public function testSearchRespectsPublisherAndCollectionFiltersOfTheSite(): void
    {
        // given
        $allowedPublisher = ModelFactory::createPublisher(name: "Autorisé", url: "autorise.fr");
        $otherPublisher = ModelFactory::createPublisher(name: "Autre", url: "autre.fr");
        $allowed = ModelFactory::createArticle(title: "Livre visible", publisher: $allowedPublisher);
        $hiddenCollection = ModelFactory::createCollection(publisher: $allowedPublisher, name: "Cachée");
        ModelFactory::createArticle(
            url: "author/hidden-collection",
            title: "Livre visible caché",
            publisher: $allowedPublisher,
            collection: $hiddenCollection,
        );
        ModelFactory::createArticle(url: "author/other", title: "Livre visible autre", publisher: $otherPublisher);

        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->willReturnMap([
            ["publisher_filter", null, (string) $allowedPublisher->getId()],
            ["collection_filter_hide", null, (string) $hiddenCollection->getId()],
        ]);
        $repository = new ArticleRepository();

        // when
        $articles = $repository->search("visible", $currentSite);

        // then
        $this->assertCount(1, $articles);
        $this->assertEquals($allowed->getId(), $articles[0]->getId());
    }
}
