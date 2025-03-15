<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Framework\Controller;
use Loupe\Loupe\Loupe;
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