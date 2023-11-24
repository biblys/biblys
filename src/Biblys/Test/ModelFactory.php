<?php

namespace Biblys\Test;

use Biblys\Article\Type;
use Biblys\Service\Config;
use Biblys\Service\Slug\SlugService;
use DateTime;
use Exception;
use Model\Article;
use Model\ArticleCategory;
use Model\AuthenticationMethod;
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
use Model\SpecialOffer;
use Model\Stock;
use Model\User;
use Propel\Runtime\Exception\PropelException;

class ModelFactory
{
    /**
     * @throws PropelException
     * @throws Exception
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
        string         $title = "Article",
        array          $authors = [],
        string         $ean = "9781234567890",
        string         $url = "author/article",
        int            $price = 999,
        int            $typeId = Type::BOOK,
        string         $keywords = null,
        string         $lemoninkMasterId = null,
        Publisher      $publisher = null,
        BookCollection $collection = null,
        bool           $isPriceEditable = false,
        DateTime       $publicationDate = null,
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
        $article->setPriceEditable($isPriceEditable);
        $article->setPubdate($publicationDate);

        $publisher = $publisher ?? self::createPublisher();
        $article->setPublisherId($publisher->getId());
        $article->setPublisherName($publisher->getName());

        $collection = $collection ?? self::createCollection();
        $article->setCollectionId($collection->getId());
        $article->setCollectionName($collection->getName());

        $authorNames = array_map(function ($author) use ($article) {
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
    public static function createArticleCategory(
        Site   $site,
        string $name = "Rayon de lune",
    ): ArticleCategory
    {
        $category = new ArticleCategory();
        $category->setName($name);
        $category->setSite($site);
        $category->save();

        return $category;
    }

    /**
     * @param int $amount
     * @throws PropelException
     */
    public static function createCart(
        Site        $site = null,
        User $user = null,
        string      $uniqueId = null,
        int         $amount = 0,
        int         $count = 0,
    ): Cart
    {
        $cart = new Cart();
        $cart->setUid($uniqueId ?? "cart-uid");
        $cart->setSite($site ?? self::createSite());
        $cart->setUser($user);
        $cart->setAmount($amount);
        $cart->setCount($count);
        $cart->save();

        return $cart;
    }

    /**
     * @throws PropelException
     */
    public static function createCollection(
        Publisher $publisher = null,
        string $name = "La Blanche",
    ): BookCollection
    {
        $slugService = new SlugService();

        $collection = new BookCollection();
        $collection->setName($name);
        $collection->setUrl($slugService->slugify($name));
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
    public static function createLink(
        Article         $article = null,
        ArticleCategory $articleCategory = null,
    ): Link
    {
        $link = new Link();
        $link->setArticle($article);
        $link->setArticleCategory($articleCategory);
        $link->save();

        return $link;
    }

    /**
     * @throws PropelException
     */
    public static function createOrder(
        Site   $site = null,
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
        array  $attributes,
        Site   $site,
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
     *
     * @deprecated ModelFactory::createPeople is deprecated.
     *             Use ModelFactory::createContributor instead
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
    public static function createContributor(
        string $firstName = "Lili",
        string $lastName = "Raton",
        string $gender = "N",
        string $url = "lili-raton",
    ): People
    {
        $contributor = new People();
        $contributor->setFirstName($firstName);
        $contributor->setLastName($lastName);
        $fullName = trim($contributor->getFirstName() . " " . $contributor->getLastName());
        $contributor->setName($fullName);
        $contributor->setGender($gender);
        $contributor->setUrl($url);
        $contributor->save();

        return $contributor;
    }

    /**
     * @throws PropelException
     */
    public static function createStockItem(
        ?Site        $site = null,
        ?Article     $article = null,
        ?AxysAccount $axysAccount = null,
        ?User        $user = null,
        ?Cart        $cart = null,
        int          $sellingPrice = 0,
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
        $stock->setUser($user);
        $stock->setCart($cart);
        $stock->setCondition("Neuf");
        $stock->setSellingPrice($sellingPrice);
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
    public static function createUserSession(User $user = null): Session
    {
        if (!$user) {
            $user = ModelFactory::createUser();
        }

        $session = new Session();
        $session->setUser($user);
        $session->setToken(Session::generateToken());
        $session->setExpiresAt(new DateTime('tomorrow'));
        $session->save();

        return $session;
    }

    /**
     * @throws PropelException
     */
    public static function createSiteOption($site, $key, $value): void
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
        $site->setContact("contact@paronymie.fr");
        $site->setTag("PAR");
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
        Site     $site = null,
        array    $articles = [],
        string   $email = "invited-user@biblys.fr",
        string   $code = "ABCD1234",
        bool     $allowsPreDownload = false,
        DateTime $expiresAt = null,
        DateTime $consumedAt = null,
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

    /**
     * @throws PropelException
     */
    public static function createUser(
        Site $site = null,
        string $email = "user@biblys.fr",
    ): User
    {
        $user = new User();
        $user->setSite($site ?? self::createSite());
        $user->setEmail($email);
        $user->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createAuthenticationMethod(
        Site $site = null,
        User $user = null,
        string $identityProvider = "axys",
        string $externalId = "AXYS1234",
    ): AuthenticationMethod
    {
        $authenticationMethod = new AuthenticationMethod();
        $authenticationMethod->setSite($site ?? self::createSite());
        $authenticationMethod->setUser($user ?? self::createUser());
        $authenticationMethod->setIdentityProvider($identityProvider);
        $authenticationMethod->setExternalId($externalId);
        $authenticationMethod->save();

        return $authenticationMethod;
    }

    /**
     * @throws PropelException
     */
    public static function createAdminUser(Site $site = null): User
    {
        $site = $site ?? self::createSite();

        $user = self::createUser($site);
        self::createRight(user: $user, site: $site);

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createPublisherUser(
        Site $site = null,
        Publisher $publisher = null,
    ): User
    {
        $site = $site ?? self::createSite();
        $publisher = $publisher ?? self::createPublisher();

        $user = self::createUser($site);
        self::createRight(user: $user, publisher: $publisher);

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createRight(
        User $user,
        Site $site = null,
        Publisher $publisher = null,
    ): Right
    {
        $right = new Right();
        $right->setUser($user);
        $right->setSite($site);
        $right->setPublisher($publisher);
        $right->save();

        return $right;
    }

    /**
     * @throws PropelException
     */
    public static function createSpecialOffer(
        Site $site,
        string $name = "Offre spéciale",
        BookCollection $targetCollection = null,
        Article $freeArticle = null,
        int $targetQuantity = 2,
        DateTime $startDate = new DateTime("- 1 day"),
        DateTime $endDate = new DateTime("+ 1 day"),
    ): SpecialOffer
    {
        $specialOffer = new SpecialOffer();
        $specialOffer->setSite($site);
        $specialOffer->setName($name);
        $specialOffer->setTargetCollection($targetCollection ?? self::createCollection());
        $specialOffer->setTargetQuantity($targetQuantity);
        $specialOffer->setFreeArticle($freeArticle ?? ModelFactory::createArticle());
        $specialOffer->setStartDate($startDate);
        $specialOffer->setEndDate($endDate);
        $specialOffer->save();

        return $specialOffer;
    }

}