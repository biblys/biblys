<?php

namespace Biblys\Test;

use Article;
use ArticleManager;
use CFReward;
use CFRewardManager;
use Collection;
use CollectionManager;
use Exception;
use People;
use PeopleManager;
use Publisher;
use PublisherManager;
use Rayon;
use RayonManager;
use Stock;
use StockManager;

class Factory
{

    /**
     * @param array $attributes
     * @param People[]|null $authors
     * @return Article
     * @throws Exception
     */
    public static function createArticle(
        array $attributes = [],
        array $authors = null
    ): Article
    {
        if (!isset($attributes["article_title"])) {
            $attributes["article_title"] = "L'Animalie";
        }

        if (!isset($attributes["collection_id"])) {
            $collection = self::createCollection();
            $attributes["collection_id"] = $collection->get("id");
        }

        if (!isset($attributes["publisher_id"])) {
            $collection = self::createPublisher();
            $attributes["publisher_id"] = $collection->get("publisher_id");
        }

        $am = new ArticleManager();
        $article = $am->create($attributes);

        if ($authors === null) {
            $authors = [self::createPeople()];
        }
        foreach ($authors as $author) {
            $article->addContributor($author, 1);
        }

        return $article;
    }

    /**
     *
     * @return People
     * @throws Exception
     */
    public static function createPeople(): People
    {
        $pm = new PeopleManager();

        $attributes = [
            "people_first_name" => "Hervé",
            "people_last_name" => "LE TERRIER",
        ];

        $people = $pm->get($attributes);
        if ($people) {
            return $people;
        }

        return $pm->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Stock
     * @throws Exception
     */
    public static function createStock(array $attributes = []): Stock
    {
        if (!isset($attributes["article_id"])) {
            $article = self::createArticle();
            $attributes["article_id"] = $article->get("id");
        }

        $sm = new StockManager();
        return $sm->create($attributes);
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public static function createCollection(): Collection
    {
        $cm = new CollectionManager();

        $collection = $cm->get(["collection_name" => "La Blanche"]);
        if ($collection) {
            return $collection;
        }

        $publisher = self::createPublisher();
        return $cm->create([
            "collection_name" => "La Blanche",
            "publisher_id" => $publisher->get("id")
        ]);
    }

    /**
     * @return Publisher
     * @throws Exception
     */
    public static function createPublisher(): Publisher
    {
        $pm = new PublisherManager();

        $publisher = $pm->get(["publisher_name" => "Paronymie"]);
        if ($publisher) {
            return $publisher;
        }

        return $pm->create(["publisher_name" => "Paronymie"]);
    }

    /**
     * @param array $attributes
     * @return Rayon
     */
    public static function createRayon(array $attributes = []): Rayon
    {
        $rm = new RayonManager();

        if (!isset($attributes["rayon_name"])) {
            $attributes["rayon_name"] = "Science Fiction";
        }

        $rayon = $rm->get($attributes);
        if ($rayon) {
            return $rayon;
        }

        return $rm->create($attributes);
    }

    /**
     * @return CFReward
     * @throws Exception
     */
    public static function createCrowfundingReward(): CFReward
    {
        $cfrm = new CFRewardManager();

        $article = self::createArticle();
        return $cfrm->create([
            "reward_articles"=> "[".$article->get("id")."]",
        ]);
    }
}