<?php

namespace Biblys\Test;

use Model\Publisher;
use Model\User;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

class RequestFactory
{

    /**
     * @throws PropelException
     */
    public static function createAuthRequest(
        string $content = "",
        User   $user = null,
        string $authMethod = "cookie"
    ): Request
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
     * @deprecated createAuthRequestForAdminUser is deprecated. Stub CurrentUserService->authAdmin
     * instead.
     *
     * @throws PropelException
     */
    public static function createAuthRequestForAdminUser(string $content = ""): Request
    {
        $adminUser = ModelFactory::createAdminUser();
        return RequestFactory::createAuthRequest($content, $adminUser);
    }

    /**
     * @throws PropelException
     */
    public static function createAuthRequestForPublisherUser(Publisher $publisher = null, string $content = ""): Request
    {
        if ($publisher === null) {
            $publisher = ModelFactory::createPublisher();
        }

        $publisherUser = ModelFactory::createPublisherUser(publisher: $publisher);
        return RequestFactory::createAuthRequest($content, $publisherUser);
    }
}