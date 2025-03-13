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


namespace Model;

use Biblys\Test\ModelFactory;
use DateTime;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class PostTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PostQuery::create()->deleteAll();
    }

    /** isPublished */

    public function testIsPublishedReturnsFalseByDefault()
    {
        // given
        $post = new Post();

        // when
        $isOnline = $post->isPublished();

        // then
        $this->assertFalse($isOnline);
    }

    public function testIsPublishedReturnsFalseIfStatusIsO()
    {
        // given
        $post = new Post();
        $post->setStatus(0);

        // when
        $isOnline = $post->isPublished();

        // then
        $this->assertFalse($isOnline);
    }

    public function testIsPublishedReturnsTrueIfStatusIs1()
    {
        // given
        $post = new Post();
        $post->setStatus(1);

        // when
        $isOnline = $post->isPublished();

        // then
        $this->assertTrue($isOnline);
    }

    /** getNextPost */

    /**
     * @throws PropelException
     */
    public function testGetNextPostReturnsNextPost()
    {
        // given
        $previousPost = ModelFactory::createPost(date: new DateTime("2025-01-01"));
        $previousPostButUnpublished = ModelFactory::createPost(status: Post::STATUS_OFFLINE, date: new DateTime("2025-01-02"));
        $post = ModelFactory::createPost(date: new DateTime("2025-01-03"));
        $nextPostButUnpublished = ModelFactory::createPost(status: Post::STATUS_OFFLINE, date: new DateTime("2025-01-04"));
        $nextPost = ModelFactory::createPost(date: new DateTime("2025-01-05"));

        // when
        $returnedPost = $post->getNextPost();

        // then
        $this->assertNotEquals($previousPost, $returnedPost);
        $this->assertNotEquals($previousPostButUnpublished, $returnedPost);
        $this->assertNotEquals($post, $returnedPost);
        $this->assertNotEquals($nextPostButUnpublished, $returnedPost);
        $this->assertEquals($nextPost, $returnedPost);
    }

    /** getPreviousPost */

    /**
     * @throws PropelException
     */
    public function testGetPreviousPostReturnsPreviousPost()
    {
        // given
        $previousPost = ModelFactory::createPost(date: new DateTime("2025-01-01"));
        $previousPostButUnpublished = ModelFactory::createPost(status: Post::STATUS_OFFLINE, date: new DateTime("2025-01-02"));
        $post = ModelFactory::createPost(date: new DateTime("2025-01-03"));
        $nextPostButUnpublished = ModelFactory::createPost(status: Post::STATUS_OFFLINE, date: new DateTime("2025-01-04"));
        $nextPost = ModelFactory::createPost(date: new DateTime("2025-01-05"));

        // when
        $returnedPost = $post->getPreviousPost();

        // then
        $this->assertNotEquals($nextPost, $returnedPost);
        $this->assertNotEquals($nextPostButUnpublished, $returnedPost);
        $this->assertNotEquals($post, $returnedPost);
        $this->assertNotEquals($previousPostButUnpublished, $returnedPost);
        $this->assertEquals($previousPost, $returnedPost);
    }

}
