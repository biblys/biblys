<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class RayonTest extends PHPUnit_Framework_TestCase
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
     * @expectedException Exception
     * @expectedExceptionMessage Le rayon doit avoir un nom.
     */
    public function testCreateTagWithoutAName()
    {
        $rm = new RayonManager();

        $rm->create(['rayon_name' => '']);
    }

    /**
     * Test that two publisher cannot have the same name
     * @expectedException Exception
     * @expectedExceptionMessage Il existe déjà un rayon avec le nom Fruits & légumes.
     */
    public function testDuplicateNameCheck()
    {
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
        $article = $am->create(["article_title" => "Pomme", "article_url" => "pomme"]);

        // when
        $rayon->addArticle($article);

        // then
        $this->assertEquals($article->get('links'), '[rayon:'.$rayon->get('id').']');
    }

    /**
     * Test adding an article to a rayon that is already in Rayon
     * @depends testGet
     * @expectedException Exception
     * @expectedExceptionMessage L'article « Banane » est déjà dans le rayon « Fruits & légumes ».
     */
    public function testAddArticleAlreadyInRayon(Rayon $rayon)
    {
        $rm = new RayonManager();
        $am = new ArticleManager();

        $article = $am->create(["article_title" => "Banane", "article_url" => "banane"]);

        $rayon->addArticle($article);
        $rayon->addArticle($article);
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
