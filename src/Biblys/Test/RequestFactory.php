<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Test;

use Model\Publisher;
use Model\Site;
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
    public static function createAuthRequestForAdminUser(
        string $content = "",
        Site   $site = null,
    ): Request
    {
        $adminUser = ModelFactory::createAdminUser(site: $site);
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