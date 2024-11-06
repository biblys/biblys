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


use Biblys\Service\Config;
use Framework\RequestListener;
use Framework\RouteLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__."/../setUp.php";

class RequestListenerTest extends TestCase
{
    public function testOnUnsecureRequest()
    {
        $config = new Config();
        $config->set("https", true);

        $_SERVER = [
            "HTTP_HOST" => "www.example.org",
            "REQUEST_URI" => "/admin/?id=1&view=full",
            "REQUEST_TIME" => 1616700639,
        ];
        $request = Request::createFromGlobals();
        $container = include __DIR__."/../../src/container.php";
        $routes = RouteLoader::load();

        // when
        $response = $container->get("framework")->handle($request);

        // then
        $this->assertInstanceOf(
            "Symfony\Component\HttpFoundation\RedirectResponse",
            $response
        );
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "https://www.example.org/admin/?id=1&view=full",
            $response->headers->get("Location")
        );
    }
}
