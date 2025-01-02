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
use Framework\Controller;
use Model\FileQuery;
use Model\ImageQuery;
use Model\MediaFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MaintenanceController extends Controller
{
    public function infosAction(): JsonResponse
    {
        return new JsonResponse([
            'version' => BIBLYS_VERSION,
        ]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function diskUsageAction(
        CurrentUser     $currentUser,
        CurrentSite     $currentSite,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $articlesQuery = ImageQuery::create()
            ->filterByType("cover")
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
            ->select(['count', 'size']);

        $articles = $articlesQuery->find()->getData()[0];

        $contributors = ImageQuery::create()
            ->filterByType("portrait")
            ->withColumn("COUNT(`id`)", "count")
            ->withColumn("SUM(`fileSize`)", "size")
            ->select(["count", "size"])->find()->getData()[0];

        $publishers = ImageQuery::create()
            ->filterByType("logo")
            ->withColumn("COUNT(`id`)", "count")
            ->withColumn("SUM(`fileSize`)", "size")
            ->select(["count", "size"])->find()->getData()[0];

        $downloadableFiles = FileQuery::create()
            ->withColumn('COUNT(`file_id`)', 'count')
            ->withColumn('SUM(`file_size`)', 'size')
            ->select(['count', 'size'])
            ->find()
            ->getData()[0];

        $stockItems = ImageQuery::create()
            ->filterByType("photo")
            ->filterBySite($currentSite->getSite())
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
            ->select(['count', 'size'])
            ->find()
            ->getData()[0];

        $postIllustrations = ImageQuery::create()
            ->filterByType("illustration")
            ->filterByPostId(null, Criteria::ISNOTNULL)
            ->filterBySite($currentSite->getSite())
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
            ->select(['count', 'size'])
            ->find()
            ->getData()[0];

        $eventIllustrations = ImageQuery::create()
            ->filterByType("illustration")
            ->filterByEventId(null, Criteria::ISNOTNULL)
            ->filterBySite($currentSite->getSite())
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
            ->select(['count', 'size'])
            ->find()
            ->getData()[0];

        $mediaFiles = MediaFileQuery::create()
            ->filterBySiteId($currentSite->getSite()->getId())
            ->withColumn('COUNT(`media_id`)', 'count')
            ->withColumn('SUM(`media_file_size`)', 'size')
            ->select(['count', 'size'])
            ->find()
            ->getData()[0];

        $totalCount = $articles["count"]
            + $contributors["count"]
            + $publishers["count"]
            + $stockItems["count"]
            + $postIllustrations["count"]
            + $eventIllustrations["count"]
            + $mediaFiles["count"]
            + $downloadableFiles["count"];
        $totalSize = $articles["size"]
            + $contributors["size"]
            + $publishers["size"]
            + $stockItems["size"]
            + $postIllustrations["size"]
            + $eventIllustrations["size"]
            + $mediaFiles["size"]
            + $downloadableFiles["size"];

        return $templateService->renderResponse("AppBundle:Maintenance:disk-usage.html.twig", [
            "articlesCount" => $articles["count"],
            "articlesSize" => $this->_convertToGigabytes($articles["size"]),
            "contributorsCount" => $contributors["count"],
            "contributorsSize" => $this->_convertToGigabytes($contributors["size"]),
            "publishersCount" => $publishers["count"],
            "publishersSize" => $this->_convertToGigabytes($publishers["size"]),
            "stockItemsCount" => $stockItems["count"],
            "stockItemsSize" => $this->_convertToGigabytes($stockItems["size"]),
            "postIllustrationsCount" => $postIllustrations["count"],
            "postIllustrationsSize" => $this->_convertToGigabytes($postIllustrations["size"]),
            "eventIllustrationsCount" => $eventIllustrations["count"],
            "eventIllustrationsSize" => $this->_convertToGigabytes($eventIllustrations["size"]),
            "downloadableFilesCount" => $downloadableFiles["count"],
            "downloadableFilesSize" => $this->_convertToGigabytes($downloadableFiles["size"]),
            "mediaFilesCount" => $mediaFiles["count"],
            "mediaFilesSize" => $this->_convertToGigabytes($mediaFiles["size"]),
            "totalCount" => $totalCount,
            "totalSize" => $this->_convertToGigabytes($totalSize),
        ]);
    }

    private function _convertToGigabytes(mixed $bytes): float
    {
        return number_format($bytes / 1073741824, 3);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function cacheAction(CurrentUser $currentUser, TemplateService $templateService): Response
    {
        $currentUser->authAdmin();

        return $templateService->renderResponse("AppBundle:Maintenance:cache.html.twig");
    }
}
