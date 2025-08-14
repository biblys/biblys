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


namespace ApiBundle\Controller;

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use Mockery;
use Model\ArticleQuery;
use Model\LinkQuery;
use Payplug\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";
class ArticleControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        ArticleQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function testExportCatalog()
    {
        // given
        $controller = new ArticleController();
        $site = ModelFactory::createSite();
        $site->setName("paronymie");
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        $publisher = ModelFactory::createPublisher(name: "Un éditeur");
        $collection = ModelFactory::createCollection(publisher: $publisher);
        $author = ModelFactory::createPeople(["first_name" => "Albert", "last_name" => "Koalanstein"]);
        $article = ModelFactory::createArticle(
            title: "L'Animalie",
            authors: [$author],
            ean: "9781234567897",
            price: "1500",
            publisher: $publisher,
            collection: $collection
        );
        ModelFactory::createStockItem(site: $site, article: $article);
        ModelFactory::createArticle(
            title: "Au-revoir, Mao",
            authors: [$author],
            ean: "9781234567844",
            price: "999",
            publisher: $publisher,
            collection: $collection
        );
        ModelFactory::createArticle(
            title: "Le \"Serpent\" sur la butte aux pommes",
            authors: [$author],
            ean: "9781234567833",
            price: "0",
            publisher: $publisher,
            collection: $collection
        );
        $currentSite->setOption("publisher_filter", $publisher->getId());

        $publisher = ModelFactory::createPublisher(name: "Un autre éditeur");
        ModelFactory::createArticle(
            title: "Livre d'un autre éditeur",
            ean: "9789876543210",
            price: "21",
            publisher: $publisher
        );

        // when
        $response = $controller->export($currentUser, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertEquals(
            "text/csv; charset=utf-8",
            $response->headers->get("Content-Type"),
            "it should return CSV content"
        );
        $this->assertEquals(
            "attachment; filename=paronymie-catalog.csv",
            $response->headers->get("Content-Disposition"),
            "it should return a downloadable file"
        );

        $csv  = "EAN,Titre,Auteur·trice·s,Collection,Éditeur,Prix,Stock\n";
        $csv .= "9781234567897,L'Animalie,\"Albert Koalanstein\",\"La Blanche\",\"Un éditeur\",15,1\n";
        $csv .= "9781234567844,\"Au-revoir, Mao\",\"Albert Koalanstein\",\"La Blanche\",\"Un éditeur\",9.99,0\n";
        $csv .= "9781234567833,\"Le \"\"Serpent\"\" sur la butte aux pommes\",\"Albert Koalanstein\",\"La Blanche\",\"Un éditeur\",0,0\n";
        $this->assertEquals(
            $csv,
            $response->getContent(),
            "it should return the catalog"
        );

    }


    /** addToBundle */

    /**
     * @throws PropelException
     * @throws NotFoundException
     */
    public function testAddToBundleAction()
    {
        // given
        $controller = new ArticleController();
        $bundleArticle = ModelFactory::createArticle(title: "Un article de type lot");
        $articleToAdd = ModelFactory::createArticle(
            title: "Un article à ajouter au lot",
            authors: [ModelFactory::createContributor(firstName: "Lolo", lastName: "Lot")],
            url: "un-article-a-ajouter-au-lot",
            collection: ModelFactory::createCollection(name: "En lot"),
        );

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authPublisher")->once()->andReturn(true);

        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->shouldReceive("parse")->with(["bundle_id" => ["type" => "numeric"]]);
        $bodyParamsService->shouldReceive("getInteger")->with("bundle_id")->andReturn($bundleArticle->getId());

        // when
        $response = $controller->addToBundleAction(
            $currentUser,
            $bodyParamsService,
            $articleToAdd->getId()
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $link = LinkQuery::create()
            ->filterByArticleId($articleToAdd->getId())
            ->filterByBundleId($bundleArticle->getId())
            ->findOne();
        $this->assertNotNull($link);
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([
            "link_id" => $link->getId(),
            "article_title" => "Un article à ajouter au lot",
            "article_authors" => "Lolo Lot",
            "article_collection" => "En lot",
            "article_url" => "lolo-lot/un-article-a-ajouter-au-lot",
        ], $responseData);
    }

    /**
     * @throws PropelException
     * @throws NotFoundException
     */
    public function testRemoveTagAction(): void
    {
        // given
        $controller = new ArticleController();

        $article = ModelFactory::createArticle();
        $tag = ModelFactory::createTag();
        $article->addTag($tag);

        $curentUser = Mockery::mock(CurrentUser::class);
        $curentUser->shouldReceive("authPublisher")->once()->andReturn();

        // when
        $controller->removeTagAction($curentUser, $article->getId(), $tag->getId());

        // then
        $this->assertNotContains($tag, $article->getTags());
    }
}
