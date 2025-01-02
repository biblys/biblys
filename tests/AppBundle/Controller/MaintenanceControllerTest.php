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

use Biblys\Service\CacheService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\FileQuery;
use Model\ImageQuery;
use Model\MediaFileQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../../tests/setUp.php";

class MaintenanceControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        FileQuery::create()->deleteAll();
        ImageQuery::create()->deleteAll();
        MediaFileQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function testDiskUsageAction(): void
    {
        // given
        $controller = new MaintenanceController();

        $site = ModelFactory::createSite();
        ModelFactory::createImage(type: 'cover', fileSize: 99999999);
        ModelFactory::createImage(site: $site, type: 'photo', fileSize: 99999999);
        ModelFactory::createImage(type: 'other', fileSize: 99999999);
        ModelFactory::createImage(
            post: ModelFactory::createPost(site: $site), site: $site, type: 'illustration', fileSize: 99999999
        );
        ModelFactory::createImage(
            event: ModelFactory::createEvent(site: $site), site: $site, type: 'illustration', fileSize: 99999999
        );
        ModelFactory::createImage(site: $site, type: 'logo', fileSize: 99999999);
        ModelFactory::createImage(site: $site, type: 'portrait', fileSize: 99999999);
        ModelFactory::createMediaFile(site: $site, fileSize: 99999999);
        ModelFactory::createDownloadableFile(fileSize: 99999999);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturns($site);
        $currentSite->expects("getOption")->andReturns(null);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->diskUsageAction($currentUser, $currentSite, $templateService);

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $currentUser->shouldHaveReceived("authAdmin")->withNoArgs();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('<tbody>
      <tr>
        <td>Articles (images de couverture)</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Contributeur·ices (portrait)</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Éditeurs (logos)</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Exemplaires d’occasion (photos)</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Billets de blog (illustrations)</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Évènements (illustrations)</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Médias</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
      <tr>
        <td>Fichiers téléchargeables</td>
        <td class="text-right">1</td>
        <td class="text-right">0.093 Go</td>
      </tr>
    </tbody>', $response->getContent());
    }

    /**
     * @throws Exception
     */
    public function testCacheAction(): void
    {
        // given
        $controller = new MaintenanceController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->cacheAction($currentUser, $templateService);

        // then
        $this->assertStringContainsString("Cache", $response->getContent());
    }

    /**
     * @throws Exception
     */
    public function testEmptyCacheAction(): void
    {
        // given
        $controller = new MaintenanceController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $cacheService = Mockery::mock(CacheService::class);
        $cacheService->expects("clear")->andReturns();
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")->with("success", "Le cache a été vidé.")->andReturns();
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("maintenance_cache")->andReturns("/cache");

        // when
        $response = $controller->emptyCacheAction($currentUser, $cacheService, $flashMessagesService, $urlGenerator);

        // then
        $cacheService->shouldHaveReceived("clear");
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/cache", $response->getTargetUrl());
    }
}
