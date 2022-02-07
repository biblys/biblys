<?php

namespace Biblys\Test;

use Biblys\Service\Config;
use Model\Article;
use Model\Country;
use Model\Page;
use Model\People;
use Model\Publisher;
use Model\Right;
use Model\Role;
use Model\Session;
use Model\ShippingFee;
use Model\Site;
use Model\SiteQuery;
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

    public static function createPeople(array $attributes = []): People
    {
        $people = new People();
        $people->setGender($attributes["gender"] ?? "N");
        $people->save();

        return $people;
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
        $shippingFee->setMaxWeight($attributes["max_weight"] ?? 1000);
        $shippingFee->setMaxAmount($attributes["max_amount"] ?? 1000);
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