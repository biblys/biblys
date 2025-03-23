<?php

namespace AppBundle\Controller;

use Biblys\Contributor\UnknownJobException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\SearchService;
use Biblys\Service\TemplateService;
use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SearchController extends Controller
{
    /**
     * @throws PropelException
     * @throws UnknownJobException
     */
    public function createIndexAction(
        CurrentUser   $currentUser,
        CurrentSite   $currentSite,
        ImagesService $imagesService
    ): Response
    {
        $currentUser->authAdmin();

        $search = new SearchService();
        $count = $search->createIndex($currentSite, $imagesService);

        return new Response($count . " articles indexed");
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function searchAction(
        QueryParamsService $queryParams,
        TemplateService    $templateService
    ): Response
    {
        $queryParams->parse(["q" => ["type" => "string"]]);
        $query = $queryParams->get("q");

        $search = new SearchService();
        $results = $search->search($query);

        return $templateService->renderResponse("AppBundle:Search:search.html.twig", [
            "query" => $query,
            "autofocus" => false,
            "count" => $results->getTotalHits(),
            "sortOptions" => [],
            "results" => $results->getHits(),
            "inStockFilterChecked" => false,
        ]);
    }
}