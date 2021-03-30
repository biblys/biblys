<?php

namespace Biblys\Test;

use Article;
use ArticleManager;
use Collection;
use CollectionManager;
use Exception;
use Publisher;
use PublisherManager;
use StockManager;

class Factory
{
    /**
     * @param string $title
     * @return Article
     * @throws Exception
     */
    public static function createArticle(string $title = "Au revoir Mao"): Article
    {
        $collection = self::createCollection();
        $am  = new ArticleManager();
        return $am->create([
            "article_title" => $title,
            "type_id" => 1,
            "collection_id" => $collection->get("id")
        ]);
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
}