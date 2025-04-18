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

use Biblys\Data\ArticleType;
use Biblys\Exception\ArticleAlreadyInRayonException;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\PeopleQuery;
use Model\PublisherQuery;
use Propel\Runtime\Exception\PropelException;

require_once "setUp.php";

class ArticleTest extends PHPUnit\Framework\TestCase
{
    private ArticleManager $m;

    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PublisherQuery::create()->deleteAll();
        ArticleQuery::create()->deleteAll();
        PeopleQuery::create()->deleteAll();
        BookCollectionQuery::create()->deleteAll();
        $this->m = new ArticleManager();
    }

    /**
     * @throws Exception
     */
    public function testGetModel(): void
    {
        // given
        $article = new Article(["id" => 1234]);

        // when
        $model = $article->getModel();

        // then
        $this->assertInstanceOf(\Model\Article::class, $model);
        $this->assertEquals(1234, $model->getId());
    }

    /**
     * @throws Exception
     */
    public function testValidateOnFetch()
    {
        // given
        $am = new ArticleManager();
        $article = EntityFactory::createArticle();
        EntityManager::prepareAndExecute(
            "UPDATE `articles` SET `publisher_id` = NULL WHERE `article_id` = :id LIMIT 1",
            ["id" => $article->get("id")]
        );

        // then
        $this->expectException("Biblys\Exception\InvalidEntityFetchedException");
        $this->expectExceptionMessage(
            sprintf("Article %s is invalid: missing publisher_id", $article->get("id"))
        );

        // when
        $am->getById($article->get("id"));
    }

    /**
     * @throws Exception
     */
    public function testValidateOnFetchIsSkippedIfNoTitle()
    {
        // given
        $am = new ArticleManager();
        $article = EntityFactory::createArticle();
        EntityManager::prepareAndExecute(
            "UPDATE `articles` SET `article_editing_user` = 1, `publisher_id` = NULL WHERE `article_id` = :id LIMIT 1",
            ["id" => $article->get("id")]
        );

        // when
        $article = $am->getById($article->get("id"));

        // then
        $this->assertInstanceOf(
            "Article",
            $article,
            "should return an Article"
        );
    }

    /**
     * @throws Exception
     */
    public function testCreate()
    {
        $article = $this->m->create(["publisher_id" => 1]);

        $this->assertInstanceOf('Article', $article);

        return $article;
    }

    /**
     * Test getting a copy
     * @throws PropelException
     * @throws Exception
     * @throws Exception
     */
    public function testGet()
    {
        $article = EntityFactory::createArticle();
        $returnedArticle = $this->m->getById($article->get('id'));

        $this->assertInstanceOf('Article', $returnedArticle);
        $this->assertEquals($article->get('id'), $returnedArticle->get('id'));

        return $article;
    }

    /**
     * Test updating a copy
     * @throws Exception
     */
    public function testUpdate()
    {
        // given
        $article = EntityFactory::createArticle();
        $article->set("article_title", "Bara Yogoi");
        $article->set("publisher_id", 262);
        $this->m->update($article);

        // when
        $updatedArticle = $this->m->getById($article->get("id"));

        // then
        $this->assertTrue($updatedArticle->has("updated"));
        $this->assertEquals("Bara Yogoi", $updatedArticle->get("title"));
        $this->assertEquals(262, $updatedArticle->get("publisher_id"));

        return $updatedArticle;
    }

    /**
     * Test keywords & links generation
     * @throws Exception
     */
    public function testRefreshMetadata()
    {
        $article = EntityFactory::createArticle();
        $this->m->refreshMetadata($article);
        $this->assertEquals(
            "L'Animalie     La Blanche PARONYMIE  Hervé LE TERRIER",
            $article->get("keywords")
        );

        /** @var Article $articleWithoutJoins */
        $articleWithoutJoins = $this->m->create([
            "article_collection" => "Présence du futur",
            "publisher_id" => 1000,
        ]);
        $this->m->refreshMetadata($articleWithoutJoins);
        $this->assertEquals(
            "     Présence du futur ",
            $articleWithoutJoins->get("keywords")
        );
    }

    /**
     * Test generating cover tag
     * @throws Exception
     * @noinspection PhpDeprecationInspection
     */
    public function testGetCoverTag()
    {
        $am = new ArticleManager();
        /** @var Article $article */
        $article = $am->create([
            "article_title" => "Bara Yogoi",
            "publisher_id" => 1,
        ]);
        $article->getCover("object")->setExists(true);

        $tag = $article->getCoverTag(["class" => "aClass", "rel" => "aRel", "size" => "w250"]);
        $tagWithWidth = $article->getCoverTag(["class" => "aClass", "rel" => "aRel", "width" => 250]);
        $tagWithHeight = $article->getCoverTag(["class" => "aClass", "rel" => "aRel", "height" => 85]);
        $tagWithoutLink = $article->getCoverTag(['link' => false]);

        $this->assertMatchesRegularExpression(
            "/<a href=\"\/images\/\/book\/\d+\/\d+\.jpg\" rel=\"aRel\"><img src=\"\/images\/\/book\/\d+\/\d+.jpg\" class=\"aClass\" alt=\"Bara Yogoi\"><\/a>/",
            $tag
        );
        $this->assertMatchesRegularExpression(
            "/<a href=\"\/images\/\/book\/\d+\/\d+\.jpg\" rel=\"aRel\"><img src=\"\/images\/\/book\/\d+\/\d+.jpg\" class=\"aClass\" alt=\"Bara Yogoi\" width=\"250\"><\/a>/",
            $tagWithWidth
        );
        $this->assertMatchesRegularExpression(
            "/<a href=\"\/images\/\/book\/\d+\/\d+\.jpg\" rel=\"aRel\"><img src=\"\/images\/\/book\/\d+\/\d+.jpg\" class=\"aClass\" alt=\"Bara Yogoi\" height=\"85\"><\/a>/",
            $tagWithHeight
        );
        $this->assertMatchesRegularExpression(
            "/<img src=\"\/images\/\/book\/\d+\/\d+\.jpg\" alt=\"Bara Yogoi\">/",
            $tagWithoutLink
        );
    }

    /**
     * Test generating cover url
     * @throws Exception
     * @noinspection PhpDeprecationInspection
     */
    public function testGetCoverUrl()
    {
        $article = EntityFactory::createArticle();
        $article->getCover("object")->setExists(true);
        $url = $article->getCoverUrl();

        $this->assertMatchesRegularExpression("/\/images\/\/book\/\d+\/\d+\.jpg/", $url);
    }

    /**
     * Test if article is available
     * @throws PropelException
     * @throws Exception
     */
    public function testIsAvailable()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isAvailable());

        $article->set('article_availability_dilicom', 1);
        $this->assertTrue($article->isAvailable());
    }

    /**
     * Test if article is available
     * @throws PropelException
     * @throws Exception
     */
    public function testIsComingSoon()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 1);
        $this->assertFalse($article->isComingSoon());

        $article->set('article_availability_dilicom', 2);
        $this->assertTrue($article->isComingSoon());

        $article->set('article_availability_dilicom', 1);
        $article->set('article_pubdate', (new DateTime('tomorrow'))->format("Y-m-d H:i:s"));
        $this->assertTrue($article->isComingSoon());
    }

    /**
     * Test if article is available
     * @throws PropelException
     * @throws Exception
     */
    public function testIsToBeReprinted()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isToBeReprinted());

        $article->set('article_availability_dilicom', 3);
        $this->assertTrue($article->isToBeReprinted());
    }

    /**
     * Test if article is sold out
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testIsSoldout()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isSoldOut());

        $article->set('article_availability_dilicom', 6);
        $this->assertTrue($article->isSoldOut());
    }

    /**
     * Test if article is available
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testIsSoonUnavailable()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isSoonUnavailable());

        $article->set('article_availability_dilicom', 9);
        $this->assertTrue($article->isSoonUnavailable());
    }

    /**
     * Test if article is available
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testIsPrivatelyPrinted()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isPrivatelyPrinted());

        $article->set('article_availability_dilicom', 10);
        $this->assertTrue($article->isPrivatelyPrinted());
    }

    /**
     * Test if article is purchasable
     * @throws Exception
     */
    public function testIsPurchasable()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertFalse($article->isPurchasable());

        $article->set('article_availability_dilicom', 1);
        $yesterday = (new DateTime("yesterday"))->format('Y-m-d');
        $article->set('article_pubdate', $yesterday);
        $this->assertTrue($article->isPurchasable());
    }

    /**
     * Test if article is purchasable
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testGetAvailabilityLed()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_availability_dilicom', 0);
        $this->assertEquals(
            '<img src="/common/img/square_red.png" title="Épuisé" alt="Épuisé">',
            $article->getAvailabilityLed()
        );

        $article->set('article_availability_dilicom', 1);
        $article->set('article_pubdate', (new DateTime())->format('Y-m-d'));
        $this->assertEquals(
            '<img src="/common/img/square_green.png" title="Disponible" alt="Disponible">',
            $article->getAvailabilityLed()
        );
    }

    /**
     * Test if article is published
     * @throws Exception
     */
    public function testIsPublished()
    {
        $today = new DateTime();
        $yesterday = new DateTime('yesterday');
        $tomorrow = new DateTime('tomorrow');

        $article = EntityFactory::createArticle();
        $article->set('article_pubdate', $today->format('Y-m-d'));
        $this->assertTrue($article->isPublished());

        $article->set('article_pubdate', $yesterday->format('Y-m-d'));
        $this->assertTrue($article->isPublished());

        $article->set('article_pubdate', $tomorrow->format('Y-m-d'));
        $this->assertFalse($article->isPublished());
    }

    /**
     * Test if article can be preordered
     * @throws PropelException
     */
    public function testIsPreorderable()
    {
        $article = EntityFactory::createArticle();
        $this->assertFalse($article->isPreorderable());

        $article->set('article_preorder', 1);
        $this->assertTrue($article->isPreorderable());
    }

    /**
     * Test if article is of a physical type
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testIsPhysical()
    {
        $article = EntityFactory::createArticle();
        $article->set('type_id', 2);
        $this->assertFalse($article->isPhysical());

        $article->set('type_id', 1);
        $this->assertTrue($article->isPhysical());
    }

    /**
     * Test if article is downloadable (non-physical)
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testIsDownloadable()
    {
        $article = EntityFactory::createArticle();
        $article->set('type_id', 1);
        $this->assertFalse($article->isDownloadable());

        $article->set('type_id', 2);
        $this->assertTrue($article->isDownloadable());
    }

    /**
     * Test if article is downloadable (non-physical)
     * @throws Exception
     * @throws Exception
     * @throws PropelException
     */
    public function testIsFree()
    {
        $article = EntityFactory::createArticle();
        $article->set('article_price', 0);
        $this->assertTrue($article->isFree());

        $article->set('article_price', 999);
        $this->assertFalse($article->isFree());
    }

    /**
     * Test getting article contributors
     * @throws Exception
     */
    public function testGetContributors()
    {
        $article = EntityFactory::createArticle(["article_title" => "Rebellion!"], []);
        $this->assertEmpty($article->getContributors());
        $this->assertFalse($article->hasOtherContributors());

        $pm = new PeopleManager();

        $people1 = $pm->create(["people_first_name" => "Han", "people_last_name" => "Solo"]);
        $people2 = $pm->create(["people_first_name" => "Leia", "people_last_name" => "Organa"]);
        $article = EntityFactory::createArticle(["article_title" => "Rebellion!"], [$people1, $people2]);

        $contributors = $article->getContributors();
        $this->assertCount(
            2,
            $contributors,
            "it should return 2 contributors"
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($people1->get('id'), $contributors[0]->get('id'));
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($people2->get('id'), $contributors[1]->get('id'));

        $authors = $article->getAuthors();
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($authors[0]->get('id'), $people1->get('id'));
        $this->assertEquals(1, $authors[0]->getJobId());

        $pm->delete($people1);
        $pm->delete($people2);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetContributorsWithDeletedPeople()
    {
        // given
        $author = EntityFactory::createPeople([
            "people_first_name" => "Auteur",
            "people_last_name" => "Disparu",
        ]);
        $article = EntityFactory::createArticle(
            ["article_title" => "La disparition (de l'auteur)"],
            [$author]
        );
        $peopleModel = PeopleQuery::create()->findPk($author->get("id"));
        $peopleModel->delete();

        // then
        $this->expectException("Biblys\Exception\InvalidEntityException");
        $this->expectExceptionMessage(
            sprintf(
                "Cannot load article %s with invalid contribution: contributor %s does not exist",
                $article->get("id"),
                $author->get("id"),
            )
        );

        // when
        $article->getContributors();
    }

    /** Test getting article contributors
     * @throws Exception
     */
    public function testGetDownloadableFiles()
    {
        $article = EntityFactory::createArticle();
        $this->assertFalse($article->hasDownloadableFiles());
        $this->assertEmpty($article->getDownloadableFiles());

        $fm = new FileManager();
        $am = new ArticleManager();

        $paid = $fm->create(["file_access" => 1, "article_id" => $article->get('id')]);
        $free = $fm->create(["file_access" => 0, "article_id" => $article->get('id')]);

        /** @var Article $article */
        $article = $am->getById($article->get('id'));

        $files = $article->getDownloadableFiles();
        $this->assertEquals($files[0]->get('id'), $paid->get('id'));
        $this->assertEquals($files[1]->get('id'), $free->get('id'));

        $paids = $article->getDownloadableFiles('paid');
        $this->assertEquals($paids[0]->get('id'), $paid->get('id'));
        $this->assertEquals(1, $paids[0]->get('access'));

        $frees = $article->getDownloadableFiles('free');
        $this->assertEquals($frees[0]->get('id'), $free->get('id'));
        $this->assertEquals(0, $frees[0]->get('access'));
    }

    /**
     * Test getting available copies for article
     * @return void
     * @throws Exception
     */
    public function testGetAvailableCopies()
    {
        $sm = new StockManager();

        $article = EntityFactory::createArticle();
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
     * @throws Exception
     */
    public function testGetCheapestAvailable()
    {
        $sm = new StockManager();

        $article = EntityFactory::createArticle();
        $sm->create([
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
     * @throws Exception
     */
    public function testGetAwards()
    {
        $am = new AwardManager();
        $article = EntityFactory::createArticle();
        $award = $am->create(['award_name' => 'Prix Goncourt', 'article_id' => $article->get('id')]);

        $awards = $article->getAwards();
        $gotAward = $awards[0];

        $this->assertEquals($award->get('name'), $gotAward->get('name'));

        $am->delete($award);
    }

    /**
     * Test setType and getType methods
     * @throws Exception
     * @throws Exception
     */
    public function testSetGetType()
    {
        $article = new Article(["publisher_id" => 1]);
        $type = ArticleType::getById(1);

        $article->setType($type);
        $type = $article->getType();

        $this->assertEquals(1, $type->getId());
    }

    /**
     * Test deleting a collection with links
     * @throws PropelException
     */
    public function testBeforeDeleteWithLinks()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de supprimer cet article car des éléments y sont liés.");

        $am = new ArticleManager();
        $lm = new LinkManager();
        $article = EntityFactory::createArticle();
        $lm->create(['article_id' => $article->get('id')]);

        $am->delete($article);
    }

    /**
     * Test deleting a collection with stock
     * @throws PropelException
     */
    public function testBeforeDeleteWithStock()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de supprimer cet article car des exemplaires y sont associés.");

        $am = new ArticleManager();
        $sm = new StockManager();
        $article = EntityFactory::createArticle();
        $sm->create(['article_id' => $article->get('id')]);

        $am->delete($article);
    }

    /**
     * Test that adding a too long string as article_authors does not validate
     * @throws Exception
     */
    public function testValidateArticleAuthorsLength()
    {
        $this->expectException("Biblys\Exception\InvalidEntityException");
        $this->expectExceptionMessage("Le champ Auteurs ne peut pas dépasser 256 caractères.");

        $am = new ArticleManager();
        $article = new Article([
            "url" => "article/url",
            "publisher_id" => 1,
        ]);
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
     * @throws Exception
     */
    public function testUpdatingArticleWithoutUrl()
    {
        $this->expectException("Biblys\Exception\InvalidEntityException");
        $this->expectExceptionMessage("L'article doit avoir une url");

        $am = new ArticleManager();
        $article = $am->create(["publisher_id" => 1]);

        $am->update($article);
    }

    /**
     * @throws Exception
     */
    public function testUpdatingArticleWithoutUrlAndBeingEdited()
    {
        // given
        $am = new ArticleManager();
        $article = $am->create(["publisher_id" => 1, "article_editing_user" => 1]);

        // when
        $am->update($article);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * Test that updating an article without an url throws
     * @throws Exception
     */
    public function testUpdatingArticleExistingUrl()
    {
        // then
        $this->expectException("Biblys\Exception\InvalidEntityException");
        $this->expectExceptionMessage("Il existe déjà un article avec l'url anne-onyme/hous");

        // given
        $am = new ArticleManager();
        EntityFactory::createArticle(["article_url" => "anne-onyme/hous"]);
        $article = $am->create(["article_url" => "anne-onyme/hous"]);

        // when
        $am->update($article);
    }

    /**
     * Test that updating an article without a publisher does not throw
     * @throws Exception
     */
    public function testUpdatingArticleWithNoPublisher()
    {
        // then
        $this->expectNotToPerformAssertions();

        // given
        $pm = new PublisherManager();
        $publisherAllowed = $pm->create(["publisher_name" => "Éditeur inexistant"]);
        $sm = new SiteManager();

        /** @var Site $site */
        $site = $sm->create();
        $GLOBALS["LEGACY_CURRENT_SITE"] = $site;
        $GLOBALS["LEGACY_CURRENT_SITE"]->setOpt("publisher_filter", $publisherAllowed->get("id"));
        $am = new ArticleManager();

        $am->setIgnoreSiteFilters(true);
        $article = $am->create([
            "article_url" => "jean-bon/de-bayonne",
            "publisher_id" => null,
            "article_editing_user" => 1,
        ]);

        // when
        $am->update($article);
    }

    /**
     * @throws Exception
     */
    public function testPreprocessSlugWithOneAuthor()
    {
        // given
        $GLOBALS["LEGACY_CURRENT_SITE"] = EntityFactory::createSite();
        $am = new ArticleManager();
        $article = EntityFactory::createArticle([
            "article_title" => "Pénates du soir"
        ]);

        // when
        $article = $am->preprocess($article);

        // then
        $this->assertEquals(
            "herve-le-terrier/penates-du-soir",
            $article->get('url'),
            "it should generate correct slug for one author"
        );
    }

    /**
     * @throws Exception
     */
    public function testPreprocessSlugWithSeveralAuthors()
    {
        // given
        $am = new ArticleManager();
        $pm = new PeopleManager();
        $riri = $pm->create(["people_last_name" => "Riri"]);
        $fifi = $pm->create(["people_last_name" => "Fifi"]);
        $loulou = $pm->create(["people_last_name" => "Loulou"]);
        $article = EntityFactory::createArticle(
            ["article_title" => "La Bande à Picsou"],
            [$riri, $fifi, $loulou]
        );

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
     * @throws ArticleAlreadyInRayonException
     * @throws Exception
     */
    public function testAddRayon()
    {
        // given
        $am = new ArticleManager();
        $article = EntityFactory::createArticle();
        $rayon = EntityFactory::createRayon();
        $lm = new LinkManager();

        // when
        $am->addRayon($article, $rayon);

        // then
        $link = $lm->exists([
            "article_id" => $article->get("id"),
            "rayon_id" => $rayon->get("id"),
        ]);
        $this->assertTrue($link);
    }

    /**
     * @throws Exception
     */
    public function testAddArticleAlreadyInRayon()
    {
        // then
        $this->expectException("Biblys\Exception\ArticleAlreadyInRayonException");
        $this->expectExceptionMessage("L'article « C'est mon rayon » est déjà dans le rayon « Mon rayon ».");

        // given
        $am = new ArticleManager();
        $article = EntityFactory::createArticle(["article_title" => "C'est mon rayon"]);
        $rayon = EntityFactory::createRayon(["rayon_name" => "Mon rayon"]);

        // when
        $am->addRayon($article, $rayon);
        $am->addRayon($article, $rayon);
    }

    /**
     * @throws Exception
     * @noinspection PhpDeprecationInspection
     */
    public function testHasCoverWithCover()
    {
        // given
        $article = new Article(["publisher_id" => 1]);
        $article->getCover("object")->setExists(true);

        // when
        $hasCover = $article->hasCover();

        // then
        $this->assertTrue($hasCover, "it returns true when there is a cover");
    }

    /**
     * @throws Exception
     * @noinspection PhpDeprecationInspection
     */
    public function testHasCoverWithoutCover()
    {
        // given
        $article = new Article(["publisher_id" => 1]);
        $article->getCover("object")->setExists(false);

        // when
        $hasCover = $article->hasCover();

        // then
        $this->assertFalse($hasCover, "it returns false when there is no cover");
    }

    /**
     * Test deleting a copy
     * @throws Exception
     */
    public function testDelete()
    {
        // given
        $article = EntityFactory::createArticle();
        $am = new ArticleManager();

        // when
        $am->delete($article);

        // then
        $articleExists = $am->getById($article->get("id"));
        $this->assertFalse($articleExists);
    }

    /**
     * @throws Exception
     */
    public function testIsBeingCreated()
    {
        // given
        $article = EntityFactory::createArticle();
        $article->set("article_editing_user", 1);

        // when
        $isBeingCreated = $article->isBeingCreated();

        // then
        $this->assertTrue(
            $isBeingCreated,
            "should be true when there is an editing user"
        );
    }

    /**
     * @throws Exception
     */
    public function testGetAllFromRayon()
    {
        // given
        $article = EntityFactory::createArticle();
        $rayon = EntityFactory::createRayon(["rayon_name" => "getAllFromRayon"]);
        $rayon->addArticle($article);
        $am = new ArticleManager();

        // when
        $articles = $am->getAllFromRayon($rayon);

        // then
        $this->assertEquals(
            $article->get('id'),
            $articles[0]->get('id'),
            "it should return all articles from a rayon"
        );
    }



    /**
     * @throws Exception
     */
    public function testCountSearchResultsForAvailableStock()
    {
        // given
        $currentSite = ModelFactory::createSite();
        $articleWithStock = ModelFactory::createArticle(keywords: "Article à trouver");
        ModelFactory::createArticle(keywords: "Article à trouver");
        ModelFactory::createStockItem(site: $currentSite, article: $articleWithStock);
        ModelFactory::createStockItem(site: $currentSite, article: $articleWithStock);
        $otherArticleWithStockToFound = ModelFactory::createArticle(keywords: "Article à trouver aussi");
        ModelFactory::createStockItem(site: $currentSite, article: $otherArticleWithStockToFound);
        $otherArticleWithStock = ModelFactory::createArticle(keywords: "Sans rapport");
        ModelFactory::createStockItem(site: $currentSite, article: $otherArticleWithStock);
        $am = new ArticleManager();

        // when
        $count = $am->countSearchResultsForAvailableStock("Article à trouver");

        // then
        $this->assertEquals(
            2,
            $count,
            "returns the correct number of article with available stock"
        );
    }

    /**
     * @throws Exception
     */
    public function testSearchWithAvailableStock()
    {
        // given
        $currentSite = ModelFactory::createSite();
        $articleWithStock = ModelFactory::createArticle(keywords: "Article à trouver");
        ModelFactory::createArticle(keywords: "Article à trouver");
        ModelFactory::createStockItem(site: $currentSite, article: $articleWithStock);
        $otherArticleWithStock = ModelFactory::createArticle(keywords: "Sans rapport");
        ModelFactory::createStockItem(site: $currentSite, article: $otherArticleWithStock);
        $am = new ArticleManager();

        // when
        $results = $am->searchWithAvailableStock("Article à trouver");

        // then
        $this->assertCount(1, $results, "returns 1 result");
        $this->assertEquals(
            $articleWithStock->getId(),
            $results[0]->get("id"),
            "returns all articles with available stock"
        );
    }
}
