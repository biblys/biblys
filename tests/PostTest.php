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

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Propel\Runtime\Exception\PropelException;

require_once "setUp.php";

class PostTest extends PHPUnit\Framework\TestCase
{
    /**
     * @throws Exception
     */
    public function testGetModel(): void
    {
        // given
        $entity = new Post(["id" => 1234]);

        // when
        $model = $entity->getModel();

        // then
        $this->assertInstanceOf(\Model\Post::class, $model);
        $this->assertEquals(1234, $model->getId());
    }

    /**
     * Test creating a post
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $pm = new PostManager();

        $post = $pm->create();

        $this->assertInstanceOf('Post', $post);

        return $post;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(Post $post)
    {
        $pm = new PostManager();

        $gotPost = $pm->getById($post->get('id'));

        $this->assertInstanceOf('Post', $post);
        $this->assertEquals($post->get('id'), $gotPost->get('id'));

        return $post;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(Post $post)
    {
        $pm = new PostManager();

        $post->set('post_title', 'Une bonne nouvelle');
        $pm->update($post);

        $updatedPost = $pm->getById($post->get('id'));

        $this->assertTrue($updatedPost->has('updated'));
        $this->assertEquals($updatedPost->get('title'), 'Une bonne nouvelle');

        return $updatedPost;
    }

    /**
     * Test getting illustration
     * @depends testGet
     */
    public function testGetIllustration(Post $post)
    {
        $this->assertInstanceOf('Media', $post->getIllustration());
    }

    /**
     * Test illustration exists
     * @depends testGet
     */
    public function testHasIllustration(Post $post)
    {
        $post->getIllustration()->setExists(true);
        $this->assertTrue($post->hasIllustration());
    }

    /**
     * Test illustration exists
     * @depends testGet
     */
    public function testGetIllustrationTag()
    {
        $pm = new PostManager();
        $post = $pm->create(["post_illustration_legend" => "Une belle image"]);
        $tag = $post->getIllustrationTag();

        $this->assertMatchesRegularExpression('/<img src="\/images\/\/post\/\d+\/\d+\.jpg" alt="Une belle image" class="illustration">/', $tag);
    }

    public function testGetIllustrationUrl()
    {
        // given
        $post = new Post(["post_id" => 1]);

        // when
        $url = $post->getIllustrationUrl();

        // then
        $this->assertEquals("/images//post/01/1.jpg", $url);
    }

    public function testGetIllustrationUrlWithVersion()
    {
        // given
        $post = new Post(["post_id" => 1, "post_illustration_version" => 3]);

        // when
        $url = $post->getIllustrationUrl();

        // then
        $this->assertEquals("/images//post/01/1.jpg?v=3", $url);
    }

    /**
     * Test illustration exists
     * @depends testGet
     */
    public function testGetIllustrationTagWithHeight()
    {
        $pm = new PostManager();
        $post = $pm->create(["post_illustration_legend" => "Une belle image"]);
        $tag = $post->getIllustrationTag(height: 60);

        $this->assertMatchesRegularExpression('/<img src="\/images\/\/post\/\d+\/\d+\.jpg" alt="Une belle image" height=60 class="illustration">/', $tag);
    }

    /**
     * Test getting first image url
     */
    public function testGetFirstImageUrl()
    {
        $pm = new PostManager();
        $post = $pm->create(["post_content" => '
            <p>Hello!</p>
            <img src="http://domain.com/image1.jpg">
            <img src="http://domain.com/image2.jpg">
        ']);
        $imageUrl = $post->getFirstImageUrl();

        $postWithoutImage = $pm->create(["post_content" => '<p>Hello!</p>']);
        $imageUrl2 = $postWithoutImage->getFirstImageUrl();

        $this->assertNull($imageUrl2);
        $this->assertEquals($imageUrl, "http://domain.com/image1.jpg", "should return first post image url");
    }

    /**
     * Test getting related publisher
     */
    public function testGetPublisher()
    {
        $pm = new PostManager();
        $pum = new PublisherManager();

        $postWithoutPublisher = $pm->create();

        $publisher = $pum->create(['publisher_name' => 'Éditeur']);
        $postWithPublisher = $pm->create(["publisher_id" => $publisher->get("id")]);

        $this->assertNull($postWithoutPublisher->getPublisher());

        $postPublisher = $postWithPublisher->getPublisher();
        $this->assertInstanceOf('Publisher', $postPublisher);
        $this->assertEquals($postPublisher->get('id'), $publisher->get('id'));

        $pum->delete($publisher);
    }

    /**
     * Test getting related articles
     */
    public function testGetArticles()
    {
        $pm = new PostManager();
        $am = new ArticleManager();
        $lm = new LinkManager();

        $postWithoutArticles = $pm->create();
        $postWithArticles = $pm->create();

        $article = EntityFactory::createArticle();
        $links = $lm->create([
            "article_id" => $article->get('id'),
            "post_id" => $postWithArticles->get('id')
        ]);

        $this->assertEmpty($postWithoutArticles->getArticles());
        $this->assertEquals(
            $article->get('id'),
            $postWithArticles->getArticles()[0]->get('id')
        );
    }


    /**
     * Test getting related people
     */
    public function testGetRelatedPeople()
    {
        $pm = new PostManager();
        $ppm = new PeopleManager();
        $lm = new LinkManager();

        $postWithoutPeople = $pm->create();
        $postWithPeople = $pm->create();

        $people = $ppm->create(['people_last_name' => 'plop']);
        $links = $lm->create([
            "people_id" => $people->get('id'),
            "post_id" => $postWithPeople->get('id')
        ]);

        $this->assertEmpty($postWithoutPeople->getRelatedPeople());
        $this->assertEquals(
            $people->get('id'),
            $postWithPeople->getRelatedPeople()[0]->get('id')
        );

        $ppm->delete($people);
    }


    /**
     * Test if user can delete post
     * @throws Exception
     */
    public function testPostCantBeDeletedByAnyone()
    {
        // given
        $user = ModelFactory::createUser();
        $post = new Post(["user_id" => 222]);

        // when
        $canBeDeleted = $post->canBeDeletedBy($user);

        // then
        $this->assertFalse($canBeDeleted, "User should not be able to delete any post");
    }

    /**
     * @throws PropelException
     */
    public function testPostCanBeDeletedByItsAuthor()
    {
        // given
        $postAuthor = ModelFactory::createUser();
        $post = new Post(["user_id" => $postAuthor->getId()]);

        // when
        $canBeDeleted = $post->canBeDeletedBy($postAuthor);

        // then
        $this->assertTrue($canBeDeleted, "Post author should be able to delete it");
    }

    /**
     * Test deleting a post
     * @depends testGet
     */
    public function testDelete(Post $post)
    {
        $pm = new PostManager();

        $pm->delete($post, 'Test entity');

        $postExists = $pm->getById($post->get('id'));

        $this->assertFalse($postExists);
    }
}
