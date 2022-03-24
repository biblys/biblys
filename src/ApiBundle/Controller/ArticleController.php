<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentSite;
use Framework\Controller;
use Framework\Exception\AuthException;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @route GET /admin/articles/export
     * @throws AuthException
     * @throws PropelException
     * @throws CannotInsertRecord
     */
    public function export(Request $request, CurrentSite $currentSiteService): Response
    {
        self::authAdmin($request);

        $currentSite = $currentSiteService->getSite();
        $fileName = "{$currentSite->getName()}-catalog.csv";

        $response = new Response();
        $response->headers->set("Content-Type", "text/csv; charset=utf-8");
        $response->headers->set("Content-Disposition", "attachment; filename=$fileName");

        $csv = Writer::createFromString();
        $csv->insertOne(["EAN", "Titre", "Collection", "Éditeur", "Prix"]);

        $articles = ArticleQuery::create()->filterForCurrentSite($currentSiteService)->find();
        foreach ($articles as $article) {
            $csv->insertOne([
                $article->getEan(),
                $article->getTitle(),
                $article->getCollectionName(),
                $article->getPublisherName(),
                $article->getPrice() / 100,
            ]);
        }

        $response->setContent($csv);
        return $response;
    }
}