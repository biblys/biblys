<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\ImageQuery;
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

        $articles = ImageQuery::create()
            ->filterByType("cover")
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
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

        $totalCount = $articles["count"] + $stockItems["count"];
        $totalSize = $articles["size"] + $stockItems["size"];

        return $templateService->renderResponse("AppBundle:Maintenance:disk-usage.html.twig", [
            "articlesCount" => $articles["count"],
            "articlesSize" => $this->_convertToGigabytes($articles["size"]),
            "stockItemsCount" => $stockItems["count"],
            "stockItemsSize" => $this->_convertToGigabytes($stockItems["size"]),
            "totalCount" => $totalCount,
            "totalSize" => $this->_convertToGigabytes($totalSize),
        ]);
    }

    private function _convertToGigabytes(mixed $bytes): float
    {
        return number_format($bytes / 1073741824, 3);
    }
}
