<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class ArticleTest extends PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $this->m = new ArticleManager();
    }

    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $article = $this->m->create();

        $this->assertInstanceOf('Article', $article);

        return $article;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Article $article)
    {
        $gotArticle = $this->m->getById($article->get('id'));

        $this->assertInstanceOf('Article', $article);
        $this->assertEquals($article->get('id'), $gotArticle->get('id'));

        return $article;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Article $article)
    {
        // given
        $article->set("article_title", "Bara Yogoi");
        $article->set("publisher_id", 262);
        $pm = new PeopleManager();
        $author = $pm->create(["people_first_name" => "Léo", "people_last_name" => "Henry"]);
        $article->addContributor($author, 1);
        $this->m->update($article);

        // when
        $updatedArticle = $this->m->getById($article->get("id"));

        // then
        $this->assertTrue($updatedArticle->has("updated"));
        $this->assertEquals($updatedArticle->get("title"), "Bara Yogoi");
        $this->assertEquals($updatedArticle->get("publisher_id"), 262);

        return $updatedArticle;
    }

    /**
     * Test keywords & links generation
     * @depends testUpdate
     */
    public function testRefreshMetadata(Article $article)
    {
        $this->m->refreshMetadata($article);
        $this->assertEquals(
            "Bara Yogoi      Léo HENRY",
            $article->get("keywords"),
        );

        $articleWithoutJoins = $this->m->create([
            "article_collection" => "Présence du futur"
        ]);
        $this->m->refreshMetadata($articleWithoutJoins);
        $this->assertEquals(
            "     Présence du futur ",
            $articleWithoutJoins->get("keywords"),
        );
    }

    /**
     * Test on order
     * @depends testUpdate
     */
    public function testOnOrder(Article $article)
    {
        global $site;
        $siteId = $site->get("id");

        // Create a fake publisher for article
        $pm = new PublisherManager();
        $publisher = $pm->create([
            'publisher_name' => 'Publitou' . rand(1, 10000),
            'publisher_url' => 'publitou' . rand(1, 10000)
        ]);
        $article->set('publisher_id', $publisher->get('id'));

        // Create a fake on order supplier
        $sm = new SupplierManager();
        $supplier = $sm->create(['supplier_on_order' => 1]);

        // Associate publisher with supplier
        $lm = new LinkManager();
        $lm->create([
            'site_id' => $siteId,
            'publisher_id' => $publisher->get('id'),
            'supplier_id' => $supplier->get('id')
        ]);

        // Refresh metadata
        $article = $this->m->refreshMetadata($article);

        $this->assertStringContainsString(
            "[onorder:$siteId]",
            $article->get('links')
        );
    }

    /**
     * Test generating cover tag
     */
    public function testGetCoverTag()
    {
        $am = new ArticleManager();
        $article = $am->create(['article_title' => 'Bara Yogoi']);

        $tag = $article->getCoverTag(["class" => "aClass", "rel" => "aRel", "size" => "w250"]);
        $tagWithWidth = $article->getCoverTag(["class" => "aClass", "rel" => "aRel", "width" => 250]);
        $tagWithHeight = $article->getCoverTag(["class" => "aClass", "rel" => "aRel", "height" => 85]);
        $tagWithoutLink = $article->getCoverTag(['link' => false]);

        $this->assertRegExp(
            "/<a href=\"\/media\/book\/\d+\/\d+\.jpg\" rel=\"aRel\"><img src=\"\/media\/book\/\d+\/\d+.jpg\" class=\"aClass\" alt=\"Bara Yogoi\"><\/a>/",
            $tag
        );
        $this->assertRegExp(
            "/<a href=\"\/media\/book\/\d+\/\d+\.jpg\" rel=\"aRel\"><img src=\"\/media\/book\/\d+\/\d+.jpg\" class=\"aClass\" alt=\"Bara Yogoi\" width=\"250\"><\/a>/",
            $tagWithWidth
        );
        $this->assertRegExp(
            "/<a href=\"\/media\/book\/\d+\/\d+\.jpg\" rel=\"aRel\"><img src=\"\/media\/book\/\d+\/\d+.jpg\" class=\"aClass\" alt=\"Bara Yogoi\" height=\"85\"><\/a>/",
            $tagWithHeight
        );
        $this->assertRegExp(
            "/<img src=\"\/media\/book\/\d+\/\d+\.jpg\" alt=\"Bara Yogoi\">/",
            $tagWithoutLink
        );
    }

    /**
     * Test generating cover url
     * @depends testUpdate
     */
    public function testGetCoverUrl(Article $article)
    {
        $url = $article->getCoverUrl();

        $this->assertRegExp("/\/media\/book\/\d+\/\d+\.jpg/", $url);
    }

    /**
     * Test if article is available
     * @depends testUpdate
     */
    public function testIsAvailable(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isAvailable());

        $article->set('article_availability_dilicom', 1);
        $this->assertTrue($article->isAvailable());
    }

    /**
     * Test if article is available
     * @depends testUpdate
     */
    public function testIsComingSoon(Article $article)
    {
        $article->set('article_availability_dilicom', 1);
        $this->assertFalse($article->isComingSoon());

        $article->set('article_availability_dilicom', 2);
        $this->assertTrue($article->isComingSoon());

        $article->set('article_availability_dilicom', 1);
        $article->set('article_pubdate', (new DateTime('tomorrow'))->format("Y-m-d H:i:s"));
        $this->assertTrue($article->isComingSoon());
    }

    /**
     * Test if article is avaiable
     * @depends testUpdate
     */
    public function testIsToBeReprinted(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isToBeReprinted());

        $article->set('article_availability_dilicom', 3);
        $this->assertTrue($article->isToBeReprinted());
    }

    /**
     * Test if article is sold out
     * @depends testUpdate
     */
    public function testIsSoldout(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isSoldOut());

        $article->set('article_availability_dilicom', 6);
        $this->assertTrue($article->isSoldOut());
    }

    /**
     * Test if article is available
     * @depends testUpdate
     */
    public function testIsSoonUnavailable(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isSoonUnavailable());

        $article->set('article_availability_dilicom', 9);
        $this->assertTrue($article->isSoonUnavailable());
    }

    /**
     * Test if article is available
     * @depends testUpdate
     */
    public function testIsPrivatelyPrinted(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isPrivatelyPrinted());

        $article->set('article_availability_dilicom', 10);
        $this->assertTrue($article->isPrivatelyPrinted());
    }

    /**
     * Test if article is purchasable
     * @depends testUpdate
     */
    public function testIsPurchasable(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isPurchasable());

        $article->set('article_availability_dilicom', 1);
        $article->set('article_pubdate', (new DateTime())->format('Y-m-d H:i:s'));
        $this->assertTrue($article->isPurchasable());
    }

    /**
     * Test if article is purchasable
     * @depends testUpdate
     */
    public function testGetAvailabilityLed(Article $article)
    {
        $article->set('article_availability_dilicom', 0);
        $this->assertEquals($article->getAvailabilityLed(),
            '<img src="/common/img/square_red.png" title="Épuisé" alt="Épuisé">');

        $article->set('article_availability_dilicom', 1);
        $article->set('article_pubdate', (new DateTime())->format('Y-m-d H:i:s'));
        $this->assertEquals($article->getAvailabilityLed(),
            '<img src="/common/img/square_green.png" title="Disponible" alt="Disponible">');
    }

    /**
     * Test if article is published
     * @depends testUpdate
     */
    public function testIsPublished(Article $article)
    {
        $yesterday = new DateTime('yesterday');
        $tomorrow = new DateTime('tomorrow');

        $article->set('article_pubdate', $yesterday->format('Y-m-d H:i:s'));
        $this->assertTrue($article->isPublished());

        $article->set('article_pubdate', $tomorrow->format('Y-m-d H:i:s'));
        $this->assertFalse($article->isPublished());
    }

    /**
     * Test if article can be preordered
     * @depends testUpdate
     */
    public function testIsPreorderable(Article $article)
    {
        $this->assertFalse($article->isPreorderable());

        $article->set('article_preorder', 1);
        $this->assertTrue($article->isPreorderable());
    }

    /**
     * Test if article is of physical type
     * @depends testUpdate
     */
    public function testIsPhysical(Article $article)
    {
        $article->set('type_id', 2);
        $this->assertFalse($article->isPhysical());

        $article->set('type_id', 1);
        $this->assertTrue($article->isPhysical());
    }

    /**
     * Test if article is downloadable (non-physical)
     * @depends testUpdate
     */
    public function testIsDownloadable(Article $article)
    {
        $article->set('type_id', 1);
        $this->assertFalse($article->isDownloadable());

        $article->set('type_id', 2);
        $this->assertTrue($article->isDownloadable());
    }

    /**
     * Test if article is downloadable (non-physical)
     * @depends testUpdate
     */
    public function testIsFree(Article $article)
    {
        $article->set('article_price', 0);
        $this->assertTrue($article->isFree());

        $article->set('article_price', 999);
        $this->assertFalse($article->isFree());
    }

    /**
     * Test adding contributor to article
     */
    public function testAddContributor()
    {
        $am = new ArticleManager();
        $rm = new RoleManager();
        $pm = new PeopleManager();

        $article = $am->create([]);

        $people = $pm->create(["people_first_name" => "Luke", "people_last_name" => "Skywalker"]);
        $article->addContributor($people, 1);

        $role = $rm->get(['article_id' => $article->get('id'), 'people_id' => $people->get('id'), 'job_id' => 1]);
        $this->assertInstanceOf('Role', $role);

        $pm->delete($people);
    }

    public function testAddContributorWrongJob()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cannot add contributor with invalid job 25");

        // given
        $am = new ArticleManager();
        $pm = new PeopleManager();

        $article = $am->create([]);
        $people = $pm->create(["people_first_name" => "Luke", "people_last_name" => "Skywalker"]);
        $article->addContributor($people, 25);

        $pm->delete($people);
        $am->delete($article);
    }

    /**
    * Test getting article contributors
    * @depends testUpdate
    */
    public function testGetContributors(Article $article)
    {
        $article = $this->m->create([]);
        $this->assertEmpty($article->getContributors());
        $this->assertFalse($article->hasOtherContributors());

        $pm = new PeopleManager();
        $rm = new RoleManager();
        $am = new ArticleManager();

        $people1 = $pm->create(["people_first_name" => "Han", "people_last_name" => "Solo"]);
        $people2 = $pm->create(["people_first_name" => "Leia", "people_last_name" => "Organa"]);

        $article->addContributor($people1, 1);
        $article->addContributor($people2, 2);

        $contributors = $article->getContributors();
        $this->assertEquals(
            2,
            count($contributors),
            "it should return 2 contributors",
        );
        $this->assertEquals($contributors[0]->get('id'), $people1->get('id'));
        $this->assertEquals($contributors[1]->get('id'), $people2->get('id'));

        $authors = $article->getAuthors();
        $this->assertEquals($authors[0]->get('id'), $people1->get('id'));
        $this->assertEquals($authors[0]->get('job_id'), 1);

        $otherContributors = $article->getOtherContributors();
        $this->assertEquals($otherContributors[0]->get('id'), $people2->get('id'));
        $this->assertEquals($otherContributors[0]->get('job_id'), 2);

        $pm->delete($people1);
        $pm->delete($people2);
    }

    /** Test getting article contributors
    * @depends testUpdate
    */
    public function testGetDownloadableFiles(Article $article)
    {
        $this->assertFalse($article->hasDownloadableFiles());
        $this->assertEmpty($article->getDownloadableFiles());

        $fm = new FileManager();
        $am = new ArticleManager();

        $paid = $fm->create(["file_access" => 1, "article_id" => $article->get('id')]);
        $free = $fm->create(["file_access" => 0, "article_id" => $article->get('id')]);

        $article = $am->getById($article->get('id'));

        $files = $article->getDownloadableFiles();
        $this->assertEquals($files[0]->get('id'), $paid->get('id'));
        $this->assertEquals($files[1]->get('id'), $free->get('id'));

        $paids = $article->getDownloadableFiles('paid');
        $this->assertEquals($paids[0]->get('id'), $paid->get('id'));
        $this->assertEquals($paids[0]->get('access'), 1);

        $frees = $article->getDownloadableFiles('free');
        $this->assertEquals($frees[0]->get('id'), $free->get('id'));
        $this->assertEquals($frees[0]->get('access'), 0);
    }

    /**
     * Test getting available copies for article
     * @return [type] [description]
     */
    public function testGetAvailableCopies()
    {
        $am = new ArticleManager();
        $sm = new StockManager();

        $article = $am->create();
        $new = $sm->create([
            "article_id" => $article->get('id'),
            "stock_condition" => "Neuf"
        ]);
        $used = $sm->create([
            "article_id" => $article->get('id'),
            "stock_condition" => "Mauvais"
        ]);

        $copies = $article->getAvailableItems();
        $new_copies = $article->getAvailableItems('new');
        $used_copies = $article->getAvailableItems('used');

        $this->assertEquals($new->get('id'), $copies[0]->get('id'));
        $this->assertEquals($new->get('id'), $new_copies[0]->get('id'));
        $this->assertEquals($used->get('id'), $used_copies[0]->get('id'));
    }

    /**
     * Test getting available copies for article
     * @return [type] [description]
     */
    public function testGetCheapestAvailable()
    {
        $am = new ArticleManager();
        $sm = new StockManager();

        $article = $am->create();
        $expensive = $sm->create([
            "article_id" => $article->get('id'),
            "stock_condition" => "Neuf",
            "stock_selling_price" => "2500"
        ]);
        $cheapest = $sm->create([
            "article_id" => $article->get('id'),
            "stock_condition" => "Mauvais",
            "stock_selling_price" => "1250"
        ]);

        $copy = $article->getCheapestAvailableItem();

        $this->assertEquals($cheapest->get('id'), $copy->get('id'));
    }

    /** Test getting related awards
    * @depends testGet
    */
    public function testGetAwards(Article $article)
    {
        $am = new AwardManager();

        $award = $am->create(['award_name' => 'Prix Goncourt', 'article_id' => $article->get('id')]);

        $awards = $article->getAwards();
        $gotAward = $awards[0];

        $this->assertEquals($award->get('name'), $gotAward->get('name'));

        $am->delete($award);
    }

    /**
     * Test setType and getType methods
     */
    public function testSetGetType()
    {
        $article = new Article([]);
        $type = Biblys\Article\Type::getById(1);

        $article->setType($type);
        $type = $article->getType();

        $this->assertEquals($type->getId(), 1);
    }

    /**
     * Test deleting a collection with links
     * @depends testGet
     */
    public function testBeforeDeleteWithLinks(Article $article)
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de supprimer cet article car des éléments y sont liés.");

        $am = new ArticleManager();
        $lm = new LinkManager();

        $lm->create(['article_id' => $article->get('id')]);

        $am->delete($article);
    }

    /**
     * Test deleting a collection with stock
     * @depends testGet
     */
    public function testBeforeDeleteWithStock(Article $article)
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de supprimer cet article car des exemplaires y sont associés.");

        $am = new ArticleManager();
        $sm = new StockManager();

        $sm->create(['article_id' => $article->get('id')]);

        $am->delete($article);
    }

    /**
     * Test setting a publisher
     * @depends testGet
     */
    public function testSetPublisher(Article $article)
    {
        $pm = new PublisherManager();
        $publisher = $pm->create(['publisher_name' => 'The Publisher']);

        $article->setPublisher($publisher);

        $this->assertEquals($article->get('publisher_id'), $publisher->get('id'));
        $this->assertEquals($article->get('article_publisher'), $publisher->get('name'));

        $pm->delete($publisher);
    }


    /**
     * Test setting a collection
     * @depends testGet
     */
    public function testSetCollection(Article $article)
    {
        $pm = new PublisherManager();
        $publisher = $pm->create(['publisher_name' => 'The Publisher']);

        $cm = new CollectionManager();
        $collection = $cm->create([
            'collection_name' => 'The Collection',
            'publisher_id' => $publisher->get('id')
        ]);

        $article->setCollection($collection);

        $this->assertEquals($article->get('collection_id'), $collection->get('id'));
        $this->assertEquals($article->get('article_collection'), $collection->get('name'));
        $this->assertEquals($article->get('publisher_id'), $publisher->get('id'));
        $this->assertEquals($article->get('article_publisher'), $publisher->get('name'));

        $cm->delete($collection);
        $pm->delete($publisher);
    }

    /** Test getting rayons as a JS array */
    public function testGetJsArray()
    {
        $rm = new RayonManager();
        $pm = new PeopleManager();
        $article = $this->m->create(["article_title" => "A Book"]);
        $people = $pm->create(["people_last_name" => "An author"]);
        $article->addContributor($people, 1);

        $rayon1 = $rm->create(["rayon_name" => "Rayon 1"]);
        $rayon2 = $rm->create(["rayon_name" => "Rayon 2"]);
        $rayon3 = $rm->create(["rayon_name" => "Rayon 3"]);
        $rayon4 = $rm->create(["rayon_name" => "Rayon 4"]);
        $rayon5 = $rm->create(["rayon_name" => "Rayon 5"]);
        $rayon6 = $rm->create(["rayon_name" => "Rayon 6"]);

        $this->m->addRayon($article, $rayon1);
        $this->m->addRayon($article, $rayon2);
        $this->m->addRayon($article, $rayon3);
        $this->m->addRayon($article, $rayon4);
        $this->m->addRayon($article, $rayon5);
        $this->m->addRayon($article, $rayon6);

        $this->assertEquals(
            $article->getRayonsAsJsArray(),
            '["Rayon 1","Rayon 2","Rayon 3","Rayon 4","Rayon 5"]'
        );

        $rm->delete($rayon1);
        $rm->delete($rayon2);
        $rm->delete($rayon3);
        $rm->delete($rayon4);
        $rm->delete($rayon5);
        $rm->delete($rayon6);
    }

    /**
     * Test that adding a too long string as article_authors does not validate
     */
    public function testValidateArticleAuthorsLength()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Le champ Auteurs ne peut pas dépasser 256 caractères.");

        $am = new ArticleManager();
        $article = new Article(['url' => 'article/url']);
        $article->set(
            'article_authors',
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam
            aliquet arcu at libero maximus, euismod vehicula justo suscipit.
            Praesent faucibus porta porta. Integer id congue lorem. Nulla
            convallis sagittis ultricies. Fusce molestie nibh quis tellus
            iaculis dapibus. Aenean vitae velit sed nulla."
        );

        $am->validate($article);
    }

    /**
     * Test that updating an article without an url throws
     */
    public function testUpdatingArticleWithoutUrl()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("L'article doit avoir une url");

        $am = new ArticleManager();
        $article = $am->create([]);

        $am->update($article);
    }

    public function testPreprocessSlugWithOneAuthor()
    {
        // given
        $am = new ArticleManager();
        $article = $am->create(["article_title" => "Légumes du jour"]);
        $pm = new PeopleManager();
        $people = $pm->create([
            "people_first_name" => "Jean-Sol",
            "people_last_name" => "Partre"
        ]);
        $article->addContributor($people, 1);

        // when
        $article = $am->preprocess($article);

        // then
        $this->assertEquals(
            "jean-sol-partre/legumes-du-jour",
            $article->get('url'),
            "it should generate correct slug for one author"
        );
    }

    public function testPreprocessSlugWithSeveralAuthors()
    {
        // given
        $am = new ArticleManager();
        $article = $am->create(["article_title" => "La Bande à Picsou"]);
        $pm = new PeopleManager();
        $riri = $pm->create(["people_last_name" => "Riri"]);
        $fifi = $pm->create(["people_last_name" => "Fifi"]);
        $loulou = $pm->create(["people_last_name" => "Loulou"]);
        $article->addContributor($riri, 1);
        $article->addContributor($fifi, 1);
        $article->addContributor($loulou, 1);

        // when
        $article = $am->preprocess($article);

        // then
        $this->assertEquals(
            "collectif/la-bande-a-picsou",
            $article->get('url'),
            "it should generate correct slug for several authors"
        );
    }


    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Article $article)
    {
        $am = new ArticleManager();
        $lm = new LinkManager();
        $sm = new StockManager();

        $link = $lm->get(['article_id' => $article->get('id')]);
        $lm->delete($link);

        $stock = $sm->get(['article_id' => $article->get('id')]);
        $sm->delete($stock);

        $am->delete($article, 'Test entity');

        $articleExists = $am->getById($article->get('id'));

        $this->assertFalse($articleExists);
    }
}
