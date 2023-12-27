<?php

namespace Biblys\Test;

use Biblys\Article\Type;
use Biblys\Service\Config;
use DateTime;
use Model\Article;
use Model\ArticleCategory;
use Model\AxysAccount;
use Model\AxysAccountQuery;
use Model\Invitation;
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
use Propel\Runtime\Exception\PropelException;

class ModelFactory
{
    /**
     * @throws PropelException
     */
    public static function createAdminAxysAccount(Site $site = null): AxysAccount
    {
        $user = new AxysAccount();
        $user->save();

        $config = Config::load();
        if ($site === null) {
            $site = SiteQuery::create()->findOneById($config->get("site"));
        }

        $right = new Right();
        $right->setAxysAccount($user);
        $right->setSite($site);
        $right->setCurrent(true);
        $right->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createArticle(
        string $title = "Article",
        array $authors = [],
        string $ean = "9781234567890",
        string $url = "author/article",
        int $price = 999,
        int $typeId = Type::BOOK,
        string $keywords = null,
        string $lemoninkMasterId = null,
        Publisher $publisher = null,
        BookCollection $collection = null,
    ): Article
    {
        $article = new Article();
        $article->setTitle($title);
        $article->setEan($ean);
        $article->setUrl($url);
        $article->setPrice($price);
        $article->setKeywords($keywords ?? $title);
        $article->setTypeId($typeId);
        $article->setLemonInkMasterId($lemoninkMasterId);

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
    public static function createArticleCategory(Site $site): ArticleCategory
    {
        $category = new ArticleCategory();
        $category->setName("Rayon de lune");
        $category->setSite($site);
        $category->save();

        return $category;
    }

    /**
     * @throws PropelException
     */
    public static function createCart(
        array $attributes = [],
        Site $site = null,
        AxysAccount $user = null
    ): Cart
    {
        $cart = new Cart();
        $cart->setUid($attributes["uid"] ?? "cart-uid");
        $cart->setSite($site ?? self::createSite());
        $cart->setAxysAccount($user);
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
    public static function createCountry($name = "France"): Country
    {
        $country = new Country();
        $country->setName($name);
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
            $campaign = ModelFactory::createCrowdfundingCampaign(
                ["site_id" => $attributes["site_id"] ?? 1]
            );
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
    public static function createOrder(
        Site $site = null,
        string $slug = null,
    ): Order
    {
        $order = new Order();
        $order->setSite($site ?? ModelFactory::createSite());
        $order->setSlug($slug ?? "order-slug");
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
        $page->setContent($attributes["content"] ?? "Veuillez lire attentivement le texte suivant.");
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
        ?Site        $site = null,
        ?Article     $article = null,
        ?AxysAccount $axysAccount = null,
        DateTime     $sellingDate = null,
        DateTime     $returnDate = null,
        DateTime     $lostDate = null,
        string       $lemoninkTransactionId = null,
        string       $lemoninkTransactionToken = null,
    ): Stock
    {
        $stock = new Stock();
        $stock->setSite($site ?? self::createSite());
        $stock->setArticle($article ?? self::createArticle());
        $stock->setAxysAccount($axysAccount);
        $stock->setSellingDate($sellingDate);
        $stock->setReturnDate($returnDate);
        $stock->setLostDate($lostDate);
        $stock->setLemonInkTransactionId($lemoninkTransactionId);
        $stock->setLemonInkTransactionToken($lemoninkTransactionToken);
        $stock->save();

        return $stock;
    }

    /**
     * @throws PropelException
     */
    public static function createAxysAccount(
        string $email = "user@biblys.fr",
        string $username = null,
        string $password = "password",
        string $emailKey = null
    ): AxysAccount
    {
        $userByEmail = AxysAccountQuery::create()->findOneByEmail($email);
        if ($userByEmail) {
            return $userByEmail;
        }

        $user = new AxysAccount();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setEmailKey($emailKey);
        $user->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createUserSession(AxysAccount $user = null): Session
    {
        if (!$user) {
            $user = ModelFactory::createAxysAccount();
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
    public static function createPublisherAxysAccount(Publisher $publisher): AxysAccount
    {
        $user = new AxysAccount();
        $user->save();

        $right = new Right();
        $right->setAxysAccount($user);
        $right->setPublisherId($publisher->getId());
        $right->setCurrent(true);
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
    public static function createPublisher(
        string $name = "Les Éditions Paronymie",
        string $url = "les-editions-paronymie.com",
    ): Publisher
    {
        $publisher = new Publisher();
        $publisher->setName($name);
        $publisher->setUrl($url);
        $publisher->save();

        return $publisher;
    }

    /**
     * @throws PropelException
     */
    public static function createSite(array $attributes = []): Site
    {
        $site = new Site();
        $site->setName($attributes["name"] ?? "paronymie");
        $site->setTitle($attributes["title"] ?? "Éditions Paronymie");
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

    /**
     * @throws PropelException
     */
    public static function createInvitation(
        Site        $site = null,
        array       $articles = [],
        string      $email = "invited-user@biblys.fr",
        string      $code = "ABCD1234",
        bool        $allowsPreDownload = false,
        DateTime    $expiresAt = null,
        DateTime    $consumedAt = null,
    ): Invitation
    {


        $invitation = new Invitation();
        $invitation->setSite($site ?? self::createSite());
        $invitation->setEmail($email);
        $invitation->setCode($code);
        $invitation->setAllowsPreDownload($allowsPreDownload);
        $invitation->setExpiresAt($expiresAt ?? strtotime("+1 month"));
        $invitation->setConsumedAt($consumedAt);

        if (count($articles) === 0) {
            $articles = [self::createArticle()];
        }
        foreach ($articles as $article) {
            $invitation->addArticle($article);
        }

        $invitation->save();

        return $invitation;
    }

}