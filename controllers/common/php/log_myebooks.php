<?php

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Model\ArticleQuery;
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

    $copies = StockQuery::create()
        ->filterBySite($currentSite->getSite())
        ->filterByUser($currentUser->getAxysAccount())
        ->orderBySellingDate(Criteria::DESC)
        ->find();

    $updated = 0;

    $am = new ArticleManager();
    foreach ($copies as $copy) {

        /** @var Article $articleEntity */
        $articleEntity = $am->getById($copy->getArticleId());
        $article = ArticleQuery::create()->findPk($copy->getArticleId());

        if (!$articleEntity || !$articleEntity->isDownloadable()) {
            continue;
        }

        $download_links = [];

        $download_icon = 'cloud-download';
        if ($copy->getFileUpdated()) {
            $updated++;
            $download_icon = 'refresh';
        }

        $files = $fm->getAll(['article_id' => $articleEntity->get('id'), 'file_access' => 1]);
        foreach ($files as $file) {
            $downloadUrl = $urlGenerator->generate('file_download', [
                'id' => $file->get('id'),
                'format' => ltrim($file->getType('ext') ?: ".ext", '.'),
            ]);

            $download_links[] = '
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

        $articleCoverUrl = null;
        if ($articleEntity->hasCover()) {
            $articleCoverUrl = $articleEntity->getCoverUrl(["size" => "h60"]);
        }

        // Liens de téléchargement
        if ($articleEntity->get('pubdate') > date("Y-m-d") && !$copy->getAllowPredownload()) {
            $dl_links = 'A para&icirc;tre<br />le ' . _date($articleEntity->get('pubdate'), 'd/m');
        }
        elseif ($article->isWatermarkable()) {
            $dl_links = '
                <a class="btn btn-primary"
                    href="'.$urlGenerator->generate("article_download_with_watermark", [
                        "id" => $article->getId()
                    ]).'"
                >
                    <span class="fa fa-' . $download_icon . '"></span>
                    &nbsp; Télécharger &nbsp; 
                </a>
            ';
        } elseif (empty($download_links)) {
            $dl_links = 'Aucun fichier disponible';
        } else {
            $dl_links = '
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-label="Ouvrir le menu de sélection du format">
                        <span class="fa fa-' . $download_icon . '"></span>
                        &nbsp; Télécharger &nbsp; 
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        ' . implode($download_links) . '
                    </ul>
                </div>
            ';
        }

        $ebooks[] = [
            "article" => $article,
            "updated" => $copy->isFileUpdated(),
            "articleCoverUrl" => $articleCoverUrl,
            "dlLinks" => $dl_links,
        ];
    }

    return $templateService->renderResponse("AppBundle:Legacy:user_ebooks.html.twig", [
        "updatesAvailable" => $updated > 0,
        "ebooks" => $ebooks,
    ]);
};
