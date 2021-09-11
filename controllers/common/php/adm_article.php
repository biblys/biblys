<?php

use Biblys\Contributor\Contributor;
use Biblys\Exception\InvalidEntityException;
use Biblys\Isbn\IsbnParsingException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Biblys\Service\Browser;
use Biblys\Isbn\Isbn;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

$am = new ArticleManager();
$sm = new SiteManager();
$pm = new PublisherManager();
$cm = new CollectionManager();

$am->setIgnoreSiteFilters(true);

$content = "";

// Check browser version
$browser = new Browser();
if (!$browser->isUpToDate()) {
    $content .= $browser->getUpdateAlert('error');
}

$_JS_CALLS[] = '/common/js/adm_article.js';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /** @var Article $article */
    $article = $am->getById($_POST['article_id']);

    $params = array();

    // Valider et...
    if (isset($_POST['article_submit_and_show'])) {
        $redirect_to_show = true;
        $_POST['article_submit_and_show'] = null;
    } elseif (isset($_POST['article_submit_and_stock'])) {
        $redirect_to_stock = true;
        $_POST['article_submit_and_stock'] = null;
    } elseif (isset($_POST['article_submit_and_new'])) {
        $redirect_to_new = 1;
        $_POST['article_submit_and_new'] = null;
    }

    // Precommande
    if (empty($_POST['article_preorder'])) {
        $_POST['article_preorder'] = 0;
    }

    // Editable price
    if (empty($_POST['article_price_editable'])) {
        $_POST['article_price_editable'] = 0;
    }

    // TextID
    if (empty($_POST['article_title_original'])) {
        $textid_title = $_POST['article_title_original'];
    } else {
        $textid_title = $_POST['article_title'];
    }

    // Link to another edition of the same book
    $linkto = $request->request->get('article_link_to', false);
    if ($linkto) {
        unset($_POST['article_link_to']);
        $other = $am->getById($linkto);

        // If the other book exists, link to it
        if ($other) {
            // If the other book already has an item id, use it
            $item = $other->get('item');
            if ($item) {
                $_POST['article_item'] = $item;
            } else {
                // Else, create a new (negative) one

                // Get the smallest article_item yet and substract one
                $last_item_article = $am->get(
                    ['article_item' => 'NOT NULL'],
                    ['order' => 'article_item']
                );

                if ($last_item_article) {
                    $_POST['article_item'] = $last_item_article->get('item') - 1;
                } else {
                    $_POST['article_item'] = -1;
                }

                // Update the other edition too
                $other->set('article_item', $_POST['article_item']);
                $am->update($other);
            }
        }
    }

    // Bundle
    if ($_POST['type_id'] == 8) {
        $bundle = $_SQL->query('SELECT `article_ean` FROM `articles` JOIN `links` USING(`article_id`) WHERE `bundle_id` = '.$_SQL->quote($_POST['article_id'], PDO::PARAM_INT));
        while ($bu = $bundle->fetch()) {
            $_POST['article_ean_others'] .= ' '.$bu['article_ean'];
        }
    }

    // Autres ISBN
    if (!empty($_POST['article_ean_others'])) {
        $eans = array_unique(explode(' ', $_POST['article_ean_others']));
        $_POST['article_ean_others'] = null;
        foreach ($eans as $ean) {
            if (Isbn::isParsable($ean)) {
                $_POST['article_ean_others'] .= ' '.Isbn::convertToEan13($ean);
            }
        }
        $_POST['article_ean_others'] = trim($_POST['article_ean_others']);
    }

    // Titre alphabetique
    $_POST['article_title_alphabetic'] = alphabetize($_POST['article_title']);

    // Retirer les liens dans article_summary
    $_POST['article_summary'] = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', '\\2', $_POST['article_summary']);

    // Set article_tva according to type_id
    $_POST['article_tva'] = 3;
    if (isset($_POST['type_id'])) {
        if (in_array($_POST['type_id'], array(1, 2, 7, 8, 9, 12))) {
            $_POST['article_tva'] = 1;
        } elseif ($_POST['type_id'] == 12) {
            $_POST['article_tva'] = 2;
        }
    }

    // FICHIERS //

    // Couverture
    if (!empty($_FILES['article_cover_upload']['tmp_name'])) { // Upload
        $cover = new Media('article', $_POST['article_id']);
        $cover->upload($_FILES['article_cover_upload']['tmp_name']);
        $article->bumpCoverVersion();
    } elseif (!empty($_POST['article_cover_import'])) { // Import
        media_delete('article', $_POST['article_id']);
        $copy_from = $_POST['article_cover_import'];
        $file_dir = MEDIA_PATH.'/book/'.file_dir($_POST['article_id']).'/';
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        $copy_to = $file_dir.$_POST['article_id'].'.jpg';
        copy($copy_from, $copy_to) or error('Unable to copy '.$copy_from.' to '.$copy_to);
        $article->bumpCoverVersion();
    } elseif (isset($_POST['article_cover_delete']) && $_POST['article_cover_delete']) { // Suppression
        media_delete('article', $_POST['article_id']);
    }
    unset($_POST['article_cover_upload']);
    unset($_POST['article_cover_import']);
    unset($_POST['article_cover_delete']);

    // Set article collection & publisher
    $collectionId = $request->request->get('collection_id');
    $collection = $cm->getById($collectionId);
    if (!$collection) {
        throw new Exception('Collection unknown');
    }
    $article->setCollection($collection);

    // VALIDATION
    foreach ($_POST as $key => $val) {
        $article->set($key, $val);
    }
    $article->set('article_editing_user', 0);

    // Persist & refresh article
    try {
        $am->update($article);
        $article = $am->getById($article->get('id'));

        // Update keywords & links
        $article = $am->refreshMetadata($article);
        $am->update($article);

        // Mise a jour des pages contributeurs concernes
        $peopleToUpdate = $_SQL->prepare('SELECT `people_id` FROM `people` JOIN `roles` USING(`people_id`) WHERE `article_id` = :article_id');
        $peopleToUpdate->execute(['article_id' => $request->request->get('article_id')]);
        while ($up = $peopleToUpdate->fetch(PDO::FETCH_ASSOC)) {
            $update = $_SQL->prepare('UPDATE `people` SET `people_update` = NOW() WHERE `people_id` = :people_id LIMIT 1');
            $update->execute(['people_id' => $up['people_id']]);
        }

        // Virtual stock: update available copies if necessary
        if ($site->getOpt('virtual_stock')) {
            $stm = new StockManager();
            $stock = $stm->getAll(['article_id' => $article->get('id')]);
            $params['stock_updated'] = 0;
            foreach ($stock as $copy) {
                if ($copy->isAvailable()) {
                    // Update copies price
                    if ($copy->get('selling_price') != $article->get('price')) {
                        $copy->set('stock_selling_price', $article->get('price'));
                        ++$params['stock_updated'];
                    }
                    // Return copies if article is sold out
                    if ($article->isSoldOut()) {
                        $copy->setReturned();
                    }
                    $stm->update($copy);
                }
            }
        }

        // Redirection
        if (isset($redirect_to_stock)) {
            redirect('/pages/adm_stock?add=' . $_POST['article_id'] . '#add');
        } elseif (isset($redirect_to_new)) {
            redirect('/pages/adm_article');
        } else {
            $articleUrl = $urlgenerator->generate('article_show', [
                'slug' => $article->get('url'),
            ]);
            return new RedirectResponse($articleUrl);
        }
    } catch (IsbnParsingException $exception) {
        throw new BadRequestHttpException(
            sprintf(
                "Le code EAN %s est invalide. Le validateur a renvoyé l'erreur : \"%s\".",
                $request->request->get("article_ean"),
                $exception->getMessage()
            )
        );
    } catch (InvalidEntityException $exception) {
        throw new BadRequestHttpException(
            sprintf(
                "L'enregistrement de l'article a échoué. Le validateur a renvoyé l'erreur : \"%s\".",
                $exception->getMessage()
            )
        );
    }
}

