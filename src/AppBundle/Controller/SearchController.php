<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use Loupe\Loupe\Loupe;
use Loupe\Loupe\SearchParameters;
use Model\Article;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Loupe\Loupe\Config\TypoTolerance;
use Loupe\Loupe\Configuration;
use Loupe\Loupe\LoupeFactory;

class SearchController extends Controller
{
    /**
     * @throws PropelException
     */
    public function createIndexAction(
        CurrentUser $currentUser,
        CurrentSite $currentSite
    ): Response
    {
        $currentUser->authAdmin();

        $articleQuery = ArticleQuery::create();
        $publisherFilter = $currentSite->getOption("publisher_filter");
        if ($publisherFilter) {
            $articleQuery->filterByPublisherId($publisherFilter);
        }
        $articles = $articleQuery->find();

        $loupe = $this->_getLoupeClient();

        /** @var Article $article */
        foreach ($articles as $article) {

            $contributors = [];
            $roles = $article->getRoles();
            foreach ($roles as $role) {
                $contributors[] = $role->getPeople()->getFullName();
            }

            $loupe->addDocument([
                'uuid' => $article->getId(),
                'title' => $article->getTitle(),
                'contributors' => $contributors,
                'publisher' => $article->getPublisher()->getName(),
                'type' => $article->getType()->getName(),
                'price' => $article->getPrice(),
            ]);
        }

        return new Response($articles->count()." articles indexed");
    }

    public function searchAction(QueryParamsService $queryParams): Response
    {
        $queryParams->parse(["q" => ["type" => "string"]]);
        $q = $queryParams->get("q");

        $searchParameters = SearchParameters::create()
            ->withQuery($q)
            ->withAttributesToRetrieve(["title", "contributors"])
            ->withSort(["title:asc"])
            ->withHitsPerPage(100)
            ->withPage(1)
        ;

        $loupe = $this->_getLoupeClient();
        $results = $loupe->search($searchParameters);

        $content = "";

        foreach ($results->getHits() as $hit) {
            $contributors = !empty($hit["contributors"]) ? join(", ", $hit["contributors"]) : "";
            $content .= $hit["title"] . " - " . $contributors . "<br>";
        }

        return new Response($content);
    }

    /**
     * @return Loupe
     */
    private function _getLoupeClient(): Loupe
    {
        $configuration = Configuration::create()
            ->withPrimaryKey('uuid') // optional, by default it's 'id'
            ->withSearchableAttributes(['title', 'contributors', 'publisher']) // optional, by default it's ['*'] - everything is indexed
            ->withFilterableAttributes(['type'])
            ->withSortableAttributes(['title', 'price'])
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