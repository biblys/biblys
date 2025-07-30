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
use Model\CycleQuery;
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

        $articleEntity->set("article_cycle", "");
        $cycle = CycleQuery::create()->findPk($request->request->get("cycle_id"));
        if ($cycle) {
            $articleEntity->set("cycle_id", $cycle->getId());
            $articleEntity->set("article_cycle", $cycle->getName());
        }

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

    $autoImport = false;

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
                $autoImport = true;
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
    $bundleFieldsetClass = 'hidden';

    $typeOptions = ArticleType::getOptions($articleEntity->get('type_id'));

    if ($a['type_id'] == 8) {
        $bundleFieldsetClass = null;
    }

    // Collection
    $categoryFieldClass = 'hidden';
    $articlePriceReadonly = null;
    if (!empty($a['collection_id'])) {
        $collections = EntityManager::prepareAndExecute(
            'SELECT `collection_name`, `site_id`, `pricegrid_id`, `publisher_id` FROM `collections` WHERE `collection_id` = :collection_id LIMIT 1',
            ["collection_id" => $articleEntity->get("collection_id")],
        );
        $c = $collections->fetch(PDO::FETCH_ASSOC);
        $a['publisher_id'] = $c['publisher_id'];
        if (!empty($c['pricegrid_id'])) {
            $categoryFieldClass = '';
            $articlePriceReadonly = 'readonly title="Choisissez la catégorie correspondante."';
        }
    }

    // Availability
    $availabilityOptions = [];
    foreach (Article::$AVAILABILITY_DILICOM_VALUES as $key => $value) {
        $availabilityOptions[] = "<option value=\"$key\"" . ($articleEntity->get('availability_dilicom') == $key ? 'selected' : null) . ">$value</option>";
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
    $bundleArticles = null;
    if ($a['type_id'] == 8) {
        $bundle = EntityManager::prepareAndExecute(
            query: 'SELECT `article_title`, `article_authors`, `article_url`, `article_collection`, `link_id` FROM `articles` JOIN `links` USING(`article_id`) WHERE `bundle_id` = :article_id ORDER BY `link_id`',
            params: ['article_id' => $articleEntity->get('id')],
        );
        while ($bu = $bundle->fetch(PDO::FETCH_ASSOC)) {
            $bundleArticles .= '
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
    /** @noinspection HtmlUnknownAttribute */
    $content .= $templateService->render('AppBundle:Legacy:article-edit.html.twig', [
        "article" => $article,
        "form_mode" => $formMode,
        "auto_import" => $autoImport,
        "create_collection_publisher" => $createCollectionPublisher,
        "article_type_options" => join($typeOptions),
        "collection_field" => $collectionField,
        "availability_options" => implode($availabilityOptions),
        "preorder_checked" => $preorderChecked,
        "category_field_class" => $categoryFieldClass,
        "article_price_readonly" => $articlePriceReadonly,
        "article_price_editable" => $article->isPriceEditable() ? 'checked' : null,
        "bundle_fieldset_class" => $bundleFieldsetClass,
        "bundle_articles" => $bundleArticles,
        "lang_current_options" => $lang_current_options,
        "lang_original_options" => $lang_original_options,
        "origin_country_options" => join($origin_country_options),
        "article_cover_upload" => $article_cover_upload,
        "files_table" => $files_table,
        "lemonink_id_field" => $lemonInkIdField,
        "the_tags" => $the_tags,
        "default_tags" => $default_tags,
        "current_article_categories" => $currentArticleCategories,
        "rayons_options" => $rayons_options,
        "the_awards" => $the_awards,
        "article_has_summary" => $article->getSummary() ? 'false' : 'true',
        "article_has_contents" => $article->getContents() ? 'false' : 'true',
        "article_has_bonus" => $article->getBonus() ? 'false' : 'true',
        "article_has_catchline" => $article->getCatchline() ? 'false' : 'true',
        "article_has_biography" => $article->getBiography() ? 'false' : 'true',
        "submit_and_stock" => $submit_and_stock,
    ]);

    return new Response($content);
};
