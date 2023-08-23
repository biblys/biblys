<?php

namespace Biblys\Test;

use Model\Publisher;
use Model\AxysAccount;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

class RequestFactory
{

    /**
     * @throws PropelException
     */
    public static function createAuthRequest(
        string      $content = "",
        AxysAccount $user = null,
        string      $authMethod = "cookie"): Request
    {
        $session = ModelFactory::createUserSession($user);
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
     * @param string $content
     * @return Request
     * @throws PropelException
     */
    public static function createAuthRequestForAdminUser(string $content = ""): Request
    {
        $adminUser = ModelFactory::createAdminAxysAccount();
        return RequestFactory::createAuthRequest($content, $adminUser);
    }

    /**
     * @param Publisher|null $publisher
     * @param string|null $content
     * @return Request
     * @throws PropelException
     */
    public static function createAuthRequestForPublisherUser(Publisher $publisher = null, string $content = ""): Request
    {
        if ($publisher === null) {
            $publisher = ModelFactory::createPublisher();
        }

        $publisherUser = ModelFactory::createPublisherAxysAccount($publisher);
        return RequestFactory::createAuthRequest($content, $publisherUser);
    }
}