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
}
