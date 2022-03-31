<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use League\Csv\CannotInsertRecord;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";
class ArticleControllerTest extends TestCase
{
    /**
     * @throws AuthException
     * @throws PropelException
     * @throws CannotInsertRecord
     */
    public function testExportCatalog()
    {
        // given
        $controller = new ArticleController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $site = ModelFactory::createSite();
        $site->setName("paronymie");
        $currentSite = new CurrentSite($site);

        $publisher = ModelFactory::createPublisher(["name" => "Les Éditions Paronymie"]);
        $collection = ModelFactory::createCollection(["publisher" => $publisher]);
        $author = ModelFactory::createPeople(["first_name" => "Albert", "last_name" => "Koalanstein"]);
        $article = ModelFactory::createArticle([
            "title" => "L'Animalie",
            "ean" => "9781234567897",
            "price" => "1500",
        ], $publisher, $collection, [$author]);
        ModelFactory::createStockItem([], $site, $article);
        ModelFactory::createArticle([
            "title" => "Au-revoir, Mao",
            "ean" => "9781234567844",
            "price" => "999",
        ], $publisher, $collection, [$author]);
        ModelFactory::createArticle([
            "title" => "Le \"Serpent\" sur la butte aux pommes",
            "ean" => "9781234567833",
            "price" => "0",
        ], $publisher, $collection, [$author]);
        $currentSite->setOption("publisher_filter", $publisher->getId());

        $publisher = ModelFactory::createPublisher(["name" => "Un autre éditeur"]);
        ModelFactory::createArticle([
            "title" => "Livre d'un autre éditeur",
            "ean" => "9789876543210",
            "price" => "21",
        ], $publisher);

        // when
        $response = $controller->export($request, $currentSite);

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
        $csv .= "9781234567897,L'Animalie,\"Albert Koalanstein\",\"La Blanche\",\"Les Éditions Paronymie\",15,1\n";
        $csv .= "9781234567844,\"Au-revoir, Mao\",\"Albert Koalanstein\",\"La Blanche\",\"Les Éditions Paronymie\",9.99,0\n";
        $csv .= "9781234567833,\"Le \"\"Serpent\"\" sur la butte aux pommes\",\"Albert Koalanstein\",\"La Blanche\",\"Les Éditions Paronymie\",0,0\n";
        $this->assertEquals(
            $csv,
            $response->getContent(),
            "it should return the catalog"
        );

    }
}
