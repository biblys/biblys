<?php

namespace Biblys\Test;

use Article;
use ArticleManager;
use CFReward;
use CFRewardManager;
use Collection;
use CollectionManager;
use Exception;
use Publisher;
use PublisherManager;
use Stock;
use StockManager;

class Factory
{
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
        $article = Factory::createArticle();
        return $sm->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Article
     * @throws Exception
     */
    public static function createArticle(array $attributes = []): Article
    {
        if (!isset($attributes["collection_id"])) {
            $collection = self::createCollection();
            $attributes["collection_id"] = $collection->get("id");
        }
        $am = new ArticleManager();
        return $am->create($attributes);
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
     * @param Article $article
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