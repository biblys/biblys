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


namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class OrderControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function testIndexAction(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");
        $templateService = Helpers::getTemplateService();
        $config = Mockery::mock(Config::class);
        $config->expects("isMondialRelayEnabled")->andReturn(false);

        // when
        $response = $controller->indexAction($request, $currentUser, $config, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Commandes web", $response->getContent());
        $this->assertStringNotContainsString("Exporter pour Mondial Relay", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexActionWithMondialRelayEnabled(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");
        $templateService = Helpers::getTemplateService();
        $config = Mockery::mock(Config::class);
        $config->expects("isMondialRelayEnabled")->andReturn(true);

        // when
        $response = $controller->indexAction($request, $currentUser, $config, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Commandes web", $response->getContent());
        $this->assertStringContainsString("Export Mondial Relay", $response->getContent());
    }

    /**
     * @throws PropelException
     */
    public function testShowForAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder(site: $site);
        $controller = new OrderController();
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->show($currentSite, $currentUser, $order->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/order/order-slug", $response->getTargetUrl());
    }
}
