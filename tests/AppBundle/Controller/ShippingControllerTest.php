<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Mockery;
use Payplug\Exception\NotFoundException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShippingControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws Exception
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws \Exception
     */
    public function testOptionsAction()
    {
        // given
        $controller = new ShippingController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->optionsAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws SyntaxError
     * @throws Exception
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \Exception
     */
    public function testCountriesAction()
    {
        // given
        $controller = new ShippingController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->countriesAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("France", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws Exception
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \Exception
     */
    public function testZonesAction()
    {
        // given
        $controller = new ShippingController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->zonesAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("France", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws Exception
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws \Exception
     */
    public function testZoneCountriesAction(): void
    {
        // given
        $controller = new ShippingController();

        $zone = ModelFactory::createShippingZone(name: "Zone 51");
        ModelFactory::createCountry(name: "Nevada", shippingZone: $zone);

        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->zoneCountriesAction($currentUser, $templateService, $zone->getId());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Zone 51", $response->getContent());
        $this->assertStringContainsString("Nevada", $response->getContent());
    }

    /**
     * @throws Exception
     * @throws NotFoundException
     * @throws PropelException
     */
    public function testZoneAddCountryAction(): void
    {
        // given
        $controller = new ShippingController();

        $zone = ModelFactory::createShippingZone(name: "Zone 51");
        $country = ModelFactory::createCountry(name: "Nevada");

        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $bodyParamsService = $this->createMock(BodyParamsService::class);
        $bodyParamsService->expects($this->once())
            ->method("parse")
            ->with(["country_id" => ["type" => "numeric"]]);
        $bodyParamsService->expects($this->once())
            ->method("getInteger")
            ->with("country_id")
            ->willReturn($country->getId());
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")
            ->with("success", "Le pays « Nevada » a été ajouté à la zone « Zone 51 ».");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")
            ->with("shipping_zones_countries", ["id" => $zone->getId()])
            ->andReturn("redirect_url");

        // when
        $response = $controller->zoneAddCountryAction(
            $currentUser, $bodyParamsService, $flashMessagesService, $urlGenerator, $zone->getId()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("redirect_url", $response->getTargetUrl());
        $zoneHasCountry = $zone->getCountries()->contains($country);
        $this->assertTrue($zoneHasCountry, "adds country to the zone");
    }

    /**
     * @throws Exception
     * @throws PropelException
     * @throws NotFoundException
     */
    public function testZoneRemoveCountryAction(): void
    {
        // given
        $controller = new ShippingController();

        $zone = ModelFactory::createShippingZone(name: "Zone 51");
        $country = ModelFactory::createCountry(name: "Nevada", shippingZone: $zone);

        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $bodyParamsService = $this->createMock(BodyParamsService::class);
        $bodyParamsService->expects($this->once())
            ->method("parse")
            ->with(["country_id" => ["type" => "numeric"]]);
        $bodyParamsService->expects($this->once())
            ->method("getInteger")
            ->with("country_id")
            ->willReturn($country->getId());
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")
            ->with("success", "Le pays « Nevada » a été retiré de la zone « Zone 51 ».");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")
            ->with("shipping_zones_countries", ["id" => $zone->getId()])
            ->andReturn("redirect_url");

        // when
        $response = $controller->zoneRemoveCountryAction(
            $currentUser, $bodyParamsService, $flashMessagesService, $urlGenerator, $zone->getId()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("redirect_url", $response->getTargetUrl());
        $zoneHasCountry = $zone->getCountries()->contains($country);
        $this->assertFalse($zoneHasCountry, "removes country from the zone");
    }
}