// Modifier un article existant
if (!isset($_GET['id'])) {
    $_GET['id'] = null;
}
$articles = EntityManager::prepareAndExecute(
    'SELECT * FROM `articles`
    WHERE (`article_id` = :article_id OR `article_editing_user` = :user_id)
    ORDER BY `article_editing_user` LIMIT 1',
    [
        'article_id' => $request->query->get('id'),
        'user_id' => $_V->get('user_id'),
    ]
);
if ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
    $article = $am->getById($a['article_id']);

    // Livres numériques
    $ebook_pdf = new Media('pdf', $a['article_id']);
    $ebook_epub = new Media('epub', $a['article_id']);
    $ebook_azw = new Media('azw', $a['article_id']);

    $default_tags = '';

    if (!empty($a['article_title'])) {
        // Mode editeur
        if (!$_V->isAdmin() && $_V->getCurrentRight()->get('publisher_id') != $a['publisher_id']) {
            die("Vous n'avez pas le droit de modifier ce livre !");
        }

        // Biblys publisher's catalog can only be edited on it's own site
        $publisher = $article->get('publisher');
        $publisher_site = $sm->get(array('publisher_id' => $publisher->get('id')));
        if (
            $publisher_site &&
            $publisher_site->get('id') != $_SITE['site_id'] &&
            !$_V->hasRight('publisher', $publisher->get('id'))
        ) {
            trigger_error("Vous n'avez pas l'autorisation de modifier les articles du catalogue ".$publisher->get('name').", merci de <a href='http://".$publisher_site->get('domaine')."/contact/'>contacter l'éditeur</a>.");
        }

        $_PAGE_TITLE = 'Modifier '.$a['article_title'];
        $_MODE = 'update';

        $article = $am->getById($a['article_id']);
        $articleUrl = $urlgenerator->generate(
            'article_show',
            [
            'slug' => $article->get('url'),
            ]
        );

        $articleCover = null;
        if ($article->hasCover()) {
            $articleCover = $article->getCoverTag(
                [
                    'class' => 'article-thumb-cover',
                    'link' => false,
                    'height' => 85,
                ]
            );
        }

        $content .= '
            <div class="article-editor">
                <h1><span class="fa fa-book"></span> Modifier l\'article n&deg; '.$a['article_id'].'</h1>

                <a href="'.$articleUrl.'">
                    <div class="article-thumb">
                        '.$articleCover.'
                        <div>
                            <h3>'.$a['article_title'].'</h3>
                            <p>
                                de '.truncate($a['article_authors'], 65, '...', true, true).'<br />
                                coll. '.$a['article_collection'].' '.numero($a['article_number']).' ('.$a['article_publisher'].')<br />
                                Prix &eacute;diteur : '.price($a['article_price'], 'EUR').'
                            </p>
                        </div>
                    </div>
                </a>
        ';
        $import_default = '<div class="center"><a class="btn btn-default reimport event">R&eacute;importer la fiche depuis la base externe...</a></div>';
    } else {
        $_PAGE_TITLE = 'Créer un nouvel article';
        $_MODE = 'insert';
        if (isset($_GET['import'])) {
            if (Isbn::isParsable($_GET['import'])) {
                $a['article_ean'] = Isbn::convertToEan13($_GET['import']);
                $article_ean_class = ' autoimport';
            } else {
                $a['article_title'] = $_GET['import'];
                $article_title_class = ' autoimport';
            }
        }
        $content .= '<h1><span class="fa fa-book"></span> Créer un nouvel article</h1>';
        $import_default = '<h4 class="center">Entrez un ISBN ou un titre <br />pour rechercher dans les bases externes...</h4>';

        // Default type
        if ($site->getOpt('default_type_id')) {
            $article->set('type_id', $site->getOpt('default_type_id'));
        }

        $a['collection_id'] = $site->getOpt('default_collection_id') ? $site->getOpt('default_collection_id') : $a['collection_id'];
        $a['article_source_id'] = $site->getOpt('default_article_source_id') ? $site->getOpt('default_article_source_id') : $a['article_source_id'];
        $a['article_pubdate'] = $site->getOpt('default_article_pubdate') ? $site->getOpt('default_article_pubdate') : $a['article_pubdate'];
        $default_tags = $site->getOpt('default_article_tags');
    }
} else {
    // Creer un nouvel article
    $articleInsert = EntityManager::prepareAndExecute(
        'INSERT INTO `articles`(`article_editing_user`, `article_created`) VALUES(:user_id, NOW())',
        ['user_id' => $_V->get('user_id')]
    );

    $import = $request->query->get('import', null);
    redirect('/pages/'.$_PAGE.'?import='.$import);
}

