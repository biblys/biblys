<?php

namespace AppBundle\Controller;

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
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $articles = ImageQuery::create()
            ->filterByType("cover")
            ->withColumn('SUM(`fileSize`)', 'total')
            ->select(['total'])
            ->find()
            ->getData()[0];

        $total = ImageQuery::create()
            ->withColumn('SUM(`fileSize`)', 'total')
            ->select(['total'])
            ->find()
            ->getData()[0];

        return $templateService->renderResponse("AppBundle:Maintenance:disk-usage.html.twig", [
            "articles" => $this->_convertToGigabytes($articles),
            "total" => $this->_convertToGigabytes($total),
        ]);
    }

    private function _convertToGigabytes(mixed $bytes): float
    {
        return number_format($bytes / 1073741824, 3);
    }
}
