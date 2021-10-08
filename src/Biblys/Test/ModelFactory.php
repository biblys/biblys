<?php

namespace Biblys\Test;

use Biblys\Service\Config;
use Model\Publisher;
use Model\Right;
use Model\Session;
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
    public static function createUserSession(User $user = null): Session
    {
        if (!$user) {
            $user = Factory::createUser();
        }

        $session = Session::buildForUser($user);
        $session->save();

        return $session;
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
}