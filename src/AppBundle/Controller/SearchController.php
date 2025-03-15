<?php

namespace AppBundle\Controller;

use Biblys\Contributor\UnknownJobException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Framework\Controller;
use Loupe\Loupe\Loupe;
use Model\Article;
use Model\ArticleQuery;
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
     * @return Loupe
     */
    private function _getLoupeClient(): Loupe
    {
        $configuration = Configuration::create()
            ->withSearchableAttributes(["title", "authors", "publisher"]) // optional, by default it's ['*'] - everything is indexed
            ->withFilterableAttributes(["type"])
            ->withSortableAttributes(["title", "price", "publisher"])
            ->withTypoTolerance(TypoTolerance::create()->withFirstCharTypoCountsDouble(false)) // can be further fine-tuned but is enabled by default
        ;
        $searchIndexDirectory = $this->_getSearchIndexDirectory();
        return (new LoupeFactory())->create($searchIndexDirectory, $configuration);
    }

    /**
     * @return string
     */
    private function _getSearchIndexDirectory(): string
    {
        return __DIR__ . "/../../../content/search-index";
    }
}