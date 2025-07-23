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

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Data\ArticleType;
use Biblys\Exception\InvalidEntityException;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Service\Config;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\StringService;
use Biblys\Service\TemplateService;
use Model\ArticleCategory;
use Model\ArticleCategoryQuery;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\LinkQuery;
use Model\PublisherQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Biblys\Isbn\Isbn;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request         $request,
    CurrentUser     $currentUser,
    CurrentSite     $currentSite,
    UrlGenerator    $urlGenerator,
    Config          $config,
    TemplateService $templateService,
): Response|RedirectResponse {
    $am = new ArticleManager();
    $sm = new SiteManager();
    $pm = new PublisherManager();

    $currentUser->authPublisher();

    $imagesService = new ImagesService($config, $currentSite, new Symfony\Component\Filesystem\Filesystem());

    $publisherId = $currentUser->getCurrentRight()?->getPublisherId();
    $publisher = PublisherQuery::create()->findPk($publisherId);
    if ($publisherId && !$currentSite->allowsPublisher($publisher)) {
        $publisherName = $publisher->getName();
        throw new AccessDeniedHttpException("Votre maison d'édition $publisherName n'est pas autorisée sur ce site.");
    }

    $am->setIgnoreSiteFilters(true);

    $content = "";

    if ($request->getMethod() === "POST") {
        $articleId = $request->request->get("article_id");
        $article = ArticleQuery::create()->findPk($articleId);
        /** @var Article $articleEntity */
        $articleEntity = $am->getById($articleId);

        $params = array();

        // Valider et...
        if (isset($_POST['article_submit_and_show'])) {
            $_POST['article_submit_and_show'] = null;
        } elseif (isset($_POST['article_submit_and_stock'])) {
            $redirect_to_stock = true;
            $_POST['article_submit_and_stock'] = null;
        } elseif (isset($_POST['article_submit_and_new'])) {
            $redirect_to_new = 1;
            $_POST['article_submit_and_new'] = null;
        }

        // Précommande
        if (empty($_POST['article_preorder'])) {
            $_POST['article_preorder'] = 0;
        }

        // Editable price
        if (empty($_POST['article_price_editable'])) {
            $_POST['article_price_editable'] = 0;
        }

        // Link to another edition of the same book
        $linkTo = $request->request->get('article_link_to', false);
        if ($linkTo) {
            unset($_POST['article_link_to']);
            $other = $am->getById($linkTo);

            // If the other book exists, link to it
            if ($other) {
                // If the other book already has an item id, use it
                $item = $other->get('item');
                if ($item) {
                    $_POST['article_item'] = $item;
                } else {
                    // Else, create a new (negative) one

                    // Get the smallest article_item yet and subtract one
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
            $bundle = EntityManager::prepareAndExecute(
                'SELECT `article_ean` FROM `articles` JOIN `links` USING(`article_id`) WHERE `bundle_id` = :bundle_id',
                ["bundle_id" => $_POST['article_id']]
            );
            while ($bu = $bundle->fetch()) {
                $_POST['article_ean_others'] .= ' ' . $bu['article_ean'];
            }
        }

        // Autres ISBN
        if (!empty($_POST['article_ean_others'])) {
            $EANs = array_unique(explode(' ', $_POST['article_ean_others']));
            $_POST['article_ean_others'] = null;
            foreach ($EANs as $ean) {
                if (Isbn::isParsable($ean)) {
                    $_POST['article_ean_others'] .= ' ' . Isbn::convertToEan13($ean);
                }
            }
            $_POST['article_ean_others'] = trim($_POST['article_ean_others']);
        }

        // Titre alphabétique
        $stringService = new StringService($_POST['article_title']);
        $_POST['article_title_alphabetic'] = $stringService->alphabetize()->get();

        // Retirer les liens dans article_summary
        $_POST['article_summary'] = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', '\\2', $_POST['article_summary']);

        // Set article_tva according to type_id
        $_POST['article_tva'] = 3;
        if (isset($_POST['type_id'])) {
            if (in_array($_POST['type_id'], array(1, 2, 7, 8, 9, 12))) {
                $_POST['article_tva'] = 1;
            }
        }

        // FICHIERS //

        // Couverture
        $uploadedCoverImageFile = $request->files->get("article_cover_upload");
        $coverImageFileToDownload = $request->request->get("article_cover_import");
        $shouldDeleteArticleCover = $request->request->get("article_cover_delete");
        if (!empty($uploadedCoverImageFile)) {
            $imagesService->addImageFor($article, $uploadedCoverImageFile);
            $articleEntity->bumpCoverVersion();
        } elseif (!empty($coverImageFileToDownload)) {
            $filesystem = new Symfony\Component\Filesystem\Filesystem();
            $temporaryFile = $filesystem->tempnam(sys_get_temp_dir(), 'article_cover_upload');
            $filesystem->copy($coverImageFileToDownload, $temporaryFile);
            $imagesService->addImageFor($article, $temporaryFile);
            $articleEntity->bumpCoverVersion();
        } elseif ($shouldDeleteArticleCover) {
            $imagesService->deleteImageFor($article);
            $articleEntity->bumpCoverVersion();
        }
        unset($_POST['article_cover_upload']);
        unset($_POST['article_cover_import']);
        unset($_POST['article_cover_delete']);

        $collectionId = $request->request->get('collection_id');
        $collection = BookCollectionQuery::create()->findPk($collectionId);
        $articleEntity->set("collection_id", $collection->getId());
        $articleEntity->set("collection", $collection->getName());

        $publisher = PublisherQuery::create()->findPk($collection->getPublisherId());
        $articleEntity->set("publisher_id", $publisher->getId());
        $articleEntity->set("publisher", $publisher->getName());

        // VALIDATION
        foreach ($_POST as $key => $val) {
            $articleEntity->set($key, $val);
        }
        $articleEntity->set('article_editing_user', 0);

        // Persist & refresh article
        try {
            $am->update($articleEntity);
            /** @var Article $articleEntity */
            $articleEntity = $am->getById($articleEntity->get('id'));

            // Update keywords & links
            $articleEntity = $am->refreshMetadata($articleEntity);
            $am->update($articleEntity);

            // Update related contributors pages
            $peopleToUpdate = EntityManager::prepareAndExecute(
                query: 'SELECT `people_id` FROM `people` JOIN `roles` USING(`people_id`) WHERE `article_id` = :article_id',
                params: ['article_id' => $request->request->get('article_id')]
            );
            while ($up = $peopleToUpdate->fetch(PDO::FETCH_ASSOC)) {
                EntityManager::prepareAndExecute(
                    query: 'UPDATE `people` SET `people_update` = NOW() WHERE `people_id` = :people_id LIMIT 1',
                    params: ['people_id' => $up['people_id']]
                );
            }

            // Virtual stock: update available copies if necessary
            if ($currentSite->getOption('virtual_stock')) {
                $stm = new StockManager();
                $stock = $stm->getAll(['article_id' => $articleEntity->get('id')]);
                $params['stock_updated'] = 0;
                foreach ($stock as $copy) {
                    if ($copy->isAvailable()) {
                        // Update copies price
                        if ($copy->get('selling_price') != $articleEntity->get('price')) {
                            $copy->set('stock_selling_price', $articleEntity->get('price'));
                            ++$params['stock_updated'];
                        }
                        // Return copies if article is sold out
                        if ($articleEntity->isSoldOut()) {
                            $copy->setReturned();
                        }
                        $stm->update($copy);
                    }
                }
            }

            // Redirection
            if (isset($redirect_to_stock)) {
                return new RedirectResponse('/pages/adm_stock?add=' . $articleId . '#add');
            } elseif (isset($redirect_to_new)) {
                return new RedirectResponse('/pages/article_edit');
            } else {

                $shouldUseLegacyArticleController = $currentSite->getOption("use_old_article_controller");
                if ($shouldUseLegacyArticleController) {
                    return new RedirectResponse("/legacy/a/" . $articleEntity->get("url"));
                }

                $articleUrl = $urlGenerator->generate('article_show', ['slug' => $articleEntity->get('url')]);
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
            'user_id' => $currentUser->getUser()->getId(),
        ]
    );
    if ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
        $articleEntity = $am->getById($a['article_id']);
        $article = ArticleQuery::create()->findPk($a['article_id']);

        $default_tags = '';

        if (!empty($a['article_title'])) {
            // Mode editeur
            if (!$currentUser->isAdmin() && $currentUser->getCurrentRight()->getPublisherId() != $a['publisher_id']) {
                throw new AccessDeniedHttpException("Vous n'avez pas le droit de modifier ce livre !");
            }

            // Biblys publisher's catalog can only be edited on its own site
            $publisher = $articleEntity->get('publisher');
            $publisher_site = $sm->get(array('publisher_id' => $publisher->get('id')));
            if (
                $publisher_site &&
                $publisher_site->get('id') != $currentSite->getId() &&
                !$currentUser->hasRightForPublisher($article->getPublisher())
            ) {
                throw new AccessDeniedHttpException(
                    "Vous n'avez pas l'autorisation de modifier les articles du catalogue {$publisher->get('name')}, merci de contacter l'éditeur."
                );
            }

            $request->attributes->set("page_title", "Modifier {$a["article_title"]}");
            $formMode = 'update';

            $articleEntity = $am->getById($a['article_id']);
            $articleUrl = $urlGenerator->generate('article_show', ['slug' => $articleEntity->get('url')]);

            $articleCoverUrl = $imagesService->getImageUrlFor($article);
            $articleCover = "";
            if ($articleCoverUrl) {
                $articleCover = '<img 
                    src="' . $articleCoverUrl . '" 
                    class="article-thumb-cover" 
                    alt="' . $articleEntity->get("title") . '"
                    height=85>
                ';
            }

            $content .= '
            <div class="article-editor">
                <h1><span class="fa fa-book"></span> Modifier l\'article n° ' . $a['article_id'] . '</h1>

                <a href="' . $articleUrl . '">
                    <div class="article-thumb">
                        ' . $articleCover . '
                        <div>
                            <h3>' . $a['article_title'] . '</h3>
                            <p>
                                de ' . truncate($a['article_authors'] ?? "", 65, '...', true, true) . '<br />
                                coll. ' . $a['article_collection'] . ' ' . numero($a['article_number']) . ' (' . $a['article_publisher'] . ')<br />
                                Prix éditeur : ' . price($a['article_price'], 'EUR') . '
                            </p>
                        </div>
                    </div>
                </a>
        ';
        } else {
            $request->attributes->set("page_title", "Créer un nouvel article");
            $formMode = 'insert';

            $importQuery = $request->query->get("import");
            if (isset($importQuery)) {
                if (Isbn::isParsable($importQuery)) {
                    $article->setEan(Isbn::convertToEan13($importQuery));
                } else {
                    $article->setTitle($importQuery);
                }
            }
            $content .= '<h1><span class="fa fa-book"></span> Créer un nouvel article</h1>';

            // Default type
            if ($currentSite->getOption('default_type_id')) {
                $articleEntity->set('type_id', $currentSite->getOption('default_type_id'));
            }

            $a['collection_id'] = $currentSite->getOption('default_collection_id') ? $currentSite->getOption('default_collection_id') : $a['collection_id'];
            $a['article_source_id'] = $currentSite->getOption('default_article_source_id') ? $currentSite->getOption('default_article_source_id') : $a['article_source_id'];
            $a['article_pubdate'] = $currentSite->getOption('default_article_pubdate') ? $currentSite->getOption('default_article_pubdate') : $a['article_pubdate'];
            $default_tags = $currentSite->getOption('default_article_tags');
        }
    } else {
        // Créer un nouvel article
        EntityManager::prepareAndExecute(
            'INSERT INTO `articles`(`article_editing_user`, `article_created`) VALUES(:user_id, NOW())',
            ['user_id' => $currentUser->getUser()->getId()]
        );

        $import = $request->query->get('import');
        return new RedirectResponse('/pages/article_edit?import=' . $import);
    }

    // Types
    $article_ean_required = null;
    $bundle_fieldset_class = 'hidden';
    $article_title_class = null;

    $type_options = ArticleType::getOptions($articleEntity->get('type_id'));

    if ($a['type_id'] == 8) {
        $bundle_fieldset_class = null;
    }

    // Collection
    $category_field_class = 'hidden';
    $article_price_readonly = null;
    if (!empty($a['collection_id'])) {
        $collections = EntityManager::prepareAndExecute(
            'SELECT `collection_name`, `site_id`, `pricegrid_id`, `publisher_id` FROM `collections` WHERE `collection_id` = :collection_id LIMIT 1',
            ["collection_id" => $articleEntity->get("collection_id")],
        );
        $c = $collections->fetch(PDO::FETCH_ASSOC);
        $a['publisher_id'] = $c['publisher_id'];
        if (!empty($c['pricegrid_id'])) {
            $category_field_class = '';
            $article_price_readonly = 'readonly title="Choisissez la catégorie correspondante."';
        }
    }

    // Availability
    $availability_options = [];
    foreach (Article::$AVAILABILITY_DILICOM_VALUES as $key => $value) {
        $availability_options[] = "<option value=\"$key\"" . ($articleEntity->get('availability_dilicom') == $key ? 'selected' : null) . ">$value</option>";
    }

    // Preorder
    $preorderChecked = null;
    if ($articleEntity->get("preorder") == 1) {
        $preorderChecked = "checked";
    }

    // Langues
    $lang_current_options = '<option value="48">Français</option>'; // Default a la creation de la fiche
    $lang_original_options = '<option value=""></option>'; // Default a la creation de la fiche
    $lgm = new LangManager();
    $languages = $lgm->getAll();
    foreach ($languages as $l) {
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
        $lang_current_options .= '<option value="' . $l['lang_id'] . '"' . $l['lang_current_sel'] . '>' . $l['lang_name'] . ' (' . $l['lang_name_original'] . ')</option>';
        $lang_original_options .= '<option value="' . $l['lang_id'] . '"' . $l['lang_original_sel'] . '>' . $l['lang_name'] . ' (' . $l['lang_name_original'] . ')</option>';
    }

    // Countries
    $cm = new CountryManager();
    $countries = $cm->getAll([], ['order' => 'country_name']);
    $articleOriginCountry = $articleEntity->get('origin_country');
    $origin_country_options = array_map(function ($country) use ($articleOriginCountry) {
        $selected = $country->get('id') == $articleOriginCountry ? ' selected' : null;
        return '<option value=' . $country->get('id') . $selected . '>' . $country->get('name') . '</option>';
    }, $countries);

    // Collection
    $cm = new CollectionManager();
    $collection = $cm->getById($articleEntity->get('collection_id'));
    if ($collection) {
        $collectionField = '
            <input type="text" id="article_collection" value="' . $collection->get('name') . '" class="form-control col-md-6 pointer changeThis" required readonly />
            <input type="hidden" id="collection_id" name="collection_id" value="' . $collection->get('id') . '" required />
            <input type="hidden" id="pricegrid_id" value="' . $collection->get('pricegrid_id') . '" />
        ';
    } else {
        $collectionField = '
            <input type="text" id="article_collection" class="form-control col-md-6 changeThis uncompleted" required />
            <input type="hidden" id="collection_id" name="collection_id" required />
            <input type="hidden" id="pricegrid_id" />
        ';
    }

    $bonus_fieldset_class = null;

    $createCollectionPublisher = '
        <input type="text" id="collection_publisher" name="collection_publisher" class="long uncompleted" required>
        <input type="hidden" id="collection_publisher_id" name="collection_publisher_id" required>
    ';

    if ($currentUser->hasPublisherRight()) {
        $currentUserPublisherId = $currentUser->getCurrentRight()->getPublisherId();
        $pub = $pm->getById($currentUserPublisherId);
        if ($pub) {
            $createCollectionPublisher = '
                <input type="text" id="collection_publisher" name="collection_publisher" value="' . $pub->get('name') . '" class="long uncompleted" required readonly>
                <input type="hidden" id="collection_publisher_id" name="collection_publisher_id" value="' . $pub->get('id') . '" required readonly>
            ';
        }
    }

    // Cycle
    $cym = new CycleManager();
    $cycle = $cym->getById($articleEntity->get('cycle_id'));
    if ($cycle) {
        $cycleField = '
            <input type="text" id="article_cycle" name="article_cycle" value="' . $cycle->get('name') . '" class="form-control col-md-6 pointer changeThis" readonly />
            <input type="hidden" id="cycle_id" name="cycle_id" value="' . $cycle->get('id') . '" />
        ';
    } else {
        $cycleField = '
            <input type="text" id="article_cycle" name="article_cycle" class="form-control col-md-6 changeThis uncompleted" />
            <input type="hidden" id="cycle_id" name="cycle_id" />
        ';
    }

    // ** CONTRIBUTIONS ** //

    if ($formMode == 'insert') {
        EntityManager::prepareAndExecute(
            query: 'DELETE FROM `roles` WHERE `article_id` = :article_id',
            params: ['article_id' => $articleEntity->get('id')]
        );
    }

    // ** FICHIERS ** //

    // Couverture
    $article_cover_upload = '<input type="file" id="article_cover_upload" name="article_cover_upload" accept="image/jpeg, image/png, image/webp" />';
    if ($imagesService->imageExistsFor($article)) {
        $article_cover_upload = '<input type="file" id="article_cover_upload" name="article_cover_upload" accept="image/jpeg, image/png, image/webp" hidden /> <label class="btn btn-outline-secondary" for="article_cover_upload">Remplacer</label> <input type="checkbox" id="article_cover_delete" name="article_cover_delete" value="1" /> <label for="article_cover_delete" class="after">Supprimer</label>';
    }

    // ** FICHIERS TÉLÉCHARGEABLES ** //

    // Files
    $fm = new FileManager();
    $files = $fm->getAll(['article_id' => $a['article_id']]); // Get all files for this article
    $files_table = null;
    foreach ($files as $file) {
        $files_table .= $file->getLine();
    }

    // ** MÉTADONNÉES ** //

    $articleCategoryLinks = LinkQuery::create()
        ->filterByArticleId($articleEntity->get('id'))
        ->filterByRayonId(null, Criteria::ISNOTNULL)
        ->find();
    $currentArticleCategories = "";
    /** @var ArticleCategory $articleCategory */
    foreach ($articleCategoryLinks as $articleCategoryLink) {
        $articleCategory = ArticleCategoryQuery::create()->findPk($articleCategoryLink->getRayonId());
        if ($articleCategory === null) {
            $articleCategory = new ArticleCategory();
            $articleCategory->setName("Rayon supprimé");
        }

        $currentArticleCategories .= '<li>
        <a class="btn btn-danger btn-sm" data-remove_link=' . $articleCategoryLink->getId() . '>
            <span class="fa fa-remove" title="Supprimer le rayon"></span>
        </a> 
        ' . $articleCategory->getName() . '
    </li>';
    }

    $rm = new RayonManager();
    $rayons = $rm->getAll([], ['order' => 'rayon_name']);
    $rayons_options = null;
    foreach ($rayons as $rayon) {
        $rayons_options .= '<option value="' . $rayon->get('id') . '">' . $rayon->get('name') . '</option>';
    }
    if (isset($rayons_options)) {
        $rayons_options = '
        <p>
            <label>Ajouter au rayon :</label>
            <select id="rayon_id">
                <option value="0">Choisir un rayon...</option>
                ' . $rayons_options . '
            </select>
        </p>
    ';
    } else {
        $rayons_options = 'Aucun rayon disponible.';
    }

    // Tags
    $tags = EntityManager::prepareAndExecute(
        'SELECT `link_id`, `tag_name` FROM `links` JOIN `tags` USING(`tag_id`) WHERE `article_id` = :article_id ORDER BY `tag_name`',
        ['article_id' => $articleEntity->get('id')],
    );
    $the_tags = null;
    while ($t = $tags->fetch(PDO::FETCH_ASSOC)) {
        $the_tags .= '<li><a class="btn btn-danger btn-sm" data-remove_link="' . $t['link_id'] . '"><span class="fa fa-remove"></span></a> ' . $t['tag_name'] . '</li>';
    }

    // Recompenses
    $awards = EntityManager::prepareAndExecute(
        'SELECT `award_id`, `award_name`, `award_year`, `award_category` FROM `awards` WHERE `article_id` = :article_id ORDER BY `award_year` DESC',
        ['article_id' => $articleEntity->get('id')],
    );
    $the_awards = null;
    while ($aw = $awards->fetch(PDO::FETCH_ASSOC)) {
        $the_awards .= '<li id="award_' . $aw['award_id'] . '">
            <i aria-label="Supprimer" class="fa-solid fa-xmark-circle pointer deleteAward" data-award_id="' . $aw['award_id'] . '"></i> 
            ' . $aw['award_name'] . ' ' . $aw['award_year'] . ' (' . $aw['award_category'] . ')
        </li>';
    }

    // Lot
    $bundle_articles = null;
    if ($a['type_id'] == 8) {
        $bundle = EntityManager::prepareAndExecute(
            query: 'SELECT `article_title`, `article_authors`, `article_url`, `article_collection`, `link_id` FROM `articles` JOIN `links` USING(`article_id`) WHERE `bundle_id` = :article_id ORDER BY `link_id`',
            params: ['article_id' => $articleEntity->get('id')],
        );
        while ($bu = $bundle->fetch(PDO::FETCH_ASSOC)) {
            $bundle_articles .= '
                <tr id="link_' . $bu['link_id'] . '">
                    <td> ' . $bu["article_title"] . ' </td>
                    <td> ' . $bu["article_authors"] . ' </td>
                    <td> ' . $bu["article_collection"] . ' </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm deleteLink pointer" data-link_id="' . $bu['link_id'] . '">
                            <i aria-label="Supprimer" class="fa-solid fa-chain-broken"></i>
                        </button> 
                    </td>
                </tr>
            ';
        }
    }

    // ** VALIDATION ** //

    // Add a copy button if not virtual stock
    $submit_and_stock = '<br>
    <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_stock">
        Valider et ajouter un exemplaire
    </button>';
    if ($currentSite->getOption('virtual_stock')) {
        $submit_and_stock = null;
    }

    $lemonInkIdField = "";
    if ($articleEntity->isDownloadable() && $config->get('lemonink.api_key')) {
        $lemonInkIdField = '
          <br />
          <div class="form-group">
            <label for="lemonink_master_id">Identifiant LemonInk :</label><br />
            <small class="form-text text-muted">
              Pour configurer le téléchargement avec tatouage numérique, 
              <a href="https://www.lemonink.co/masters" target="_blank">ajouter l\'article sur LemonInk</a>
              et coller son identifiant dans ce champ.
            </small>
            <input type="text" class="form-control" id="lemonink_master_id" name="lemonink_master_id" value="' . $articleEntity->get("lemonink_master_id") . '" />
          </div>
        ';
    }

    /** @noinspection DuplicatedCode */
    $content .= $templateService->renderFromString('
        <form id="createCollection" class="event hidden">
            <fieldset>
                <p>Si l’éditeur n’a pas de collection ou si le livre est «&nbsp;hors collection&nbsp;», indiquer le nom de l’éditeur à l’identique dans le champ collection.</p>
                <label class="floating" for="collection_name">Collection :</label>
                <input type="text" id="collection_name" name="collection_name" class="long" required />
                <br />
                <label class="floating" for="collection_publisher">Éditeur :</label>
                ' . $createCollectionPublisher . '
                <br />
            </fieldset>
        </form>
    
        <form id="create_people" class="e createPeopleForm hidden">
            <fieldset>
                <label class="floating" for="people_first_name">Prénom :</label>
                <input type="text" id="people_first_name" name="people_first_name" class="long" />
                <br />
                <label class="floating" for="people_last_name">Nom :</label>
                <input type="text" id="people_last_name" name="people_last_name" class="long" required />
            </fieldset>
        </form>
    
        <form id="create_price" class="hidden">
            <fieldset>
                <label class="floating" for="price_cat">Catégorie :</label>
                <input type="text" id="price_cat" name="price_cat" class="short" />
                <br />
                <label class="floating" for="price_amount">Prix :</label>
                <input type="number" id="price_amount" name="price_amount" class="short" required /> centimes
                <br />
                <div class="right"><input type="submit" value="Valider" id="createPriceSubmit" /></div>
            </fieldset>
        </form>
    
        <form id="add_award" class="event hidden">
            <fieldset>
                <label class="floating" for="award_name">Récompense :</label>
                <input type="text" id="award_name" name="award_name" class="long" required />
                <br />
                <label class="floating" for="award_year">Année :</label>
                <input type="number" id="award_year" name="award_year" class="short" required />
                <br />
                <label class="floating" for="award_category">Catégorie :</label>
                <input type="text" id="award_category" name="award_category" class="long" />
                <br />
                <label class="floating" for="award_note">Note :</label>
                <input type="text" id="award_note" name="award_note" class="long" />
                <br />
                <div class="right"><input type="submit" value="Ajouter" id="addAwardSubmit"></div>
            </fieldset>
        </form>
    
        <form 
          id="article_form" 
          action="/pages/article_edit?id={{ article.id }}" 
          method="post" 
          enctype="multipart/form-data" 
          class="fieldset check event" 
          data-mode="{{ form_mode }}"
          data-uploading=0 
          data-submitted=0
        >
    
            <fieldset>
                <legend>Importation</legend>
                <div id="article_import">
                    {% if form_mode == "insert" %}
                      <h4 class="center">Entrez un ISBN ou un titre <br />pour rechercher dans les bases externes...</h4>
                    {% else %}
                      <div class="center"><a class="btn btn-outline-secondary reimport event">Réimporter la fiche depuis la base externe...</a></div>
                    {% endif %}
                </div>
            </fieldset>
    
            <fieldset class="px-5">
                <legend>L\'essentiel</legend>
    
                <div class="form-group row">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_id">Article n°</label>
                  <input type="text" id="article_id" name="article_id" value="{{ article.id }}" class="form-control col-md-2 col-lg-1" readonly required />
                </div>
    
                <div class="form-group row">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="type_id">Type</label>
                  <select class="form-control col-md-4 col-lg-3" name="type_id" id="type_id" required>
                    ' . join($type_options) . '
                  </select>
                </div>
    
                <div class="form-group row" id="article_ean_div">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_ean">EAN/ISBN</label>
                  <input id="article_ean" name="article_ean" value="{{ article.ean }}" class="form-control col-md-4 col-lg-3 event article_ean" ' . $article_ean_required . ' autofocus />
                </div>
    
                <div class="form-group row mb-1">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_title">Titre</label>
                  <input type="text" id="article_title" name="article_title" value="{{ article.title }}" class="form-control col-md-9 article_duplicate_check event' . $article_title_class . '" required />
                </div>
    
                <div class="form-group row mb-1">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_authors">Auteur·ices</label>
                  <input type="text" id="article_authors" value="{{ article.authors }}" class="form-control col-md-9" tabindex="-1" readonly required maxlength=256 data-toggle="popover" data-trigger="focus" title="Liste des auteurs et autrices" data-content="Champ rempli automatiquement. Utilisez la section Contributions (ci-dessous) pour ajouter ou supprimer un auteur ou une autrice.">
                </div>
    
                <div class="form-group row mb-1">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_collection">Collection</label>
                  ' . $collectionField . '
                  <span class="col-md-2 col-lg-1 col-form-label text-md-right">n°</span> 
                  <input type="text" id="article_number" name="article_number" value="{{ article.number }}" class="form-control col-md-1 article_duplicate_check event" />
                </div>
                
                <div class="form-group row">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_cycle">Cycle</label>
                  ' . $cycleField . '
                  <span class="col-md-2 col-lg-1 col-form-label text-md-right">tome</span> 
                  <input type="text" id="article_tome" name="article_tome" value="{{ article.tome }}" class="form-control col-md-1" maxlength="12" />
                </div>
    
                <div class="form-group row mb-1">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_availability_dilicom">Disponibilité</label>
                  <select id="article_availability_dilicom" name="article_availability_dilicom" class="form-control col-md-7 col-lg-6" required>
                      ' . implode($availability_options) . '
                  </select>
                </div>
                
                <div class="form-group row">
                  <div class="form-check offset-md-3 offset-lg-2 col-md-10">
                    <input class="form-check-input" type="checkbox" value="1" id="article_preorder" name="article_preorder" '.$preorderChecked.'>
                    <label class="form-check-label" for="article_preorder">
                      Autoriser la précommande
                    </label>
                  </div>
                </div>
    
                <div id="article_category_div" class="form-group row mb-1 ' . $category_field_class . '">
                  <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_category">Catégorie</label>
                  <input type="text" id="article_category" name="article_category" value="{{ article.category }}" class="form-control col-md-2 col-lg-1" title="Appuyez sur la touche &darr; pour faire apparaître la liste des catégories." />
                </div>
                
                <div class="form-group row mb-1">
                    <label class="col-md-3 col-lg-2 col-form-label text-md-right" for="article_price">Prix</label>
                    <input type="number" step="1" id="article_price" name="article_price" value="{{ article.price }}" class="form-control col-md-2 col-lg-1" required 
                        ' . $article_price_readonly . ' data-html="true" data-toggle="popover" data-trigger="focus" data-content="Entrez le prix de l\'article en centimes. Par exemple, pour article à 14,00 €, entrez <strong>1400</strong> ; pour un article à 8,50 €, entrez <strong>850</strong>."
                    > 
                    <span class="col-md-1 col-form-label">
                      centimes
                    </span>
                </div>
    
                <div class="form-group row mb-1">
                  <div class="form-check offset-md-3 offset-lg-2 col-md-10">
                    <input class="form-check-input" type="checkbox" name="article_price_editable" id="article_price_editable" value="1"' . ($articleEntity->has('price_editable') ? ' checked' : null) . '>
                    <label class="form-check-label" for="article_price_editable">Autoriser les client·es à payer plus (prix libre)</label>
                  </div>
                </div>
                <br />
            </fieldset>
    
            <fieldset id="duplicates_fieldset" class="hidden pointer">
                <legend id="toggle_duplicates" class="toggleThis">Doublons potentiels</legend>
                <ul id="duplicates">
                </ul>
            </fieldset>
    
            <fieldset id="bundle_fieldset" class="' . $bundle_fieldset_class . '">
                <legend>Contenu du lot</legend>
                <table class="table" id="bundle_articles">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur·ice·s</th>
                        <th>Collection</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    ' . $bundle_articles . '
                </tbody>
                </tfoot>
                    <tr>
                        <td colspan="4">
                            <input type="text" id="addToBundle" class="form-control" placeholder="Titre de l’article à ajouter..." />
                        </td>
                    </tr>
                </tfoot>
                </table>
                </ul>
            </fieldset>
    
            <fieldset id="Contributeurs">
                <a href="https://docs.biblys.fr/administrer/catalogue/fiche-article/#contributeurs" target="_blank" class="float-right">Besoin d\'aide ?</a>
                <legend>Contributions</legend>
                <div id="people_list"></div>
                <br /><br />
    
                <label class="floating" for="article_people">Ajouter :</label>
                <input type="text" id="article_people" placeholder="Rechercher par nom ou prénom…" class="long" />
            </fieldset>
    
            <fieldset>
                <legend>Identifiants et liens</legend>
    
                <label class="floating" for="article_url">URL Biblys :</label>
                <input type="text" id="article_url" name="article_url" value="' . $a['article_url'] . '" pattern="^[a-z0-9-_]+/[a-z0-9-_]+$" title="Dans le doute, laisser ce champ vide." placeholder="Champ rempli automatiquement" class="long" pattern="^((?!http).)*$" />
                <br /><br />
    
                <label class="floating" for="article_textid">TextID :</label>
                <input type="text" id="article_textid" name="article_textid" value="' . $a['article_textid'] . '" class="verylong" maxlength=32>
                <br />
                <label class="floating" for="article_item">Item n°</label>
                <input type="text" id="article_item" name="article_item" value="' . $a['article_item'] . '" class="mini article_duplicate_check event"  />
                <br />
                <label class="floating" for="article_link_to">Lier à l\'article n°</label>
                <input type="number" id="article_link_to" name="article_link_to" class="mini" />
                <br />
                <label class="floating" for="article_source_id">Extrait de l\'article n°</label>
                <input type="number" name="article_source_id" value="' . $a['article_source_id'] . '" class="mini" />
                <br /><br />
    
                <label class="floating" for="article_noosfere_id">Ref. nooSFere :</label>
                <input type="text" id="article_noosfere_id" name="article_noosfere_id" value="' . $a['article_noosfere_id'] . '" class="article_duplicate_check event" />
                <br />
                <label class="floating" for="article_asin">Ref. Amazon :</label>
                <input type="text" id="article_asin" name="article_asin" value="' . $a['article_asin'] . '" class="article_duplicate_check event" />
                <br /><br />
    
                <label class="floating" for="article_ean_others">Autres ISBN :</label>
                <textarea id="article_ean_others" name="article_ean_others" class="small" title="ISBN de précédentes éditions dans la méme collection, séparés par un espace.">' . $a['article_ean_others'] . '</textarea>
                <br /><br />
            </fieldset>
    
            <fieldset>
                <legend>Données bibliographiques</legend>
    
                <label class="floating" for="article_title_alphabetic">Titre pour le tri :</label>
                <input type="text" id="article_title_alphabetic" name="article_title_alphabetic" value="' . ($articleEntity->has("article_title_alphabetic") ? htmlspecialchars($a['article_title_alphabetic']) : "") . '" class="long" placeholder="Champ rempli automatiquement" />
                <br />
                <label class="floating" for="article_subtitle">Sous-titre :</label>
                <input type="text" id="article_subtitle" name="article_subtitle" id="article_subtitle" value="' . ($articleEntity->has("article_subtitle") ? htmlspecialchars($a['article_subtitle']) : "") . '" class="long" />
                <br />
                <label class="floating" for="article_title_original">Titre original :</label>
                <input type="text" id="article_title_original" name="article_title_original" value="' . ($articleEntity->has("article_title_original") ? htmlspecialchars($a['article_title_original']) : "") . '" class="long" />
                <br />
                <label class="floating" for="article_title_others">Autres titres :</label>
                <input type="text" id="article_title_others" name="article_title_others" value="' . ($articleEntity->has("article_title_others") ? htmlspecialchars($a['article_title_others']) : "") . '" class="long" />
                <br /><br />
    
                <label class="floating" for="article_copyright">Copyright :</label>
                <input type="number" id="article_copyright" name="article_copyright" value="' . $a['article_copyright'] . '" step="1" min="1800" max="' . date('Y') . '+5" placeholder="AAAA" class="mini" />
                <br />
                <label class="floating" for="article_pubdate">Date de parution :</label>
                <input type="date" id="article_pubdate" name="article_pubdate" value="' . $a['article_pubdate'] . '" placeholder="AAAA-MM-JJ" />
                <br /><br />
    
                <p>
                    <label class="floating" for="article_lang_current">Langue actuelle :</label>
                    <select name="article_lang_current" id="article_lang_current">' . $lang_current_options . '</select>
                </p>
                <p>
                    <label class="floating" for="article_lang_original">Langue d\'origine :</label>
                    <select name="article_lang_original" id="article_lang_original">' . $lang_original_options . '</select>
                </p>
                <p>
                    <label class="floating" for="article_origin_country">Pays d\'origine :</label>
                    <select name="article_origin_country" id="article_origin_country">
                        <option value="0"></option>
                        ' . join($origin_country_options) . '
                    </select>
                </p>
                <br />
                <p>
    
                    <label class="floating" for="article_age_min">Lectorat :</label>
                    <input type="number" id="article_age_min"
                        name="article_age_min" value="' . $a['article_age_min'] . '"
                        step="1" min="1" max="99" class="mini" /> ans à
                    <input type="number" id="article_age_max"
                        name="article_age_max" value="' . $a['article_age_max'] . '"
                        step="1" min="1" max="99" class="mini" /> ans
                </p>
    
            </fieldset>
    
            <fieldset>
                <legend>Objet</legend>
    
                <p>
                    <label class="floating" for="article_shaping">Façonnage :</label>
                    <select id="article_shaping" name="article_shaping">
                        <option>' . $a['article_shaping'] . '</option>
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
                    <label class="floating" for="article_printing_process">Impression :</label>
                    <select id="article_printing_process" name="article_printing_process">
                        <option>' . $a['article_printing_process'] . '</option>
                        <option />
                        <option>quadrichromie</option>
                    </select>
                </p>
    
                <p>
                    <label class="floating" for="article_format">Format :</label>
                    <input type="text" id="article_format" name="article_format" value="' . ($articleEntity->has("format") ? htmlspecialchars($articleEntity->get('format')) : "") . '" placeholder="10 x 18 cm" class="medium">
                </p>
    
                <p>
                    <label class="floating" for="article_pages">Nombre de pages :</label>
                    <input type="number" step="1" id="article_pages" name="article_pages" value="' . $a['article_pages'] . '" class="mini" />
                </p>
    
                <p>
                    <label class="floating" for="article_weight">Poids :</label>
                    <input type="number" step="1" id="article_weight" name="article_weight" value="' . $a['article_weight'] . '" class="mini" /> grammes
                </p>
            </fieldset>
    
            <fieldset>
                <legend>Images</legend>
    
                <p title="Image au format JPEG (recommandé), PNG ou WebP">
                    <label for="article_cover_upload">Couverture :</label>
                    ' . $article_cover_upload . '<input type="hidden" id="article_cover_import" name="article_cover_import" placeholder="Adresse de l\'image...">
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
                        ' . $files_table . '
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
    
                '.$lemonInkIdField.'
            </fieldset>
    
            <fieldset>
                <legend>Classifications</legend>
    
                <h3>Mots-clés</h3>
                <ul id="the_tags">
                    ' . $the_tags . '
                </ul>
    
                <label class="floating" for="article_theme_bisac">Ajouter :</label>
                <textarea id="add_tags_input" class="medium" placeholder="Entrer un ou plusieurs mots-clés, séparés par des virgules...">' . $default_tags . '</textarea>
                <a id="add_tags_button" class="btn btn-primary">Ajouter</a>
    
                <br /><br />
    
                <label class="floating" for="article_theme_bisac">BISAC :</label>
                <input type="text" id="article_theme_bisac" name="article_theme_bisac" value="' . $a['article_theme_bisac'] . '" class="medium" maxlength="16">
                <br />
                <label class="floating" for="article_theme_clil">CLIL :</label>
                <input type="text" id="article_theme_clil" name="article_theme_clil" value="' . $a['article_theme_clil'] . '" class="medium" maxlength="16">
                <br />
                <label class="floating" for="article_theme_dewey">Dewey :</label>
                <input type="text" id="article_theme_dewey" name="article_theme_dewey" value="' . $a['article_theme_dewey'] . '" class="medium" maxlength="16">
                <br />
                <label class="floating" for="article_theme_electre">Electre :</label>
                <input type="text" id="article_theme_electre" name="article_theme_electre" value="' . $a['article_theme_electre'] . '" class="medium" maxlength="16">
                <br />
            </fieldset>
    
            <fieldset>
                <legend>Rayons</legend>
    
                <h3>Rayons actuels</h3>
                <ul id="rayons">' . $currentArticleCategories . '</ul>
                <br>
    
                ' . $rayons_options . '
            </fieldset>
    
            <fieldset>
                <legend>Récompenses littéraires</legend>
                <ul id="awards">
                    ' . $the_awards . '
                </ul>
                <div class="center">
                    <br />
                    <a id="addAward" class="btn btn-outline-secondary">
                        Ajouter une récompense
                    </a>
                </div>
            </fieldset>
    
            <fieldset class="collapsable-fieldset"
                data-collapsed="' . ($articleEntity->has('summary') ? 'false' : 'true') . '">
                <legend>
                    Quatrième de couverture&nbsp;
                    <span class="fa fa-plus-square"></span>
                </legend>
                <div class="collapsable-element">
                    <textarea id="article_summary" name="article_summary"
                        class="wysiwyg">' . $a['article_summary'] . '</textarea>
                </div>
            </fieldset>
    
            <fieldset class="collapsable-fieldset"
                data-collapsed="' . ($articleEntity->has('contents') ? 'false' : 'true') . '">
                <legend>
                    Sommaire&nbsp;
                    <span class="fa fa-plus-square"></span>
                </legend>
                <div class="collapsable-element">
                    <textarea id="article_contents" name="article_contents"
                        class="wysiwyg">' . $a['article_contents'] . '</textarea>
                </div>
            </fieldset>
    
            <fieldset class="collapsable-fieldset ' . $bonus_fieldset_class . '"
                data-collapsed="' . ($articleEntity->has('bonus') ? 'false' : 'true') . '">
                <legend>
                    Bonus&nbsp;
                    <span class="fa fa-plus-square"></span>
                </legend>
                <div class="collapsable-element">
                    <textarea id="article_bonus" name="article_bonus"
                        class="wysiwyg">' . $a['article_bonus'] . '</textarea>
                </div>
            </fieldset>
    
            <fieldset class="collapsable-fieldset"
                data-collapsed="' . ($articleEntity->has('catchline') ? 'false' : 'true') . '">
                <legend>
                    Accroche&nbsp;
                    <span class="fa fa-plus-square"></span>
                </legend>
                <div class="collapsable-element">
                    <textarea id="article_catchline" name="article_catchline"
                        class="wysiwyg">' . $articleEntity->get('catchline') . '</textarea>
                </div>
            </fieldset>
    
            <fieldset class="collapsable-fieldset"
                data-collapsed="' . ($articleEntity->has('biography') ? 'false' : 'true') . '">
                <legend>
                    Notice biographique&nbsp;
                    <span class="fa fa-plus-square"></span>
                </legend>
                <div class="collapsable-element">
                    <textarea id="article_biography" name="article_biography"
                        class="wysiwyg">' . $articleEntity->get('biography') . '</textarea>
                </div>
            </fieldset>
    
            <fieldset>
                <legend>Validation</legend>
                <div class="admin">
                    <p>Validation</p>
                    <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_show">Valider et aller à la fiche</button>
                    <br>
                    <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_new">Valider et créer une autre fiche</button>
                    ' . $submit_and_stock . '
                    <p><a href="https://docs.biblys.fr/administrer/catalogue/fiche-article/">Documentation</a></p>
                </div>
                <p class="center">
                    <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_show">Valider et aller à la fiche</button>
                    <br>
                    <button class="btn btn-primary btn-sm" type="submit" name="article_submit_and_new">Valider et créer une autre fiche</button>
                    ' . $submit_and_stock . '
                </p>
            </fieldset>
    
            <fieldset>
                <legend>Suppression</legend>
                <p class="text-center">
                    <a class="btn btn-danger" href=' . $urlGenerator->generate('article_delete', ['id' => $articleEntity->get('id')]) . '>
                        <span class="fa fa-trash-can"></span>
                        Supprimer définitivement cet article
                    </a>
                </p>
            </fieldset>
    
            <fieldset>
                <legend>Base de données</legend>
    
                <label class="floating" for="article_created" class="readonly">Fiche créée le :</label>
                <input type="text" name="article_created" id="article_created" value="' . $a['article_created'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
                <br />
                <label class="floating" for="article_updated" class="readonly">Fiche modifiée le :</label>
                <input type="text" name="article_updated" id="article_updated" value="' . $a['article_updated'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
                <br /><br />
    
                <label class="floating" for="article_keywords" class="disabled">Termes de recherche :</label>
                <textarea id="article_keywords" name="article_keywords" class="large" placeholder="Utilisés par le moteur de recherche et générés automatiquement. Pour ajouter manuellement un terme de recherche personnalisé (par exemple thématique), utilisez les mots-clés." disabled>' . $a['article_keywords'] . '</textarea>
                <br /><br />
    
                <label class="floating" for="article_links" class="disabled">Liaisons :</label>
                <textarea id="article_links" name="article_links" class="large" disabled>' . $a['article_links'] . '</textarea>
                <br /><br />
            </fieldset>
    
        </form>
    ', [
        "article" => $article,
        "form_mode" => $formMode,
    ]);

    return new Response($content);
};