// Types
$article_ean_required = null;
$ebooks_fieldset_class = 'hidden';
$bundle_fieldset_class = 'hidden';
$article_ean_div_class = null;
$article_ean_class = null;
$article_title_class = null;

$type_options = Biblys\Article\Type::getOptions($article->get('type_id'));

if ($a['type_id'] == 2) {
    $ebooks_fieldset_class = null;
    $article_ean_required = null;
    $article_ean_div_class = 'hidden';
}
if ($a['type_id'] == 8) {
    $bundle_fieldset_class = null;
}

// Collection
$category_field_class = 'hidden';
$article_price_readonly = null;
if (!empty($a['collection_id'])) {
    $collections = $_SQL->prepare('SELECT `collection_name`, `site_id`, `pricegrid_id`, `publisher_id` FROM `collections` WHERE `collection_id` = :collection_id LIMIT 1');
    $collections->execute(['collection_id' => $article->get('collection_id')]);
    $c = $collections->fetch(PDO::FETCH_ASSOC);
    $a['publisher_id'] = $c['publisher_id'];
    if (!empty($c['pricegrid_id'])) {
        $category_field_class = '';
        $article_price_readonly = 'readonly title="Choisissez la cat&eacute;gorie correspondante."';
    }
}

// Availability
$availability_options = [];
foreach (Article::$AVAILABILITY_DILICOM_VALUES as $key => $value) {
    $availability_options[] = "<option value=\"$key\"".($article->get('availability_dilicom') == $key ? 'selected' : null).">$value</option>";
}

// Preorder
$preorder = null;
if ($article->get('preorder') == 1) {
    $preorder = 'checked';
}

// Tax
$tva_1 = null; $tva_2 = null; $tva_3 = null;
if ($a['article_tva'] == 1) {
    $tva_1 = ' selected="selected" ';
} elseif ($a['article_tva'] == 2) {
    $tva_2 = ' selected="selected" ';
} elseif ($a['article_tva'] == 3) {
    $tva_3 = ' selected="selected" ';
}

// Langues
$lang_current_options = '<option value="48">Fran&ccedil;ais</option>'; // Default a la creation de la fiche
$lang_original_options = '<option value=""></option>'; // Default a la creation de la fiche
$langs = $_SQL->prepare('SELECT `lang_id`, `lang_name`, `lang_name_original` FROM `langs` ORDER BY `lang_name`');
$lgm = new LangManager();
$langs = $lgm->getAll();
foreach ($langs as $l) {
    if ($a['article_lang_current'] == $l['lang_id']) {
        $l['lang_current_sel'] = ' selected';
    } else {
        $l['lang_current_sel'] = null;
    }
    if ($a['article_lang_original'] == $l['lang_id']) {
        $l['lang_original_sel'] = ' selected';
    } else {
        $l['lang_original_sel'] = null;
    }
    $lang_current_options .= '<option value="'.$l['lang_id'].'"'.$l['lang_current_sel'].'>'.$l['lang_name'].' ('.$l['lang_name_original'].')</option>';
    $lang_original_options .= '<option value="'.$l['lang_id'].'"'.$l['lang_original_sel'].'>'.$l['lang_name'].' ('.$l['lang_name_original'].')</option>';
}

// Countries
$cm = new CountryManager();
$countries = $cm->getAll([], ['order' => 'country_name']);
$articleOriginCountry = $article->get('origin_country');
$origin_country_options = array_map(function ($country) use ($articleOriginCountry) {
    $selected = $country->get('id') == $articleOriginCountry ? ' selected' : null;
    return '<option value='.$country->get('id').$selected.'>'.$country->get('name').'</option>';
}, $countries);

