<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class PostTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        global $site;

        $pm = new PostManager();

        $post = $pm->create();

        $this->assertInstanceOf('Post', $post);
        $this->assertEquals($post->get('site_id'), $site->get('id'));

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
        $this->assertTrue($post->hasIllustration());
    }

    /**
     * Test illustration exists
     * @depends testGet
     */
    public function testGetIllustrationTag(Post $post)
    {
        $pm = new PostManager();
        $post = $pm->create(["post_illustration_legend" => "Une belle image"]);
        $illustration = $post->getIllustration();
        $tag = $post->getIllustrationTag();

        $this->assertRegExp('/<img src="\/media\/post\/\d+\/\d+\.jpg" alt="Une belle image" class="illustration">/', $tag);
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

        $this->assertFalse($imageUrl2);
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

        $this->assertFalse($postWithoutPublisher->getPublisher());

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

        $article = $am->create();
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
     */
    public function testCanBeDeletedBy()
    {
        $pm = new PostManager();
        $um = new UserManager();

        $user = $um->create([
            "id" => 927,
            'user_email' => 'user@example.com'
        ]);
        $post = $pm->create();

        $this->assertFalse(
            $post->canBeDeletedBy($user),
            "User should not be able to delete any post"
        );

        $post->set('user_id', $user->get('id'));
        $this->assertTrue(
            $post->canBeDeletedBy($user),
            "Post author should be able to delete it"
        );
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
