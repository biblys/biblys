<?php

use Biblys\Legacy\LegacyCodeHelper;

$am = new ArticleManager();
    $sm = new StockManager();
    $fm = new FileManager();

	// Add a free article to the library
	// (redirects old route to new controller)
	$add = $request->query->get('add');
	if ($add) {
		$url = $urlgenerator->generate('article_free_download', ["id" => $add]);
        redirect($url);
	}

	$message = null;
	if (isset($_GET['success']))
	{
		$message = '<p class="success">'.htmlentities($_GET['success']).'</p>';
	}
	if (isset($_GET['error']))
	{
		$message = '<p class="error">'.htmlentities($_GET['error']).'</p>';
	}

    \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Ma biblioth&egrave;que numérique');

    $_ECHO .= '
		<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
		'.$message.'
		<p>Ci-dessous, vous retrouverez la liste de tous les livres numériques achetés sur notre plateforme. Vous pouvez les télécharger &agrave; volonté et dans le format qui vous convient.</p>
		<p>Besoin d\'aide ? Découvrez <a href="http://www.biblys.fr/pages/doc_ebooks" target="_blank">comment télécharger et lire des livres numériques</a>.</p>
	';

    $ebooks = NULL;

    if ($_SITE->has('publisher_id')) {

        // Extrait gratuit
        $excerpt = EntityManager::prepareAndExecute("SELECT `a`.`article_id`, `article_title`, `article_authors`, `article_url`, `article_pubdate`
            FROM `articles` AS `a`
            JOIN `files` AS `f` USING(`article_id`)
            WHERE `publisher_id` = :publisher_id AND `article_preorder` = 1 AND `article_pubdate` > NOW() AND `file_access` = 0
            GROUP BY `article_id`
            ORDER BY `article_pubdate` LIMIT 1",
            ['publisher_id' => $_SITE->get('publisher_id')]
        );

        if ($e = $excerpt->fetch(PDO::FETCH_ASSOC)) {
            $e['dl_links'] = array();

            $files = $fm->getAll(['article_id' => $e['article_id'], 'file_access' => 0]);
            if ($files) {
                foreach ($files as $f) {
                    $e['dl_links'][] = '
                        <li>
                            <a href="'.$f->getUrl(LegacyCodeHelper::getGlobalVisitor()['user_key']).'" 
                                    title="'.$f->get('version').' | '.file_size($f->get('size')).' | '.$f->getType('name').'"
                                    aria-label="Télécharger au format '.$f->getType('name').'">
                                <img src="'.$f->getType('icon').'" width=16 alt="'.$f->getType('name').'">
                                '.$f->get('title').'
                            </a>
                        </li>
                    ';
                }
            }

            $e['cover'] = new Media('article', $e['article_id']);
            if ($e['cover']->exists()) $e['cover'] = '<img src="'.$e['cover']->url('h60').'" alt="'.$e['article_title'].'" height=60>';
            else $e['cover'] = null;

            $ebooks .= '
                <tr>
                    <td class="center">'.$e['cover'].'</td>
                    <td style="max-width: 50%">
                        <a href="/'.$e["article_url"].'">'.$e["article_title"].'</a> — Extrait gratuit<br />
                        '.$e["article_authors"].'<br>
                        À paraître le '._date($e['article_pubdate'],'j f Y').'
                    </td>
                    <td width="125" class="center">
                        <p><a class="btn btn-primary" href="/'.$e['article_url'].'">Précommander</a></p>
                        <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                aria-label="Ouvrir le menu de sélection du format">
                            Télécharger <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            '.implode($e['dl_links']).'
                        </ul>
                    </div>
                    </td>
                </tr>
            ';
        }
    }

    $copies = $sm->getAll([
        "axys_account_id" => LegacyCodeHelper::getGlobalVisitor()->get('id')
    ], [
        "order" => "stock_selling_date",
        "sort" => "desc"
    ], true);

    $updated = 0;

    foreach ($copies as $copy) {

        $article = $copy["article"];

        if (!$article || !$article->isDownloadable()) {
            continue;
        }

		$download_links = [];
        $article_updated = false;

        $update_class = null;
        $download_icon = 'cloud-download';
		if ($copy->get('file_updated') == 1) {
            $updated++;
            $update_class = ' class="alert alert-info"';
            $download_icon = 'refresh';
        }

        $files = $fm->getAll(['article_id' => $article->get('id'), 'file_access' => 1]);
		foreach ($files as $f) {
            $download_links[] = '
                <li>
                    <a href="'.$f->getUrl().'" title="'.$f->get('version').' | '.file_size($f->get('size')).' | '.$f->getType('name').'"
                            aria-label="Télécharger '.$f->getType('name').'">
                        <img src="'.$f->getType('icon').'" width=16 alt="Télécharger"> '.$f->getType('name').'
                    </a>
                </li>
            ';
		}

        // Couverture
		$article_cover = null;
        if (media_exists('book', $article->get('id'))) {
            $article_cover = '<a href="/'.$article->get('url').'"><img src="'.media_url('book', $article->get('id'), "h60").'" alt="'.$article->get('title').'" /></a>';
        }

		// Liens de téléchargement
		if ($article->get('pubdate') > date("Y-m-d") && !$copy->has("stock_allow_predownload")) {
            $dl_links = 'A para&icirc;tre<br />le '._date($article->get('pubdate'), 'd/m');
        } elseif (empty($download_links)) {
            $dl_links = 'Aucun fichier disponible';
        } else {
			$dl_links = '
				<div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-label="Ouvrir le menu de sélection du format">
                        <span class="fa fa-'.$download_icon.'"></span>
                        &nbsp; Télécharger &nbsp; 
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        '.implode($download_links).'
                    </ul>
                </div>
			';
		}

        $ebooks .= '
            <tr'.$update_class.'>
                <td class="center">'.$article_cover.'</td>
                <td style="max-width: 50%">
                     <a href="/'.$article->get('url').'">'.$article->get('title').'</a><br>'.$article->get('authors').'
                </td>
				<td width="125" class="center">
					'.$dl_links.'
				</td>
            </tr>
        ';
    }

    if ($updated) {
        $_ECHO .='
            <br>
            <p class="alert alert-info">
                <span class="fa fa-refresh"></span>&nbsp;
                Certains fichiers ont été mis à jour depuis votre dernier téléchargement.
            </p>
        ';
    }

    $_ECHO .= '<br />
        <table class="ebooks">
            <tbody>
                '.$ebooks.'
            </tbody>
        </table>
    ';

    $_ECHO .= '
        <h3 class="center">
            <a href="http://www.biblys.fr/pages/doc_ebooks">Comment télécharger et lire des livres numériques ?</a>
        </h3>
		<br />
    ';
