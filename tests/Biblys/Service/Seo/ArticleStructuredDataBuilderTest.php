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


namespace Biblys\Service\Seo;

use ArticleManager;
use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../../setUp.php";

class ArticleStructuredDataBuilderTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testBuildReturnsProductFieldsForABook()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Éditions Paronymie");
        $propelArticle = ModelFactory::createArticle(
            title: "Citoyens de demain",
            typeId: ArticleType::BOOK,
            publisher: $publisher,
            summary: "<p>Un roman <strong>essentiel</strong>.</p>",
        );
        $article = (new ArticleManager())->getById($propelArticle->getId());
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder();

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("https://schema.org", $data["@context"]);
        $this->assertEquals("Citoyens de demain", $data["name"]);
        $this->assertEquals("Un roman essentiel.", $data["description"]);
        $this->assertEquals("Éditions Paronymie", $data["brand"]["name"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildReturnsEmptyArrayForSubscription()
    {
        // given
        $propelArticle = ModelFactory::createArticle(typeId: ArticleType::SUBSCRIPTION);
        $article = (new ArticleManager())->getById($propelArticle->getId());
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder();

        // when
        $data = $builder->build($article, null, $currentSite);

        // then
        $this->assertEquals([], $data);
    }
}
