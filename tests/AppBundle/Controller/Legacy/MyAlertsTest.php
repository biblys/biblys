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


namespace AppBundle\Controller\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\AlertQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . "/../../../setUp.php";

class MyAlertsTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testThrowsExceptionWhenAlertsAreDisabled(): void
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/log_myalerts.php";

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("hasOptionEnabled")->with("alerts")->andReturn(false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $request = new Request();

        // then
        $this->expectException(ResourceNotFoundException::class);

        // when
        $controller($currentSite, $currentUser, $request);
    }

    /**
     * @throws PropelException
     */
    public function testPostCreatesAlertWhenItDoesntExist(): void
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/log_myalerts.php";

        $user = ModelFactory::createUser();
        $article = ModelFactory::createArticle();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("hasOptionEnabled")->with("alerts")->andReturn(true);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $request = Request::create("/pages/log_myalerts", "POST", content: json_encode([
            "article_id" => $article->getId(),
        ]));

        // when
        $response = $controller($currentSite, $currentUser, $request);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "répond avec le statut 200");
        $this->assertEquals(
            '{"created":1}',
            $response->getContent(),
            "retourne created:1 dans le JSON"
        );
        $alert = AlertQuery::create()
            ->filterByUser($user)
            ->filterByArticleId($article->getId())
            ->findOne();
        $this->assertNotNull($alert, "l'alerte a bien été créée en base");
    }

    /**
     * @throws PropelException
     */
    public function testPostDeletesAlertWhenItAlreadyExists(): void
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/log_myalerts.php";

        $user = ModelFactory::createUser();
        $article = ModelFactory::createArticle();
        ModelFactory::createAlert(user: $user, article: $article);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("hasOptionEnabled")->with("alerts")->andReturn(true);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $request = Request::create("/pages/log_myalerts", "POST", content: json_encode([
            "article_id" => $article->getId(),
        ]));

        // when
        $response = $controller($currentSite, $currentUser, $request);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "répond avec le statut 200");
        $this->assertEquals(
            '{"deleted":1}',
            $response->getContent(),
            "retourne deleted:1 dans le JSON"
        );
        $alert = AlertQuery::create()
            ->filterByUser($user)
            ->filterByArticleId($article->getId())
            ->findOne();
        $this->assertNull($alert, "l'alerte a bien été supprimée de la base");
    }

    /**
     * @throws PropelException
     */
    public function testGetWithDelParamDeletesAlertAndRedirects(): void
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/log_myalerts.php";

        $user = ModelFactory::createUser();
        $article = ModelFactory::createArticle();
        $alert = ModelFactory::createAlert(user: $user, article: $article);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("hasOptionEnabled")->with("alerts")->andReturn(true);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $request = new Request();
        $request->query->set("del", $alert->getId());

        // when
        $response = $controller($currentSite, $currentUser, $request);

        // then
        $this->assertEquals(302, $response->getStatusCode(), "redirige avec le statut 302");
        $this->assertEquals(
            "/pages/log_myalerts?deleted=1",
            $response->headers->get("Location"),
            "redirige vers la page des alertes avec le paramètre deleted=1"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetDisplaysAlertsList(): void
    {
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/log_myalerts.php";

        $user = ModelFactory::createUser();
        $article = ModelFactory::createArticle(title: "Le livre surveillé");
        ModelFactory::createAlert(user: $user, article: $article);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("hasOptionEnabled")->with("alerts")->andReturn(true);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $request = new Request();

        // when
        $response = $controller($currentSite, $currentUser, $request);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "répond avec le statut 200");
        $this->assertStringContainsString(
            "Le livre surveillé",
            $response->getContent(),
            "affiche le titre du livre suivi"
        );
    }
}
