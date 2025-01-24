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
use Biblys\Service\CurrentSite;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Mailer;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminsControllerTest extends TestCase
{
    /* Add */

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function testAddAction(): void
    {
        // given
        $controller = new AdminsController();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->newAction($templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    /* Create */

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateAction(): void
    {
        // given
        $controller = new AdminsController();
        $site = ModelFactory::createSite();
        ModelFactory::createUser(site: $site, email: "user@example.org");

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->shouldReceive("parse")->with(["user_email" => ["type" => "string"]]);
        $bodyParams->shouldReceive("get")->with("user_email")->andReturn("user@example.org");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->with("admins_new")->andReturn("/admin/admins");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->with(
            "success",
            "Un accès administrateur a été ajouté pour le compte user@example.org."
        );
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->once();

        // when
        $response = $controller->createAction($bodyParams, $currentSite, $urlGenerator, $flashMessages, $mailer);

        // then
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
