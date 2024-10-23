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

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class StaticPageControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testShowAction()
    {
        // given
        $controller = new StaticPageController();
        ModelFactory::createPage([
            "page_title" => "Mentions légales",
            "page_url" => "mentions-legales",
            "content" => "Merci de lire attentivement le texte suivant."
        ]);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        $currentUser = Mockery::mock(CurrentUser::class);

        // when
        $response = $controller->showAction($currentSite, $currentUser, "mentions-legales");

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "responds with http status 400",
        );
        $this->assertStringContainsString(
            "Mentions légales",
            $response->getContent(),
            "inserts static page title in body"
        );
        $this->assertStringContainsString(
            "Merci de lire attentivement le texte suivant.",
            $response->getContent(),
            "insert static page content in body"
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testShowActionForInvalidSlugs()
    {
        // then
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Cannot find a static page with slug \"page-inexistante\".");

        // given
        $controller = new StaticPageController();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        $currentUser = Mockery::mock(CurrentUser::class);

        // when
        $controller->showAction($currentSite, $currentUser,  "page-inexistante");
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testShowActionForOfflinePages()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Page \"offline-page\" is offline.");

        // given
        $controller = new StaticPageController();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        ModelFactory::createPage(["page_url" => "offline-page", "status" => 0]);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()
            ->with("Page \"offline-page\" is offline.")
            ->andThrow(new AccessDeniedHttpException("Page \"offline-page\" is offline."));

        // when
        $controller->showAction($currentSite, $currentUser, "offline-page");
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testShowActionForOfflinePageViewedByAdmin()
    {
        // given
        $controller = new StaticPageController();
        ModelFactory::createPage(["page_url" => "for-admin-only", "status" => 0]);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->showAction($currentSite, $currentUser, slug: "for-admin-only");

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "responds with http status 200",
        );
        $this->assertStringContainsString(
            "Cette page est hors-ligne et n'est prévisualisable que par les administrateurs.",
            $response->getContent(),
            "inserts admin preview warning"
        );
    }
}
