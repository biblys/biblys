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

        $downloadableFiles = FileQuery::create()
            ->filterByArticleId($article->getId())
            ->filterByAccess(\Model\File::ACCESS_RESTRICTED)
            ->find()
            ->getData();
        /** @var \Model\File $file */
        foreach ($downloadableFiles as $file) {
            $downloadUrl = $urlGenerator->generate('file_download', [
                'id' => $file->getId(),
                'format' => ltrim($file->getFileType()->getExtension() ?: ".ext", '.'),
            ]);

            $downloadLinks[] = '
                <li>
                    <a
                        href="'.$downloadUrl.'"
                        title="' . $file->getVersion() . ' | ' . file_size($file->getSize() . ' | ' . $file->getFileType()->getName()) . '"
                        aria-label="Télécharger ' . $file->getFileType()->getName(). '"
                    >
                        <img src="' . $file->getFileType()->getIcon() . '" width=16 alt="Télécharger"> ' . $file->getFileType()->getName() . '
                    </a>
                </li>
            ';
        }

        $ebooks[] = [
            "article" => $article,
            "updated" => $item->isFileUpdated(),
            "predownload_is_allowed" => $item->isAllowPredownload(),
            "download_icon" => $downloadIcon,
            "download_links" => $downloadLinks,
        ];
    }

    return $templateService->renderResponse("AppBundle:Legacy:user_ebooks.html.twig", [
        "updatesAvailable" => $updated > 0,
        "ebooks" => $ebooks,
    ]);
};
