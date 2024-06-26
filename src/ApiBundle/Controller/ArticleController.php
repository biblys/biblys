<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Framework\Controller;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @route GET /admin/articles/export
     * @throws PropelException
     * @throws CannotInsertRecord
     * @throws \Exception
     */
    public function export(CurrentUser $currentUser, CurrentSite $currentSiteService): Response
    {
        $currentUser->authAdmin();

        $currentSite = $currentSiteService->getSite();
        $fileName = "{$currentSite->getName()}-catalog.csv";

        $response = new Response();
        $response->headers->set("Content-Type", "text/csv; charset=utf-8");
        $response->headers->set("Content-Disposition", "attachment; filename=$fileName");

        $csv = Writer::createFromString();
        $csv->insertOne(["EAN", "Titre", "Auteur·trice·s", "Collection", "Éditeur", "Prix", "Stock"]);

        $articles = ArticleQuery::create()->filterForCurrentSite($currentSiteService)->find();
        foreach ($articles as $article) {
            $csv->insertOne([
                $article->getEan(),
                $article->getTitle(),
                $article->getAuthors(),
                $article->getCollectionName(),
                $article->getPublisherName(),
                $article->getPrice() / 100,
                $article->countAvailableStockItemsForSite($currentSite),
            ]);
        }

        $response->setContent($csv);
        return $response;
    }
}