<?php

namespace Biblys\Service;

use Biblys\Contributor\UnknownJobException;
use Biblys\Service\Images\ImagesService;
use Loupe\Loupe\Config\TypoTolerance;
use Loupe\Loupe\Configuration;
use Loupe\Loupe\Loupe;
use Loupe\Loupe\LoupeFactory;
use Loupe\Loupe\SearchParameters;
use Loupe\Loupe\SearchResult;
use Model\Article;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;

class SearchService
{
    private Loupe $loupe;

    public function __construct() {
        $configuration = Configuration::create()
            ->withSearchableAttributes(["title", "authors", "publisher"]) // optional, by default it's ['*'] - everything is indexed
            ->withFilterableAttributes(["type"])
            ->withSortableAttributes(["title", "price", "publisher"])
            ->withTypoTolerance(TypoTolerance::create()->withFirstCharTypoCountsDouble(false)) // can be further fine-tuned but is enabled by default
        ;
        $searchIndexDirectory = $this->_getSearchIndexDirectory();
        $this->loupe = (new LoupeFactory())->create($searchIndexDirectory, $configuration);
    }

    public function resetIndex(): void
    {
        $searchIndexDirectory = $this->_getSearchIndexDirectory();
        $searchIndexFile = "$searchIndexDirectory/loupe.db";
        if (file_exists($searchIndexFile)) {
            unlink($searchIndexFile);
        }
    }

    /**
     * @throws PropelException
     * @throws UnknownJobException
     */
    public function createIndex(CurrentSite $currentSite, ImagesService $imagesService): int
    {
        $articleQuery = ArticleQuery::create();
        $publisherFilter = $currentSite->getOption("publisher_filter");
        if ($publisherFilter) {
            $articleQuery->filterByPublisherId($publisherFilter);
        }
        $articles = $articleQuery->find();

        /** @var Article $article */
        foreach ($articles as $article) {

            $authorsName = array_map(fn($author) => $author->getPeople()->getFullName(), $article->getAuthors());

            $imageUrl = null;
            if ($imagesService->imageExistsFor($article)) {
                $imageUrl = $imagesService->getImageUrlFor($article, width: 100);
            }

            $this->loupe->addDocument([
                "id" => $article->getId(),
                "title" => $article->getTitle(),
                "slug" => $article->getSlug(),
                "authors" => $authorsName,
                "publisher" => $article->getPublisher()?->getName(),
                "price" => $article->getPrice(),
                "imageUrl" => $imageUrl,
            ]);
        }

        return $articles->count();
    }

    public function search(string $query): SearchResult
    {
        $searchParameters = SearchParameters::create()
            ->withQuery($query)
            ->withAttributesToRetrieve(["id", "title", "slug", "price", "authors", "imageUrl"])
            ->withSort(["title:asc"])
            ->withHitsPerPage(100)
            ->withPage(1);

        return $this->loupe->search($searchParameters);
    }

    /**
     * @return string
     */
    private function _getSearchIndexDirectory(): string
    {
        return __DIR__ . "/../../../content/search-index";
    }
}