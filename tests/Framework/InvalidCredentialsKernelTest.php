<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace Framework;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;

require_once __DIR__."/../setUp.php";

class InvalidCredentialsKernelTest extends TestCase
{
    public function testBadlyEncodedUserUidCookieIsIgnored(): void
    {
        // given
        $request = Request::create("https://localhost/contact/");
        $request->cookies->set("user_uid", mb_convert_encoding("café", "ISO-8859-1", "UTF-8"));

        // when
        $response = $this->handleThroughKernel($request);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testBadlyEncodedVisitorUidCookieIsIgnored(): void
    {
        // given
        $request = Request::create("https://localhost/contact/");
        $request->cookies->set("visitor_uid", mb_convert_encoding("café", "ISO-8859-1", "UTF-8"));

        // when
        $response = $this->handleThroughKernel($request);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function handleThroughKernel(Request $request): Response
    {
        LegacyCodeHelper::setGlobalSite(ModelFactory::createSite());
        $request->setSession(new Session(new MockArraySessionStorage()));

        $container = include __DIR__."/../../src/container.php";
        $container->register("listener.error", ErrorListener::class)
            ->setArguments(["AppBundle\Controller\ErrorController::exception"]);
        $container->getDefinition("dispatcher")
            ->addMethodCall("addSubscriber", [new Reference("listener.error")]);

        return $container->get("framework")->handle($request);
    }
}
