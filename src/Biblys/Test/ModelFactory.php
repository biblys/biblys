<?php

namespace Biblys\Test;

use Biblys\Service\Config;
use DateTime;
use Model\Article;
use Model\ArticleCategory;
use Model\BookCollection;
use Model\Cart;
use Model\Country;
use Model\CrowdfundingCampaign;
use Model\CrowfundingReward;
use Model\Option;
use Model\Link;
use Model\Order;
use Model\Page;
use Model\Payment;
use Model\People;
use Model\Publisher;
use Model\Right;
use Model\Role;
use Model\Session;
use Model\ShippingFee;
use Model\Site;
use Model\SiteQuery;
use Model\Stock;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;

class ModelFactory
{
    /**
     * @throws PropelException
     */
    public static function createAdminUser(Site $site = null): User
    {
        $user = new User();
        $user->save();

        $config = new Config();
        if ($site === null) {
            $site = SiteQuery::create()->findOneById($config->get("site"));
        }

        $right = new Right();
        $right->setUser($user);
        $right->setSite($site);
        $right->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createArticle(
        array $attributes = [],
        Publisher $publisher = null,
        BookCollection $collection = null,
        array $authors = []
    ): Article
    {
        $article = new Article();
        $article->setTitle($attributes["title"] ?? "Article");
        $article->setEan($attributes["ean"] ?? "9781234567890");
        $article->setPrice($attributes["price"] ?? 999);
        $article->setKeywords($attributes["keywords"] ?? $attributes["title"] ?? "Article");

        $publisher = $publisher ?? self::createPublisher();
        $article->setPublisherId($publisher->getId());
        $article->setPublisherName($publisher->getName());

        $collection = $collection ?? self::createCollection();
        $article->setCollectionId($collection->getId());
        $article->setCollectionName($collection->getName());

        $authorNames = array_map(function($author) use($article) {
            self::createContribution($article, $author);
            return $author->getFullName();
        }, $authors);
        $authorsString = implode(", ", $authorNames);
        $article->setAuthors($authorsString);

        $article->save();

        return $article;
    }

    /**
     * @throws PropelException
     */
    public static function createArticleCategory(): ArticleCategory
    {
        $category = new ArticleCategory();
        $category->save();

        return $category;
    }

    /**
     * @throws PropelException
     */
    public static function createCart(
        array $attributes = [],
        Site $site = null,
        User $user = null
    ): Cart
    {
        $cart = new Cart();
        $cart->setUid($attributes["uid"] ?? "cart-uid");
        $cart->setSite($site ?? self::createSite());
        $cart->setUser($user);
        $cart->save();

        return $cart;
    }

    /**
     * @throws PropelException
     */
    public static function createCollection(array $attributes = [], Publisher $publisher = null):
    BookCollection
    {
        $collection = new BookCollection();
        $collection->setName($attributes["name"] ?? "La Blanche");
        $collection->setUrl($attributes["url"] ?? "la-blanche");
        $publisher = $publisher ?? self::createPublisher();
        $collection->setPublisherId($publisher->getId());
        $collection->save();

        return $collection;
    }

    /**
     * @throws PropelException
     */
    public static function createCountry(): Country
    {
        $country = new Country();
        $country->setName("France");
        $country->setCode("FR");
        $country->save();

        return $country;
    }

    /**
     * @throws PropelException
     */
    public static function createCrowdfundingReward($attributes = []): CrowfundingReward
    {
        $article = ModelFactory::createArticle();

        $reward = new CrowfundingReward();
        $reward->setContent("A beautiful reward");
        $reward->setArticles("[{$article->getId()}]");
        $reward->setQuantity($attributes["quantity"] ?? 1);
        $reward->setSiteId($attributes["site_id"] ?? 1);
        $reward->setLimited($attributes["limited"] ?? 1);

        if (!isset($attributes["campaign_id"])) {
            $campaign = ModelFactory::createCrowdfundingCampaign(["site_id" => $attributes["site_id"]]);
            $attributes["campaign_id"] = $campaign->getId();
        }
        $reward->setCampaignId($attributes["campaign_id"]);

        $reward->save();

        return $reward;
    }

    /**
     * @throws PropelException
     */
    public static function createCrowdfundingCampaign($attributes = []): CrowdfundingCampaign
    {
        $campaign = new CrowdfundingCampaign();
        $campaign->setTitle("A beautiful campaign");
        $campaign->setSiteId($attributes["site_id"] ?? 1);
        $campaign->setEnds($attributes["ends"] ?? "2030-01-01");
        $campaign->save();

        return $campaign;
    }

    /**
     * @throws PropelException
     */
    public static function createLink(): Link
    {
        $link = new Link();
        $link->save();

        return $link;
    }

    /**
     * @throws PropelException
     */
    public static function createOrder(array $attributes = [], ?Site $site = null): Order
    {
        $order = new Order();
        $order->setSite($site ?? ModelFactory::createSite());
        $order->setSlug($attributes["slug"] ?? "order-slug");
        $order->save();

        return $order;
    }

    /**
     * @throws PropelException
     */
    public static function createPage(array $attributes = []): Page
    {
        $page = new Page();
        $page->setTitle($attributes["page_title"] ?? "Conditions Générales de Vente");
        $page->setUrl($attributes["page_url"] ?? "cgv");
        $page->setSiteId($attributes["site_id"] ?? 1);
        $page->setStatus($attributes["status"] ?? 1);
        $page->save();

        return $page;
    }

    /**
     * @throws PropelException
     */
    public static function createPayment(
        array $attributes,
        Site $site,
        ?Order $order = null
    ): Payment
    {
        $payment = new Payment();
        $payment->setSite($site);
        $payment->setOrder($order ?? self::createOrder());
        $payment->setAmount($attributes["amount"] ?? 10000);
        $payment->setMode($attributes["mode"] ?? "stripe");
        $payment->setExecuted(
            array_key_exists("executed", $attributes) ? $attributes["executed"] : new DateTime()
        );
        $payment->save();

        return $payment;
    }

    /**
     * @throws PropelException
     */
    public static function createPeople(array $attributes = []): People
    {
        $people = new People();
        $people->setFirstName($attributes["first_name"] ?? "Lili");
        $people->setLastName($attributes["last_name"] ?? "Raton");
        $people->setGender($attributes["gender"] ?? "N");
        $people->setUrl($attributes["slug"] ?? "slug");
        $people->save();

        return $people;
    }

    /**
     * @throws PropelException
     */
    public static function createStockItem(
        array $attributes = [],
        ?Site $site = null,
        ?Article $article = null
    ): Stock
    {
        $stock = new Stock();
        $stock->setSite($site ?? self::createSite());
        $stock->setArticle($article ?? self::createArticle());
        $stock->setSellingDate($attributes["selling_date"] ?? null);
        $stock->setReturnDate($attributes["return_date"] ?? null);
        $stock->setLostDate($attributes["lost_date"] ?? null);
        $stock->save();

        return $stock;
    }

    /**
     * @throws PropelException
     */
    public static function createUser(array $attributes = []): User
    {
        $attributes["email"] = $attributes["email"] ?? "user@biblys.fr";
        $attributes["username"] = $attributes["username"] ?? "User";
        $attributes["password"] = $attributes["password"] ?? "password";

        $userByEmail = UserQuery::create()->findOneByEmail($attributes["email"]);
        if ($userByEmail) {
            return $userByEmail;
        }

        $userByUsername = UserQuery::create()->findOneByUsername($attributes["username"]);
        if ($userByUsername) {
            return $userByUsername;
        }

        $user = new User();
        $user->setEmail($attributes["email"]);
        $user->setUsername($attributes["username"]);
        $user->setPassword(password_hash($attributes["password"], PASSWORD_DEFAULT));

        if (isset($attributes["email_key"])) {
            $user->setEmailKey($attributes["email_key"]);
        }
        $user->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createUserSession(User $user = null): Session
    {
        if (!$user) {
            $user = ModelFactory::createUser();
        }

        $session = Session::buildForUser($user);
        $session->save();

        return $session;
    }

    /**
     * @throws PropelException
     */
    public static function createSiteOption($site, $key, $value)
    {
        $option = new Option();
        $option->setSite($site);
        $option->setKey($key);
        $option->setValue($value);
        $option->save();
    }

    /**
     * @throws PropelException
     */
    public static function createPublisherUser(Publisher $publisher): User
    {
        $user = new User();
        $user->save();

        $right = new Right();
        $right->setUser($user);
        $right->setPublisherId($publisher->getId());
        $right->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createShippingFee(array $attributes = []): ShippingFee
    {
        $shippingFee = new ShippingFee();
        $shippingFee->setSiteId($attributes["site_id"] ?? 1);
        $shippingFee->setZone($attributes["zone"] ?? "ALL");
        $shippingFee->setType($attributes["type"] ?? "normal");
        $shippingFee->setMode($attributes["mode"] ?? "Colissimo");
        $shippingFee->setFee($attributes["fee"] ?? 560);
        $shippingFee->setMinAmount($attributes["min_amount"] ?? 0);
        $shippingFee->setMaxWeight($attributes["max_weight"] ?? 1000);
        $shippingFee->setMaxAmount($attributes["max_amount"] ?? 1000);
        $shippingFee->setMaxArticles($attributes["max_articles"] ?? 10);
        $shippingFee->setInfo($attributes["info"] ?? null);
        $shippingFee->save();

        return $shippingFee;
    }

    /**
     * @throws PropelException
     */
    public static function createPublisher($attributes = []): Publisher
    {
        $publisher = new Publisher();
        $publisher->setName($attributes["name"] ?? "Les Éditions Paronymie");
        $publisher->save();

        return $publisher;
    }

    /**
     * @throws PropelException
     */
    public static function createSite(): Site
    {
        $site = new Site();
        $site->save();

        return $site;
    }

    /**
     * @throws PropelException
     */
    public static function createContribution(Article $article, People $contributor): void
    {
        $contribution = new Role();
        $contribution->setArticle($article);
        $contribution->setPeople($contributor);
        $contribution->setJobId(1);
        $contribution->save();
    }

}