// Collection
$cm = new CollectionManager();
$collection = $cm->getById($article->get('collection_id'));
if ($collection) {
    $collection = '
        <input type="text" id="article_collection" value="'.$collection->get('name').'" class="long pointer changeThis" required readonly />
        <input type="hidden" id="collection_id" name="collection_id" value="'.$collection->get('id').'" required />
        <input type="hidden" id="pricegrid_id" value="'.$collection->get('pricegrid_id').'" />
    ';
} else {
    $collection = '
        <input type="text" id="article_collection" class="long changeThis uncomplete" required />
        <input type="hidden" id="collection_id" name="collection_id" required />
        <input type="hidden" id="pricegrid_id" />
    ';
}

// Current publisher id (from site or from rights)
$publisher_id = $site->get('publisher_id');
if (!$publisher_id && !$_V->isAdmin() && $_V->isPublisher()) {
    $publisher_id = $_V->getCurrentRight()->get('publisher_id');
}

$bonus_fieldset_class = 'hidden';

if ($publisher_id) {
    $pub = $pm->getById($publisher_id);
    if ($pub) {
        $createCollectionPublisher = '
            <input type="text" id="collection_publisher" name="collection_publisher" value="'.$pub->get('name').'" class="long uncomplete" required readonly>
            <input type="hidden" id="collection_publisher_id" name="collection_publisher_id" value="'.$pub->get('id').'" required readonly>
        ';

        // Display bonus field if on current publisher's site
        $bonus_fieldset_class = null;
    }
} else {
    $createCollectionPublisher = '
        <input type="text" id="collection_publisher" name="collection_publisher" class="long uncomplete" required>
        <input type="hidden" id="collection_publisher_id" name="collection_publisher_id" required>
    ';
}

// Cycle
$cym = new CycleManager();
$cycle = $cym->getById($article->get('cycle_id'));
if ($cycle) {
    $cycle = '
        <input type="text" id="article_cycle" name="article_cycle" value="'.$cycle->get('name').'" class="long pointer changeThis" readonly />
        <input type="hidden" id="cycle_id" name="cycle_id" value="'.$cycle->get('id').'" />
    ';
} else {
    $cycle = '
        <input type="text" id="article_cycle" name="article_cycle" class="long changeThis uncomplete" />
        <input type="hidden" id="cycle_id" name="cycle_id" />
    ';
}

// ** PEOPLE ** //

// Roles
$jobs = \Biblys\Contributor\Job::getAll();
$jobOptions = array_map(function(\Biblys\Contributor\Job $job) {
    return '<option value="'.$job->getId().'">'.$job->getNeutralName().'</option>';
}, $jobs);
$content .= '<select id="new_people_job" hidden>'.join($jobOptions).'</select>';

// Contributeurs
if ($_MODE == 'insert') {
    $delete = $_SQL->prepare('DELETE FROM `roles` WHERE `article_id` = :article_id');
    $delete->execute(['article_id' => $article->get('id')]);
}

// ** FICHIERS ** //

// Couverture
if (media_exists('article', $a['article_id'])) {
    $article_cover_upload = '<input type="file" id="article_cover_upload" name="article_cover_upload" accept="image/jpeg" hidden /> <label class="after btn btn-default" for="article_cover_upload">Remplacer</label> <input type="checkbox" id="article_cover_delete" name="article_cover_delete" value="1" /> <label for="article_cover_delete" class="after">Supprimer</label>';
} else {
    $article_cover_upload = '<input type="file" id="article_cover_upload" name="article_cover_upload" accept="image/jpeg" />';
}

// ** FICHIERS TELECHARGEABLES ** //

// Files
$fm = new FileManager();
$files = $fm->getAll(['article_id' => $a['article_id']]); // Get all files for this article
$files_table = null;
foreach ($files as $file) {
    $files_table .= $file->getLine();
}

// ** METADONNEES ** //

