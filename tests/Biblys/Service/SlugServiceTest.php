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


namespace Biblys\Service;

use Biblys\Service\Slug\InvalidSlugException;
use Biblys\Service\Slug\SlugService;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

class SlugServiceTest extends TestCase
{
    /**
     * #slugify
     */

    public function testWithAsciiString()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Welcome to Biblys");

        // then
        $this->assertEquals("welcome-to-biblys", $slug);
    }

    public function testWithFrenchAccentuatedText()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Élémentaire, mon cher Watson.");

        // then
        $this->assertEquals("elementaire-mon-cher-watson", $slug);
    }

    public function testWithAmpersandCharacter()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Fruits & légumes");

        // then
        $this->assertEquals("fruits-et-legumes", $slug);
    }

    public function testWithPlusCharacter()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Fruits + légumes");

        // then
        $this->assertEquals("fruits-plus-legumes", $slug);
    }

    /**
     * @throws InvalidSlugException
     */
    public function testValidateArticleSlugSuccess()
    {
        // given
        $slugService = new SlugService();

        // then
        $this->expectNotToPerformAssertions();

        // when
        $slugService->validateArticleSlug("walter-jon-williams/la-peste-du-leopard-vert_numerique");
    }

    public function testValidateArticleSlugFailure()
    {
        // given
        $slugService = new SlugService();

        // then
        $this->expectException(InvalidSlugException::class);

        // when
        $slugService->validateArticleSlug("articles/搭建六合源码论坛【联系TG:bc3979】n搭建六合源码论坛【联系TG:bc3979】nj");
    }

    /**
     * #createCollectionSlug
     */

    /**
     * @throws PropelException
     */
    public function testCreateCollectionSlug()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "SLUG");
        $collection = ModelFactory::createCollection(
            publisher: $publisher,
            name: "Tales"
        );
        $slugService = new SlugService();

        // when
        $slug = $slugService->createForBookCollection(
            $collection->getName(),
            $publisher->getName(),
        );

        // then
        $this->assertEquals("slug-tales", $slug);
    }

    /**
     * @throws PropelException
     */
    public function testCreateCollectionSlugForCollectionEqualingPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "SLUG");
        $collection = ModelFactory::createCollection(
            publisher: $publisher,
            name: "Slug"
        );
        $slugService = new SlugService();

        // when
        $slug = $slugService->createForBookCollection(
            $collection->getName(),
            $publisher->getName(),
        );

        // then
        $this->assertEquals("slug", $slug);
    }

    /**
     * @throws PropelException
     */
    public function testCreateCollectionSlugForCollectionWithAccentEqualingPublisherWithout()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "PHENOMENE");
        $collection = ModelFactory::createCollection(
            publisher: $publisher,
            name: "Phénomène"
        );
        $slugService = new SlugService();

        // when
        $slug = $slugService->createForBookCollection(
            $collection->getName(),
            $publisher->getName(),
        );

        // then
        $this->assertEquals("phenomene", $slug);
    }

    /**
     * @throws PropelException
     */
    public function testCreateCollectionSlugForCollectionIncludingPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "POLIO");
        $collection = ModelFactory::createCollection(
            publisher: $publisher,
            name: "Polio Folicier"
        );
        $slugService = new SlugService();

        // when
        $slug = $slugService->createForBookCollection(
            $collection->getName(),
            $publisher->getName(),
        );

        // then
        $this->assertEquals("polio-folicier", $slug);
    }
}
