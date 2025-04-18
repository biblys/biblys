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


namespace Biblys\Service;

use Biblys\Test\Helpers;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class TemplateServiceTest extends TestCase
{

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws PropelException
     * @throws SyntaxError
     */
    public function testRenderFromString()
    {
        // given
        $config = new Config();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $request = new Request();
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $response = $templateService->renderResponseFromString(
            "Hello <b>{{ name }}</b>!",
            ["name" => "World"]
        );

        // then
        $this->assertEquals(
            "Hello <b>World</b>!",
            $response->getContent(),
        );
    }

    /** renderResponse */

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function testRenderResponse()
    {
        // given
        $templateService = Helpers::getTemplateService();

        // when
        $response = $templateService->renderResponse("AppBundle:Main:home.html.twig");

        // then
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("no-cache, private", $response->headers->get("Cache-Control"));
        $this->assertStringContainsString("Bienvenue", $response->getContent());
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function testRenderResponseWhenIsPrivateIsTrue()
    {
        // given
        $templateService = Helpers::getTemplateService();

        // when
        $response = $templateService->renderResponse("AppBundle:Main:home.html.twig", isPrivate: true);

        // then
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("no-store, private", $response->headers->get("Cache-Control"));
        $this->assertStringContainsString("Bienvenue", $response->getContent());
    }

    /** renderResponseFromString */

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function testRenderResponseFromString()
    {
        // given
        $templateService = Helpers::getTemplateService();

        // when
        $response = $templateService->renderResponseFromString(
            "Hello <b>{{ name }}</b>!",
            ["name" => "World"]
        );

        // then
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("Hello <b>World</b>!", $response->getContent());
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function testRenderResponseFromStringWithIsPrivate()
    {
        // given
        $templateService = Helpers::getTemplateService();

        // when
        $response = $templateService->renderResponseFromString(
            "Hello <b>{{ name }}</b>!",
            ["name" => "World"],
            isPrivate: true
        );

        // then
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("no-store, private", $response->headers->get("Cache-Control"));
        $this->assertEquals("Hello <b>World</b>!", $response->getContent());
    }
}
