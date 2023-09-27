<?php

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Model\ArticleQuery;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var Request $request */
/** @var UrlGenerator $urlGenerator */
/** @var CurrentSite $currentSite */
/** @var CurrentUser $currentUser */

/**
 * @throws InvalidDateFormatException
 * @throws PropelException
 */
return function (
    Request      $request,
    UrlGenerator $urlGenerator,
    CurrentSite  $currentSite,
    CurrentUser  $currentUser
): Response|RedirectResponse
{
    $fm = new FileManager();

    $request->attributes->set("page_title", "Ma bibliothèque numérique");

    $content = '
        <h2>Ma bibliothèque numérique</h2>
        <p>Ci-dessous, vous retrouverez la liste de tous les livres numériques achetés sur notre plateforme. Vous pouvez les télécharger &agrave; volonté et dans le format qui vous convient.</p>
        <p>Besoin d\'aide ? Découvrez <a href="https://www.biblys.fr/pages/doc_ebooks" target="_blank">comment télécharger et lire des livres numériques</a>.</p>
    ';

    $ebooks = NULL;

    if ($currentSite->getSite()->getpublisherId() !== null) {

        $excerpt = EntityManager::prepareAndExecute("SELECT `a`.`article_id`, `article_title`, `article_authors`, `article_url`, `article_pubdate`
        FROM `articles` AS `a`
        JOIN `files` AS `f` USING(`article_id`)
        WHERE `publisher_id` = :publisher_id AND `article_preorder` = 1 AND `article_pubdate` > NOW() AND `file_access` = 0
        GROUP BY `article_id`
        ORDER BY `article_pubdate` LIMIT 1",
            ['publisher_id' => $currentSite->getSite()->getpublisherId()]
        );

        if ($e = $excerpt->fetch(PDO::FETCH_ASSOC)) {
            $e['dl_links'] = array();

            $files = $fm->getAll(['article_id' => $e['article_id'], 'file_access' => 0]);
            if ($files) {
                foreach ($files as $f) {
                    $e['dl_links'][] = '
                    <li>
                        <a href="' . $f->getUrl() . '" 
                                title="' . $f->get('version') . ' | ' . file_size($f->get('size')) . ' | ' . $f->getType('name') . '"
                                aria-label="Télécharger au format ' . $f->getType('name') . '">
                            <img src="' . $f->getType('icon') . '" width=16 alt="' . $f->getType('name') . '">
                            ' . $f->get('title') . '
                        </a>
                    </li>
                ';
                }
            }

            $e['cover'] = new Media('article', $e['article_id']);
            if ($e['cover']->exists()) $e['cover'] = '<img src="' . $e['cover']->getUrl(["size" => "h60"]) . '" alt="' . $e['article_title'] . '" height=60>';
            else $e['cover'] = null;

            $ebooks .= '
            <tr>
                <td class="center">' . $e['cover'] . '</td>
                <td style="max-width: 50%">
                    <a href="/' . $e["article_url"] . '">' . $e["article_title"] . '</a> — Extrait gratuit<br />
                    ' . $e["article_authors"] . '<br>
                    À paraître le ' . _date($e['article_pubdate'], 'j f Y') . '
                </td>
                <td class="center" style="width: 125px;">
                    <p><a class="btn btn-primary" href="/' . $e['article_url'] . '">Précommander</a></p>
                    <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-label="Ouvrir le menu de sélection du format">
                        Télécharger <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        ' . implode($e['dl_links']) . '
                    </ul>
                </div>
                </td>
            </tr>
        ';
        }
    }

    $copies = StockQuery::create()
        ->filterBySite($currentSite->getSite())
        ->filterByAxysAccount($currentUser->getAxysAccount())
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

        $update_class = null;
        $download_icon = 'cloud-download';
        if ($copy->getFileUpdated()) {
            $updated++;
            $update_class = ' class="alert alert-info"';
            $download_icon = 'refresh';
        }

        $files = $fm->getAll(['article_id' => $articleEntity->get('id'), 'file_access' => 1]);
        foreach ($files as $f) {
            $download_links[] = '
                <li>
                    <a href="' . $f->getUrl() . '" title="' . $f->get('version') . ' | ' . file_size($f->get('size')) . ' | ' . $f->getType('name') . '"
                            aria-label="Télécharger ' . $f->getType('name') . '">
                        <img src="' . $f->getType('icon') . '" width=16 alt="Télécharger"> ' . $f->getType('name') . '
                    </a>
                </li>
            ';
        }

        // Couverture
        $article_cover = null;
        if ($articleEntity->hasCover()) {
            $article_cover = '<a href="/a/' . $articleEntity->get("url") . '">
                <img src="' . $articleEntity->getCoverUrl(["size" => "h60"]) . '" alt="' . $articleEntity->get('title') . '" />
            </a>';
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

        $ebooks .= '
        <tr' . $update_class . '>
            <td class="center">' . $article_cover . '</td>
            <td style="max-width: 50%">
                 <a href="/' . $articleEntity->get('url') . '">' . $articleEntity->get('title') . '</a><br>' . $articleEntity->get('authors') . '
            </td>
            <td class="center" style="width: 125px;">
                ' . $dl_links . '
            </td>
        </tr>
    ';
    }

    if ($updated) {
        $content .= '
        <br>
        <p class="alert alert-info">
            <span class="fa fa-refresh"></span>&nbsp;
            Certains fichiers ont été mis à jour depuis votre dernier téléchargement.
        </p>
    ';
    }

    $content .= '<br />
    <table class="ebooks">
        <tbody>
            ' . $ebooks . '
        </tbody>
    </table>
';

    $content .= '
    <h3 class="center">
        <a href="https://www.biblys.fr/pages/doc_ebooks">Comment télécharger et lire des livres numériques ?</a>
    </h3>
    <br />
';

    return new Response($content);
};
