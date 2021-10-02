<?php

namespace Biblys\Test;

use Article;
use ArticleManager;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use CFReward;
use CFRewardManager;
use Collection;
use CollectionManager;
use Exception;
use Model\Role;
use Model\Session;
use Model\ShippingFee;
use Model\Site;
use Model\User;
use Model\Right;
use Model\SiteQuery;
use Model\UserQuery;
use People;
use PeopleManager;
use Propel\Runtime\Exception\PropelException;
use Publisher;
use PublisherManager;
use Rayon;
use RayonManager;
use Stock;
use StockManager;
use Symfony\Component\HttpFoundation\Request;

class EntityFactory
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
            $contribution = new Role();
            $contribution->setArticleId($article->get('id'));
            $contribution->setPeopleId($author->get('id'));
            $contribution->setJobId(1);
            $contribution->save();
        }

        return $article;
    }

    /**
     *
     * @return People
     * @throws Exception
     */
    public static function createPeople($attributes = []): People
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
            $user = EntityFactory::createUser();
        }

        $session = Session::buildForUser($user);
        $session->save();

        return $session;
    }

    /**
     * @throws PropelException
     */
    public static function createAuthRequest(
        string $content = "",
        User $user = null,
        string $authMethod = "cookie"): Request
    {
        $session = EntityFactory::createUserSession($user);
        $request = Request::create("", "", [], [], [], [], $content);

        if ($authMethod === "cookie") {
            $request->cookies->set("user_uid", $session->getToken());
        }

        if ($authMethod === "header") {
            $request->headers->set("AuthToken", $session->getToken());
        }

        return $request;
    }

    /**
     * @throws PropelException
     */
    public static function createShippingFee(): ShippingFee
    {
        $shippingFee = new ShippingFee();
        $shippingFee->setSiteId(1);
        $shippingFee->save();

        return $shippingFee;
    }

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
     * @param string $content
     * @return Request
     * @throws PropelException
     */
    public static function createAuthRequestForAdminUser(string $content = ""): Request
    {
        $adminUser = EntityFactory::createAdminUser();
        return EntityFactory::createAuthRequest($content, $adminUser);
    }

    public static function createSite()
    {
        $site = new Site();
        $site->save();

        return $site;
    }
}