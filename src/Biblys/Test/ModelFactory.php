<?php

namespace Biblys\Test;

use Biblys\Service\Config;
use Model\Article;
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
    public static function createPublisher(): Publisher
    {
        $publisher = new Publisher();
        $publisher->save();

        return $publisher;
    }
}