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
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../../tests/setUp.php";

class MaintenanceControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturns($site);
        $currentSite->expects("getOption")->andReturns(null);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")->andReturns(new Response("response"));

        // when
        $response = $controller->diskUsageAction($currentUser, $currentSite, $templateService);

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $currentUser->shouldHaveReceived("authAdmin")->withNoArgs();
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:Maintenance:disk-usage.html.twig", [
                "articlesCount" => 1,
                "articlesSize" => 0.093,
                "contributorsCount" => 1,
                "contributorsSize" => 0.093,
                "publishersCount" => 1,
                "publishersSize" => 0.093,
                "stockItemsCount" => 1,
                "stockItemsSize" => 0.093,
                "postIllustrationsCount" => 1,
                "postIllustrationsSize" => 0.093,
                "eventIllustrationsCount" => 1,
                "eventIllustrationsSize" => 0.093,
                "downloadableFilesCount" => 0,
                "downloadableFilesSize" => 0.0,
                "mediaFilesCount" => 1,
                "mediaFilesSize" => 0.093,
                "totalCount" => 7,
                "totalSize" => 0.652,
            ]);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("response", $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testDiskUsageActionWithPublisherFilter(): void
    {
        // given
        $controller = new MaintenanceController();

        $articleFromCurrentSite = ModelFactory::createArticle();
        ModelFactory::createImage(article: $articleFromCurrentSite, type: 'cover', fileSize: 99999999);
        $articleFromOtherSite = ModelFactory::createArticle();
        ModelFactory::createImage(article: $articleFromOtherSite, type: 'cover', fileSize: 99999999);
        $site = ModelFactory::createSite();
        ModelFactory::createDownloadableFile(article: $articleFromCurrentSite, fileSize: 99999999);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturns($site);
        $currentSite->expects("getOption")->andReturns($articleFromCurrentSite->getPublisherId());
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")->andReturns(new Response("response"));

        // when
        $response = $controller->diskUsageAction($currentUser, $currentSite, $templateService);

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $currentUser->shouldHaveReceived("authAdmin")->withNoArgs();
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:Maintenance:disk-usage.html.twig", [
                "articlesCount" => 1,
                "articlesSize" => 0.093,
                "contributorsCount" => 1,
                "contributorsSize" => 0.093,
                "publishersCount" => 1,
                "publishersSize" => 0.093,
                "postIllustrationsCount" => 0,
                "postIllustrationsSize" => 0,
                "eventIllustrationsCount" => 0,
                "eventIllustrationsSize" => 0,
                "downloadableFilesCount" => 1,
                "downloadableFilesSize" => 0.093,
                "stockItemsCount" => 0,
                "stockItemsSize" => 0,
                "mediaFilesCount" => 0,
                "mediaFilesSize" => 0,
                "totalCount" => 4,
                "totalSize" => 0.373,
            ]);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("response", $response->getContent());
    }
}
