<?php

namespace Biblys\Test;

use Article;
use ArticleManager;
use Cart;
use CartManager;
use CFCampaignManager;
use CFReward;
use CFRewardManager;
use Collection;
use CollectionManager;
use Country;
use CountryManager;
use Exception;
use Model\ArticleQuery;
use Model\PeopleQuery;
use Order;
use OrderManager;
use People;
use PeopleManager;
use Propel\Runtime\Exception\PropelException;
use Publisher;
use PublisherManager;
use Rayon;
use RayonManager;
use Shipping;
use ShippingManager;
use Site;
use SiteManager;
use Stock;
use StockManager;

class EntityFactory
{
    /**
     * @throws PropelException
     */
    public static function createArticle(
        array $attributes = [],
        array $authors = null,
        string $title = null,
    ): Article
    {
        $am = new ArticleManager();

        if (!isset($attributes["article_title"])) {
            $attributes["article_title"] = $title ?? "L'Animalie";
        }

        if (!isset($attributes["collection_id"])) {
            $collection = self::createCollection();
            $attributes["collection_id"] = $collection->get("id");
        }

        if (!isset($attributes["publisher_id"])) {
            $publisher = self::createPublisher();
            $attributes["publisher_id"] = $publisher->get("publisher_id");
        }

        if (!isset($attributes["type_id"])) {
            $attributes["type_id"] = 1;
        }

        $attributes["article_number"] = $attributes["article_number"] ?? "19";
        $attributes["article_price"] = $attributes["article_price"] ?? "999";

        $article = $am->create($attributes);

        if ($authors === null) {
            $authors = [self::createPeople()];
        }
        foreach ($authors as $author) {
            self::createContribution($article, $author);
        }

        return $article;
    }


    public static function createCart(array $attributes = []): Cart
    {
        $cm = new CartManager();
        return $cm->create([
            "site_id" => $attributes["site_id"] ?? 1,
        ]);
    }

    /**
     * @throws Exception
     */
    public static function createCountry(): Country
    {
        $cm = new CountryManager();
        return $cm->create([
            "country_name" => "France",
            "country_code" => "FR",
            "shipping_zone" => "ALL",
        ]);
    }

    /**
     * @throws Exception
     */
    public static function createOrder(array $attributes = []): Order
    {
        $om = new OrderManager();
        $order = $om->create([
            "order_email" => "customer@example.net",
            "order_firstname" => "Alec",
            "reward_id" => $attributes["reward_id"] ?? null,
        ]);

        $country = self::createCountry();
        $order->set("country_id", $country->get("id"));

        return $order;
    }

    /**
     *
     * @param array $attributes
     * @return People
     * @throws Exception
     */
    public static function createPeople(array $attributes = []): People
    {
        $pm = new PeopleManager();

        $attributes["people_first_name"] = $attributes["people_first_name"] ?? "Hervé";
        $attributes["people_last_name"] = $attributes["people_last_name"] ?? "LE TERRIER";
        $attributes["people_gender"] = $attributes["people_gender"] ?? "F";

        $people = $pm->get($attributes);
        if ($people) {
            return $people;
        }

        return $pm->create($attributes);
    }

    /**
     * @throws Exception
     */
    public static function createShipping($attributes = []): Shipping
    {
        $shipping = ModelFactory::createShippingFee($attributes);
        $sm = new ShippingManager();
        return $sm->getById($shipping->getId());
    }

    /**
     * @throws Exception
     */
    public static function createSite(): Site
    {
        $sm = new SiteManager();
        return $sm->create([
            "site_title" => "Librairie Ys",
            "site_tag" => "YS",
            "site_contact" => "contact@librys.fr",
        ]);
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

        $attributes["stock_conditon"] = "Neuf";
        $attributes["stock_stockage"] = "Paris";
        $attributes["pub_year"] = "2019";
        $attributes["stock_selling_price"] = $attributes["stock_selling_price"] ?? "1899";

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
     * @throws PropelException
     */
    public static function createCrowdfundingCampaign($attributes = [])
    {
        $modelCampaign = ModelFactory::createCrowdfundingCampaign($attributes);
        $cm = new CFCampaignManager();
        return $cm->getById($modelCampaign->getId());
    }

    /**
     * @param array $attributes
     * @return CFReward
     * @throws PropelException
     */
    public static function createCrowdfundingReward(array $attributes = []): CFReward
    {
        $modelReward = ModelFactory::createCrowdfundingReward($attributes);
        $rm = new CFRewardManager();
        return $rm->getById($modelReward->getId());
    }

    /**
     * @throws PropelException
     */
    public static function createContribution(Article $article, People $contributor): void
    {
        $article = ArticleQuery::create()->findPk($article->get("id"));
        $contributor = PeopleQuery::create()->findPk($contributor->get("id"));
        ModelFactory::createContribution($article, $contributor);
    }
}
