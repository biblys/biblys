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

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class ImageQueryTest extends TestCase
{
    /** filterByModel */

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithArticle(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);

        // when
        $image = ImageQuery::create()->filterByModel($article)->findOne();

        // then
        $this->assertEquals($image->getArticle(), $article);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithStockItem(): void
    {
        // given
        $stockItem = ModelFactory::createStockItem();
        ModelFactory::createImage(stockItem: $stockItem);

        // when
        $image = ImageQuery::create()->filterByModel($stockItem)->findOne();

        // then
        $this->assertEquals($image->getStockItem(), $stockItem);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithPost(): void
    {
        // given
        $post = ModelFactory::createPost();
        ModelFactory::createImage(post: $post);

        // when
        $image = ImageQuery::create()->filterByModel($post)->findOne();

        // then
        $this->assertEquals($image->getPost(), $post);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithPublisher(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        ModelFactory::createImage(publisher: $publisher);

        // when
        $image = ImageQuery::create()->filterByModel($publisher)->findOne();

        // then
        $this->assertEquals($image->getPublisher(), $publisher);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithContributor(): void
    {
        // given
        $contributor = ModelFactory::createContributor();
        ModelFactory::createImage(contributor: $contributor);

        // when
        $image = ImageQuery::create()->filterByModel($contributor)->findOne();

        // then
        $this->assertEquals($image->getContributor(), $contributor);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent($site);
        ModelFactory::createImage(event: $event);

        // when
        $image = ImageQuery::create()->filterByModel($event)->findOne();

        // then
        $this->assertEquals($image->getEvent(), $event);
    }

    /** filterByModelId */

    /**
     * @throws PropelException
     */
    public function testFilterByModelId(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);

        // when
        $image = ImageQuery::create()->filterByModelId(Article::class, $article->getId())->findOne();

        // then
        $this->assertEquals($image->getArticle(), $article);
    }
}
