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

require_once "setUp.php";

class PublisherTest extends PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $this->publisher = null;
    }

    public function tearDown(): void
    {
        if ($this->publisher)
        {
            $pm = new PublisherManager();
            $pm->delete($this->publisher);
            $this->publisher = null;
        }
    }

    /**
     * Test creating a post
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $pm = new PublisherManager();

        $this->publisher = $pm->create(['publisher_name' => 'Un éditeur']);

        $this->assertInstanceOf('Publisher', $this->publisher);
        $this->assertEquals($this->publisher->get('name'), 'UN ÉDITEUR');
    }

    /**
     * Test getting a post
     */
    public function testGet()
    {
        $pm = new PublisherManager();
        $this->publisher = $pm->create(['publisher_name' => 'Un éditeur']);

        $gotPublisher = $pm->getById($this->publisher->get('id'));

        $this->assertInstanceOf('Publisher', $this->publisher);
    }

    /**
     * Test updating a post
     */
    public function testUpdate()
    {
        $pm = new PublisherManager();
        $this->publisher = $pm->create(['publisher_name' => 'Un éditeur']);

        $this->publisher->set('publisher_name', 'Les éditions Publitou');
        $pm->update($this->publisher);

        $updatedPublisher = $pm->getById($this->publisher->get('id'));

        $this->assertTrue($updatedPublisher->has('updated'));
        $this->assertEquals($updatedPublisher->get('name'), 'LES ÉDITIONS PUBLITOU');
        $this->assertEquals($updatedPublisher->get('url'), 'les-editions-publitou');
        $this->assertEquals($updatedPublisher->get('name_alphabetic'), 'ÉDITIONS PUBLITOU, LES ');
    }

    /**
     * Test getting articles for this publisher
     */
    public function testGetArticles()
    {
        $pm = new PublisherManager();
        $am = new ArticleManager();

        $this->publisher = $pm->create(["publisher_name" => "Les Éditions Paronymie"]);
        $article = $am->create([
            "article_title" => "Sous-sol",
            "publisher_id" => $this->publisher->get('id'),
        ]);

        $this->assertEquals($this->publisher->countArticles(), 1);

        $am->delete($article);
    }

    /**
     * Test counting articles for this publisher
     */
    public function testCountArticles()
    {
        $pm = new PublisherManager();
        $am = new ArticleManager();

        $this->publisher = $pm->create(["publisher_name" => "Les Éditions Paronymie"]);
        $article = $am->create([
            "article_title" => "Sous-sol",
            "publisher_id" => $this->publisher->get('id'),
        ]);

        $articles = $this->publisher->getArticles();
        $this->assertEquals($articles[0]->get('id'), $article->get('id'));

        $am->delete($article);
    }

    /**
     * Test that two tag cannot have the same name
     */
    public function testCreateTagWithoutAName()
    {
        $this->expectException("\Biblys\Exception\InvalidEntityException");
        $this->expectExceptionMessage("L'éditeur doit avoir un nom.");

        $pm = new PublisherManager();

        $pm->create(['publisher_name' => '']);
    }

    /**
     * Test that two publisher cannot have the same name
     */
    public function testDuplicateNameCheck()
    {
        $this->expectException("\Biblys\Exception\EntityAlreadyExistsException");
        $this->expectExceptionMessage("Il existe déjà un éditeur avec le nom LES ÉDITIONS PUBLITOU.");

        $pm = new PublisherManager();

        $this->publisher = $pm->create(['publisher_name' => 'Les éditions Publitou']);
        $pm->create(['publisher_name' => 'Les éditions Publitou']);
    }

    /**
     * Test updating a post
     */
    public function testDelete()
    {
        $pm = new PublisherManager();
        $publisher = $pm->create(["publisher_name" => "Les Éditions Paronymie"]);

        $pm->delete($publisher);

        $publisher = $pm->getById($publisher->get('id'));
        $this->assertFalse($publisher);
    }

    /**
     * @throws Exception
     */
    public function testGetModel(): void
    {
        // given
        $pm = new PublisherManager();
        /** @var Publisher $publisher */
        $publisher = $pm->create(["publisher_name" => "Un éditeur modèle"]);

        // when
        $model = $publisher->getModel();

        // then
        $this->assertEquals("UN ÉDITEUR MODÈLE", $model->getName());
    }
}