// Rayons actuels
$links = $_SQL->query("
    SELECT `link_id`, `rayon_name` FROM `links`
    JOIN `rayons` USING(`rayon_id`)
    WHERE `article_id` = '".$a['article_id']."'
        AND `links`.`site_id` = '".$_SITE['site_id']."'
    ORDER BY `link_id`");
$rayons_actuels = null;
while ($r = $links->fetch(PDO::FETCH_ASSOC)) {
    $rayons_actuels .= '<li><a class="btn btn-danger btn-xs" data-remove_link='.$r['link_id'].'><span class="fa fa-remove" title="Supprimer le rayon"></span></a> '.$r['rayon_name'].'</li>';
}

// Rayons ajoutables
$rm = new RayonManager();
$rayons = $rm->getAll([], ['order' => 'rayon_name']);
$rayons_options = null;
foreach ($rayons as $rayon) {
    $rayons_options .= '<option value="'.$rayon->get('id').'">'.$rayon->get('name').'</option>';
}
if (isset($rayons_options)) {
    $rayons_options = '
        <p>
            <label>Ajouter au rayon :</label>
            <select id="rayon_id">
                <option value="0">Choisir un rayon...</option>
                '.$rayons_options.'
            </select>
        </p>
    ';
} else {
    $rayons_options = 'Aucun rayon disponible.';
}

// Tags
$tags = $_SQL->prepare('SELECT `link_id`, `tag_name` FROM `links` JOIN `tags` USING(`tag_id`) WHERE `article_id` = :article_id ORDER BY `tag_name`');
$tags->execute(['article_id' => $article->get('id')]);
$the_tags = null;
while ($t = $tags->fetch(PDO::FETCH_ASSOC)) {
    $the_tags .= '<li><a class="btn btn-danger btn-xs" data-remove_link="'.$t['link_id'].'"><span class="fa fa-remove"></span></a> '.$t['tag_name'].'</li>';
}

// Recompenses
$awards = $_SQL->prepare('SELECT `award_id`, `award_name`, `award_year`, `award_category` FROM `awards` WHERE `article_id` = :article_id ORDER BY `award_year` DESC');
$awards->execute(['article_id' => $article->get('id')]);
$the_awards = null;
while ($aw = $awards->fetch(PDO::FETCH_ASSOC)) {
    $the_awards .= '<li id="award_'.$aw['award_id'].'"><img src="/common/icons/delete_16.png" class="pointer deleteAward" data-award_id="'.$aw['award_id'].'"> '.$aw['award_name'].' '.$aw['award_year'].' ('.$aw['award_category'].')</li>';
}

// Lot
$bundle_articles = null;
if ($a['type_id'] == 8) {
    $bundle = $_SQL->prepare('SELECT `article_title`, `article_authors`, `article_url`, `article_collection`, `link_id` FROM `articles` JOIN `links` USING(`article_id`) WHERE `bundle_id` = :article_id ORDER BY `link_id`');
    $bundle->execute(['article_id' => $article->get('id')]);
    while ($bu = $bundle->fetch(PDO::FETCH_ASSOC)) {
        $bundle_articles .= '<li id="link_'.$bu['link_id'].'"><img src="/common/icons/delete_16.png" data-link_id="'.$bu['link_id'].'" class="deleteLink pointer" /> <a href="/'.$bu['article_url'].'">'.$bu['article_title'].'</a> de '.$bu['article_authors'].' ('.$bu['article_collection'].')</li>';
    }
}

// ** VALIDATION ** //

// Add a copy button if not virtual stock
$submit_and_stock = '<br>
    <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_stock">
        Valider et ajouter un exemplaire
    </button>';
if ($site->getOpt('virtual_stock')) {
    $submit_and_stock = null;
}

$content .= '
    <form id="createCollection" class="event hidden">
        <fieldset>
            <p>Si l\'éditeur n\'a pas de collection ou si le livre est &laquo;&nbsp;hors collection&nbsp;&raquo;, indiquer le nom de l\'éditeur à l\'identique dans le champ collection.</p>
            <label for="collection_name">Collection :</label>
            <input type="text" id="collection_name" name="collection_name" class="long" required />
            <br />
            <label for="collection_publisher">&Eacute;diteur :</label>
            '.$createCollectionPublisher.'
            <br />
        </fieldset>
    </form>

    <form id="create_people" class="e createPeopleForm hidden">
        <fieldset>
            <label for="people_first_name">Pr&eacute;nom :</label>
            <input type="text" id="people_first_name" name="people_first_name" class="long" />
            <br />
            <label for="people_last_name">Nom :</label>
            <input type="text" id="people_last_name" name="people_last_name" class="long" required />
        </fieldset>
    </form>

    <form id="create_price" class="hidden">
        <fieldset>
            <label for="price_cat">Cat&eacute;gorie :</label>
            <input type="text" id="price_cat" name="price_cat" class="short" />
            <br />
            <label for="price_amount">Prix :</label>
            <input type="number" id="price_amount" name="price_amount" class="short" required /> centimes
            <br />
            <div class="right"><input type="submit" value="Valider" id="createPriceSubmit" /></div>
        </fieldset>
    </form>

    <form id="add_award" class="event hidden">
        <fieldset>
            <label for="award_name">R&eacute;compense :</label>
            <input type="text" id="award_name" name="award_name" class="long" required />
            <br />
            <label for="award_year">Ann&eacute;e :</label>
            <input type="number" id="award_year" name="award_year" class="short" required />
            <br />
            <label for="award_category">Cat&eacute;gorie :</label>
            <input type="text" id="award_category" name="award_category" class="long" />
            <br />
            <label for="award_note">Note :</label>
            <input type="text" id="award_note" name="award_note" class="long" />
            <br />
            <div class="right"><input type="submit" value="Ajouter" id="addAwardSubmit"></div>
        </fieldset>
    </form>

    <form id="article_form" action="/pages/'.$_PAGE.'?id='.$a['article_id'].'" method="post" enctype="multipart/form-data" class="fieldset check event" data-mode="'.$_MODE.'" data-uploading=0 data-submitted=0>

        <fieldset>
            <legend>Importation</legend>
            <div id="article_import">
                '.$import_default.'
            </div>
        </fieldset>

        <fieldset>
            <legend>L\'essentiel</legend>

            <label for="article_id">Article n&deg;</label>
            <input type="text" id="article_id" name="article_id" value="'.$a['article_id'].'" class="mini" readonly required />
            <br /><br />

            <label for="type_id">Type :</label>
            <select name="type_id" id="type_id" required>
                '.join($type_options).'
            </select>
            <br /><br />

            <div id="article_ean_div" class="'.$article_ean_div_class.'">
                <label for="article_ean">EAN/ISBN :</label>
                <input id="article_ean" name="article_ean" value="'.$a['article_ean'].'" class="medium event article_ean'.$article_ean_class.'" '.$article_ean_required.' autofocus />
                <br /><br />
            </div>

            <label for="article_title">Titre :</label>
            <input type="text" id="article_title" name="article_title" value="'.htmlspecialchars($a['article_title']).'" class="long article_duplicate_check event'.$article_title_class.'" required />
            <br />

            <label for="article_authors">Auteur(s) :</label>
            <input type="text" id="article_authors" value="'.$a['article_authors'].'" class="long" tabindex="-1" readonly required maxlength=256 data-toggle="popover" data-trigger="focus" title="Ajouter un auteur" data-content="Champ rempli automatiquement. Utilisez la section Contributeurs (ci-dessous) pour ajouter ou supprimer un auteur.">
            <br />

            <label for="article_collection">Collection :</label>
            '.$collection.'
            n&deg; <input type="text" id="article_number" name="article_number" value="'.$a['article_number'].'" class="mini article_duplicate_check event" />
            <br />
            <label for="article_cycle">Cycle :</label>
            '.$cycle.'
            t. <input type="text" id="article_tome" name="article_tome" value="'.$a['article_tome'].'" class="mini" />
            <br /><br />

            <label for="article_availability_dilicom">Disponibilit&eacute; :</label>
            <select id="article_availability_dilicom" name="article_availability_dilicom" required>
                '.implode($availability_options).'
            </select>
            <span'.(!$site->getOpt('virtual_stock') ? ' style="display: none;"' : null).'>
                <input type="checkbox" name="article_preorder" id="article_preorder" value="1" '.$preorder.' />
                <label for="article_preorder" class="after">Pr&eacute;commande</label>
            </span>
            <br /><br />

            <div id="article_category_div" class="'.$category_field_class.'">
                <label for="article_category">Cat&eacute;gorie :</label>
                <input type="text" id="article_category" name="article_category" value="'.$a['article_category'].'" class="mini" title="Appuyez sur la touche &darr; pour faire appara&icirc;tre la liste des cat&eacute;gories." />
                <br />
            </div>
            <p>
                <label for="article_price">Prix :</label>
                <input type="number" step="1" id="article_price" name="article_price" value="'.$a['article_price'].'" class="mini" required '.$article_price_readonly.' data-html="true" data-toggle="popover" data-trigger="focus" data-content="Entrez le prix de l\'article en centimes. Par exemple, pour article à 14,00 €, entrez <strong>1400</strong> ; pour un article à 8,50 €, entrez <strong>850</strong>."> centimes
            </p>
            <p>
                <label for="article_price_editable">Prix libre:</label>
                <input type="checkbox" name="article_price_editable" id="article_price_editable" value="1"'.($article->has('price_editable') ? ' checked' : null).'>
            </p>
            <br />
        </fieldset>

        <fieldset id="duplicates_fieldset" class="hidden pointer">
            <legend id="toggle_duplicates" class="toggleThis">Doublons potentiels</legend>
            <ul id="duplicates">
            </ul>
        </fieldset>

        <fieldset id="bundle_fieldset" class="'.$bundle_fieldset_class.'">
            <legend>Contenu du lot</legend>
            <ul id="bundle_articles">
                '.$bundle_articles.'
            </ul>
            <ul>
                <li><input type="text" id="addToBundle" class="long" placeholder="Ajouter un article..." /></li>
            </ul>
        </fieldset>

        <fieldset id="Contributeurs">
            <a href="http://www.biblys.fr/pages/doc_article#Contributeurs" target="_blank" class="pull-right">Besoin d\'aide ?</a>
            <legend>Contributeurs</legend>
            <div id="people_list"></div>
            <br /><br />

            <label for="article_people">Ajouter :</label>
            <input type="text" id="article_people" placeholder="Nom et/ou prénom du contributeur..." class="long" />
        </fieldset>

        <fieldset>
            <legend>Identifiants et liens</legend>

            <label for="article_url">URL Biblys :</label>
            <input type="text" id="article_url" name="article_url" value="'.$a['article_url'].'" pattern="^[a-z0-9-_]+/[a-z0-9-_]+$" title="Dans le doute, laisser ce champ vide." placeholder="Champ rempli automatiquement" class="long" pattern="^((?!http).)*$" />
            <br /><br />

            <label for="article_textid">TextID :</label>
            <input type="text" id="article_textid" name="article_textid" value="'.$a['article_textid'].'" class="verylong" maxlength=32>
            <br />
            <label for="article_item">Item n&deg;</label>
            <input type="text" id="article_item" name="article_item" value="'.$a['article_item'].'" class="mini article_duplicate_check event"  />
            <br />
            <label for="article_link_to">Lier &agrave l\'article n&deg;</label>
            <input type="number" id="article_link_to" name="article_link_to" class="mini" />
            <br />
            <label for="article_source_id">Extrait de l\'article n&deg;</label>
            <input type="number" name="article_source_id" value="'.$a['article_source_id'].'" class="mini" />
            <br /><br />

            <label for="article_noosfere_id">Ref. nooSFere :</label>
            <input type="text" id="article_noosfere_id" name="article_noosfere_id" value="'.$a['article_noosfere_id'].'" class="article_duplicate_check event" />
            <br />
            <label for="article_asin">Ref. Amazon :</label>
            <input type="text" id="article_asin" name="article_asin" value="'.$a['article_asin'].'" class="article_duplicate_check event" />
            <br /><br />

            <label for="article_ean_others">Autres ISBN :</label>
            <textarea id="article_ean_others" name="article_ean_others" class="small" title="ISBN de pr&eacute;c&eacute;dentes &eacute;ditions dans la m&eacute;me collection, s&eacute;par&eacute;s par un espace.">'.$a['article_ean_others'].'</textarea>
            <br /><br />
        </fieldset>

        <fieldset>
            <legend>Donn&eacute;es bibliographiques</legend>

            <label for="article_title_alphabetic">Titre pour le tri :</label>
            <input type="text" id="article_title_alphabetic" name="article_title_alphabetic" value="'.htmlspecialchars($a['article_title_alphabetic']).'" class="long" placeholder="Champ rempli automatiquement" />
            <br />
            <label for="article_subtitle">Sous-titre :</label>
            <input type="text" id="article_subtitle" name="article_subtitle" id="article_subtitle" value="'.htmlspecialchars($a['article_subtitle']).'" class="long" />
            <br />
            <label for="article_title_original">Titre original :</label>
            <input type="text" id="article_title_original" name="article_title_original" value="'.htmlspecialchars($a['article_title_original']).'" class="long" />
            <br />
            <label for="article_title_others">Autres titres :</label>
            <input type="text" id="article_title_others" name="article_title_others" value="'.htmlspecialchars($a['article_title_others']).'" class="long" />
            <br /><br />

            <label for="article_copyright">Copyright :</label>
            <input type="number" id="article_copyright" name="article_copyright" value="'.$a['article_copyright'].'" step="1" min="1800" max="'.date('Y').'+5" placeholder="AAAA" class="mini" />
            <br />
            <label for="article_pubdate">Date de parution :</label>
            <input type="date" id="article_pubdate" name="article_pubdate" value="'.$a['article_pubdate'].'" placeholder="AAAA-MM-JJ" />
            <br /><br />

            <p>
                <label for="article_lang_current">Langue actuelle :</label>
                <select name="article_lang_current" id="article_lang_current">'.$lang_current_options.'</select>
            </p>
            <p>
                <label for="article_lang_original">Langue d\'origine :</label>
                <select name="article_lang_original" id="article_lang_original">'.$lang_original_options.'</select>
            </p>
            <p>
                <label for="article_origin_country">Pays d\'origine :</label>
                <select name="article_origin_country" id="article_origin_country">
                    <option value="0"></option>
                    '.join($origin_country_options).'
                </select>
            </p>
            <br />
            <p>

                <label for="article_age_min">Lectorat :</label>
                <input type="number" id="article_age_min"
                    name="article_age_min" value="'.$a['article_age_min'].'"
                    step="1" min="1" max="99" class="mini" /> ans à
                <input type="number" id="article_age_max"
                    name="article_age_max" value="'.$a['article_age_max'].'"
                    step="1" min="1" max="99" class="mini" /> ans
            </p>

        </fieldset>

        <fieldset>
            <legend>Objet</legend>

            <p>
                <label for="article_shaping">Façonnage :</label>
                <select id="article_shaping" name="article_shaping">
                    <option>'.$a['article_shaping'].'</option>
                    <option />
                    <option>broché</option>
                    <option>broché avec rabats</option>
                    <option>cartonné</option>
                    <option>carré/collé</option>
                    <option>cousu</option>
                    <option>cousu avec rabats</option>
                    <option>integra</option>
                    <option>livret</option>
                    <option>porte-folio</option>
                    <option>relié</option>
                    <option>relié sous jaquette</option>
                    <option>reliure japonaise</option>
                    <option>souple</option>
                </select>
            </p>

            <p>
                <label for="article_printing_process">Impression :</label>
                <select id="article_printing_process" name="article_printing_process">
                    <option>'.$a['article_printing_process'].'</option>
                    <option />
                    <option>quadrichromie</option>
                </select>
            </p>

            <p>
                <label for="article_format">Format :</label>
                <input type="text" id="article_format" name="article_format" value="'.htmlspecialchars($article->get('format')).'" placeholder="10 x 18 cm" class="medium">
            </p>

            <p>
                <label for="article_pages">Nombre de pages :</label>
                <input type="number" step="1" id="article_pages" name="article_pages" value="'.$a['article_pages'].'" class="mini" />
            </p>

            <p>
                <label for="article_weight">Poids :</label>
                <input type="number" step="1" id="article_weight" name="article_weight" value="'.$a['article_weight'].'" class="mini" /> grammes
            </p>
        </fieldset>

        <fieldset>
            <legend>Images</legend>

            <p title="Image au format JPEG">
                <label for="article_cover_upload">Couverture :</label>
                '.$article_cover_upload.'<input type="hidden" id="article_cover_import" name="article_cover_import" placeholder="Adresse de l\'image...">
            </p>

            <p>
                <label>Version :</label>
                <input value="'.$article->get('cover_version').'" class="mini" disabled />
            </p>
        </fieldset>

        <fieldset>
            <legend>Fichiers téléchargeables</legend>

            <table id="dlfiles_list" class="admin-table fileUpload dropzone">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Accès</th>
                        <th>v.</th>
                        <th>ISBN</th>
                        <th>Type</th>
                        <th>Poids</th>
                        <th title="Nombre de téléchargements">DL</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    '.$files_table.'
                </tbody>
                <tfoot>
                    <tr>
                        <td class="center" colspan="8">
                            <label class="button" for="dlfile_upload_new" data-click="dlfile_upload_new" title="Vous pouvez aussi faire glisser les fichiers<br> depuis le bureau sur le tableau pour les ajouter">Ajouter des fichiers depuis l\'ordinateur</label>
                            <input type="file" id="dlfile_upload_new" data-file_id="new" class="dlfile_upload hidden event" multiple>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </fieldset>

        <fieldset>
            <legend>Classifications</legend>

            <h3>Mots-clés</h3>
            <ul id="the_tags">
                '.$the_tags.'
            </ul>

            <label for="article_theme_bisac">Ajouter :</label>
            <textarea id="add_tags_input" class="medium" placeholder="Entrer un ou plusieurs mots-clés, séparés par des virgules...">'.$default_tags.'</textarea>
            <a id="add_tags_button" class="btn btn-primary">Ajouter</a>

            <br /><br />

            <label for="article_theme_bisac">BISAC :</label>
            <input type="text" id="article_theme_bisac" name="article_theme_bisac" value="'.$a['article_theme_bisac'].'" class="medium">
            <br />
            <label for="article_theme_clil">CLIL :</label>
            <input type="text" id="article_theme_clil" name="article_theme_clil" value="'.$a['article_theme_clil'].'" class="medium">
            <br />
            <label for="article_theme_dewey">Dewey :</label>
            <input type="text" id="article_theme_dewey" name="article_theme_dewey" value="'.$a['article_theme_dewey'].'" class="medium">
            <br />
            <label for="article_theme_electre">Electre :</label>
            <input type="text" id="article_theme_electre" name="article_theme_electre" value="'.$a['article_theme_electre'].'" class="medium">
            <br />
        </fieldset>

        <fieldset>
            <legend>Rayons</legend>

            <h3>Rayons actuels</h3>
            <ul id="rayons">'.$rayons_actuels.'</ul>
            <br>

            '.$rayons_options.'
        </fieldset>

        <fieldset>
            <legend>R&eacute;compenses litt&eacute;raires</legend>
            <ul id="awards">
                '.$the_awards.'
            </ul>
            <div class="center">
                <br />
                <a id="addAward" class="btn btn-default">
                    Ajouter une r&eacute;compense
                </a>
            </div>
        </fieldset>

        <fieldset class="collapsable-fieldset"
            data-collapsed="'.($article->has('summary') ? 'false' : 'true').'">
            <legend>
                Quatri&egrave;me de couverture&nbsp;
                <span class="fa fa-plus-square"></span>
            </legend>
            <div class="collapsable-element">
                <textarea id="article_summary" name="article_summary"
                    class="wysiwyg">'.$a['article_summary'].'</textarea>
            </div>
        </fieldset>

        <fieldset class="collapsable-fieldset"
            data-collapsed="'.($article->has('contents') ? 'false' : 'true').'">
            <legend>
                Sommaire&nbsp;
                <span class="fa fa-plus-square"></span>
            </legend>
            <div class="collapsable-element">
                <textarea id="article_contents" name="article_contents"
                    class="wysiwyg">'.$a['article_contents'].'</textarea>
            </div>
        </fieldset>

        <fieldset class="collapsable-fieldset '.$bonus_fieldset_class.'"
            data-collapsed="'.($article->has('bonus') ? 'false' : 'true').'">
            <legend>
                Bonus&nbsp;
                <span class="fa fa-plus-square"></span>
            </legend>
            <div class="collapsable-element">
                <textarea id="article_bonus" name="article_bonus"
                    class="wysiwyg">'.$a['article_bonus'].'</textarea>
            </div>
        </fieldset>

        <fieldset class="collapsable-fieldset"
            data-collapsed="'.($article->has('catchline') ? 'false' : 'true').'">
            <legend>
                Accroche&nbsp;
                <span class="fa fa-plus-square"></span>
            </legend>
            <div class="collapsable-element">
                <textarea id="article_catchline" name="article_catchline"
                    class="wysiwyg">'.$article->get('catchline').'</textarea>
            </div>
        </fieldset>

        <fieldset class="collapsable-fieldset"
            data-collapsed="'.($article->has('biography') ? 'false' : 'true').'">
            <legend>
                Notice biographique&nbsp;
                <span class="fa fa-plus-square"></span>
            </legend>
            <div class="collapsable-element">
                <textarea id="article_biography" name="article_biography"
                    class="wysiwyg">'.$article->get('biography').'</textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Validation</legend>
            <div class="admin">
                <p>Validation</p>
                <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_show">Valider et aller à la fiche</button>
                <br>
                <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_new">Valider et créer une autre fiche</button>
                '.$submit_and_stock.'
                <p><a href="http://www.biblys.fr/pages/doc_article">Documentation</a></p>
            </div>
            <p class="center">
                <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_show">Valider et aller &agrave; la fiche</button>
                <br>
                <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_new">Valider et cr&eacute;er une autre fiche</button>
                '.$submit_and_stock.'
            </p>
        </fieldset>

        <fieldset>
            <legend>Suppression</legend>
            <p class="text-center">
                <a class="btn btn-danger" href='.$urlgenerator->generate('article_delete', ['id' => $article->get('id')]).'>
                    <span class="fa fa-trash-o"></span>
                    Supprimer définitivement cet article
                </a>
            </p>
        </fieldset>

        <fieldset>
            <legend>Base de donn&eacute;es</legend>

            <label for="article_created" class="readonly">Fiche créée le :</label>
            <input type="text" name="article_created" id="article_created" value="'.$a['article_created'].'" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
            <br />
            <label for="article_updated" class="readonly">Fiche modifiée le :</label>
            <input type="text" name="article_updated" id="article_updated" value="'.$a['article_updated'].'" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
            <br /><br />

            <label for="article_keywords" class="disabled">Termes de recherche :</label>
            <textarea id="article_keywords" name="article_keywords" class="large" placeholder="Utilis&eacute;s par le moteur de recherche et g&eacute;n&eacute;r&eacute;s automatiquement. Pour ajouter manuellement un terme de recherche personnalisé (par exemple th&eacute;matique), utilisez les mots-clés." disabled>'.$a['article_keywords'].'</textarea>
            <br /><br />

            <label for="article_links" class="disabled">Liaisons :</label>
            <textarea id="article_links" name="article_links" class="large" disabled>'.$a['article_links'].'</textarea>
            <br /><br />
        </fieldset>

    </form>
</div>
';

$response = new Response($content);

return $response;
