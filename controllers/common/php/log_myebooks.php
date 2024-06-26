<?php

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
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
    $fm = new FileManager();

    $request->attributes->set("page_title", "Ma bibliothèque numérique");

    $ebooks = [];

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

        $downloadLinks = [];

        $downloadIcon = 'cloud-download';
        if ($item->getFileUpdated()) {
            $updated++;
            $downloadIcon = 'refresh';
        }

        $files = $fm->getAll(['article_id' => $article->getId(), 'file_access' => 1]);
        foreach ($files as $file) {
            $downloadUrl = $urlGenerator->generate('file_download', [
                'id' => $file->get('id'),
                'format' => ltrim($file->getType('ext') ?: ".ext", '.'),
            ]);

            $downloadLinks[] = '
                <li>
                    <a 
                        href="'.$downloadUrl.'" 
                        title="' . $file->get('version') . ' | ' . file_size($file->get ('size')) . ' | ' . $file->getType('name') . '"
                        aria-label="Télécharger ' . $file->getType('name') . '"
                    >
                        <img src="' . $file->getType('icon') . '" width=16 alt="Télécharger"> ' . $file->getType('name') . '
                    </a>
                </li>
            ';
        }

        if (!$article->isPublished() && !$item->isAllowPredownload()) {
            $downloadButton = 'A paraître<br />le ' . _date($article->getPubdate(), 'd/m');
        }
        elseif ($article->isWatermarkable()) {
            $downloadButton = '
                <a class="btn btn-primary"
                    href="'.$urlGenerator->generate("article_download_with_watermark", [
                        "id" => $article->getId()
                    ]).'"
                >
                    <span class="fa fa-' . $downloadIcon . '"></span>
                    &nbsp; Télécharger &nbsp; 
                </a>
            ';
        } elseif (empty($downloadLinks)) {
            $downloadButton = 'Aucun fichier disponible';
        } else {
            $downloadButton = '
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-label="Ouvrir le menu de sélection du format">
                        <span class="fa fa-' . $downloadIcon . '"></span>
                        &nbsp; Télécharger &nbsp; 
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        ' . implode($downloadLinks) . '
                    </ul>
                </div>
            ';
        }

        $ebooks[] = [
            "article" => $article,
            "updated" => $item->isFileUpdated(),
            "dlLinks" => $downloadButton,
        ];
    }

    return $templateService->renderResponse("AppBundle:Legacy:user_ebooks.html.twig", [
        "updatesAvailable" => $updated > 0,
        "ebooks" => $ebooks,
    ]);
};
