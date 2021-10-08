<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Test\Factory;

require_once "setUp.php";

class RayonTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a rayon
     */
    public function testCreate()
    {
        global $site;

        $rm = new RayonManager();

        $rayon = $rm->create(['rayon_name' => 'Arts']);

        $this->assertInstanceOf('Rayon', $rayon);
        $this->assertEquals($rayon->get('site_id'), $site->get('id'));
        $this->assertEquals($rayon->get('sort_by'), 'id');
        $this->assertEquals($rayon->get('sort_by_order'), 0);

        return $rayon;
    }

    /**
     * Test getting a rayon
     * @depends testCreate
     */
    public function testGet(Rayon $rayon)
    {
        $rm = new RayonManager();

        $gotRayon = $rm->getById($rayon->get('id'));

        $this->assertInstanceOf('Rayon', $rayon);
        $this->assertEquals($rayon->get('id'), $gotRayon->get('id'));

        return $rayon;
    }

    /**
     * Test updating a rayon
     * @depends testGet
     */
    public function testUpdate(Rayon $rayon)
    {
        $rm = new RayonManager();

        $rayon->set('rayon_name', 'Fruits & légumes');
        $rayon->set('rayon_sort_by', 'pubdate');
        $rayon->set('rayon_sort_order', 1);
        $rm->update($rayon);

        $updatedRayon = $rm->getById($rayon->get('id'));

        $this->assertTrue($updatedRayon->has('updated'));
        $this->assertEquals($updatedRayon->get('name'), 'Fruits & légumes');
        $this->assertEquals($updatedRayon->get('url'), 'fruits-et-legumes');
        $this->assertEquals($updatedRayon->get('sort_by'), 'pubdate');
        $this->assertEquals($updatedRayon->get('sort_order'), 1);

        return $updatedRayon;
    }

    /**
     * Test that two rayons cannot have the same name
     */
    public function testCreateTagWithoutAName()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Le rayon doit avoir un nom.");

        $rm = new RayonManager();

        $rm->create(['rayon_name' => '']);
    }

    /**
     * Test that two publisher cannot have the same name
     */
    public function testDuplicateNameCheck()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Il existe déjà un rayon avec le nom Fruits & légumes.");

        $rm = new RayonManager();

        $rm->create(['rayon_name' => 'Fruits & légumes']);
    }

    /**
     * Test adding an article to a rayon
     * @depends testGet
     */
    public function testAddArticle(Rayon $rayon)
    {
        // given
        $rm = new RayonManager();
        $am = new ArticleManager();
        $pm = new PeopleManager();
        $article = Factory::createArticle();

        // when
        $rayon->addArticle($article);

        // then
        $this->assertEquals($article->get('links'), '[rayon:'.$rayon->get('id').']');
    }

    /**
     * Test adding an article to a rayon that is already in Rayon
     * @depends testGet
     */
    public function testAddArticleAlreadyInRayon(Rayon $rayon)
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("L'article « Banane » est déjà dans le rayon « Fruits & légumes ».");

        $article = Factory::createArticle(["article_title" => "Banane", "article_url" => "banane"]);

        $rayon->addArticle($article);
        $rayon->addArticle($article);
    }

    /**
     * @throws Exception
     */
    public function testCountArticles()
    {
        // given
        $rayon = Factory::createRayon(["rayon_name" => "Combien ?"]);
        $article = Factory::createArticle();
        $rayon->addArticle($article);

        // when
        $count = $rayon->countArticles();

        // then
        $this->assertEquals(
            1,
            $count,
            "should count articles in rayon"
        );
    }

    /**
     * Test deleting a rayon
     * @depends testGet
     */
    public function testDelete(Rayon $rayon)
    {
        $rm = new RayonManager();

        $rm->delete($rayon, 'Test entity');

        $rayonExists = $rm->getById($rayon->get('id'));

        $this->assertFalse($rayonExists);
    }
}
