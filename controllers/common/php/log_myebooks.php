<?php

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Model\FileQuery;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request      $request,
    UrlGenerator $urlGenerator,
    CurrentSite  $currentSite,
    CurrentUser  $currentUser,
    TemplateService $templateService,
): Response|RedirectResponse
{
    $request->attributes->set("page_title", "Ma bibliothèque numérique");

    $items = [];

    $libraryItems = StockQuery::create()
        ->filterBySite($currentSite->getSite())
        ->filterByUser($currentUser->getUser())
        ->orderBySellingDate(Criteria::DESC)
        ->find();

    $updated = 0;

    foreach ($libraryItems as $item) {

        $article = $item->getArticle();
        if (!$article || !$article->getType()->isDownloadable()) {
            continue;
        }

        $downloadIcon = 'cloud-download';
        if ($item->getFileUpdated()) {
            $updated++;
            $downloadIcon = 'refresh';
        }

        $downloadableFiles = FileQuery::create()
            ->filterByArticleId($article->getId())
            ->filterByAccess(\Model\File::ACCESS_RESTRICTED)
            ->find()
            ->getData();

        $items[] = [
            "article" => $article,
            "updated" => $item->isFileUpdated(),
            "predownload_is_allowed" => $item->isAllowPredownload(),
            "download_icon" => $downloadIcon,
            "downloadable_files" => $downloadableFiles,
        ];
    }

    return $templateService->renderResponse("AppBundle:Legacy:user_ebooks.html.twig", [
        "updatesAvailable" => $updated > 0,
        "items" => $items,
    ]);
};
