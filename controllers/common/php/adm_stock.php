<?php

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Model\AlertQuery;
use Model\ArticleQuery;
use Model\CartQuery;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session as CurrentSession;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request              $request,
    CurrentSession       $session,
    CurrentSite          $currentSite,
    Mailer               $mailer,
    UrlGenerator         $urlgenerator,
    FlashMessagesService $flashMessagesService,
    ImagesService        $imagesService,
    TemplateService      $templateService,
): Response|RedirectResponse {
    $sm = new StockManager();
    $am = new ArticleManager();
    $om = new OrderManager();
    $rm = new RayonManager();

    $content = null;

    $div_admin = null;

    $returnedStockId = $request->query->get('return');
    if ($returnedStockId) {
        /** @var Stock $stockEntity */
        $stockEntity = $sm->getById($returnedStockId);
        $stockEntity->setReturned();
        $sm->update($stockEntity);
        return new RedirectResponse('/pages/adm_stock?id=' . $_GET['return'] . '&returned=1');
    }

    $lostCopyId = $request->query->get('lost');
    if ($lostCopyId) {
        $lostCopy = $sm->getById($lostCopyId);
        $lostCopy->set('stock_lost_date', date('Y-m-d H:i:s'))
            ->set('stock_return_date', null)
            ->set('stock_cart_date', null)
            ->set('stock_selling_date', null);
        $sm->update($lostCopy);
        $session->getFlashBag()->add('success', 'L\'exemplaire n° ' . $lostCopyId . ' a été marqué comme perdu.');
        return new RedirectResponse('/pages/adm_stock?id=' . $lostCopyId);
    }

    if (isset($_GET['sold'])) {
        // All copies sold in shop are associated to a fake customer that needs
        // to be created and specified via the `fake_shop_customer` site option.
        // This allows to create only one order for shop sales per day.
        $fakeCustomerId = $currentSite->getOption('fake_shop_customer');
        if (!$fakeCustomerId) {
            throw new Exception("L'option de site `fake_shop_customer` doit être définie.");
        }

        if ($stockEntity = $sm->get(['stock_id' => $_GET['sold']])) {
            if (!$stockEntity->isAvailable()) {
                trigger_error('Exemplaire indisponible.');
            } else {
                try {
                    // Get order for current day shop sales if it exists
                    $orderDate = date('Y-m-d') . ' 00:00:00';
                    $order = $om->get([
                        'customer_id' => $fakeCustomerId,
                        'order_created' => $orderDate,
                    ]);

                    // Else, create a new one
                    if (!$order) {
                        $order = $om->create();
                        $order->set('order_created', $orderDate)
                            ->set('customer_id', $fakeCustomerId)
                            ->set('order_type', 'shop')
                            ->set('order_payment_date', $orderDate);
                        $om->update($order);
                    }

                    // Add the copy to the order
                    $om->addStock($order, $stockEntity);

                    // Update the order amount from stock
                    $om->updateFromStock($order);
                } catch (Exception $e) {
                    trigger_error($e->getMessage());
                }

                return new RedirectResponse('/pages/adm_stock?id=' . $stockEntity->get('id') . '&solded=1');
            }
        } else {
            trigger_error('Exemplaire introuvable');
        }
    }

    $mode = "";
    if ($request->getMethod() === 'POST') {
        // AddSlashes
        foreach ($_POST as $key => $value) {
            $_POST[$key] = addslashes($value);
        }

        // Get related article
        $article_id = $request->request->get('article_id');
        /** @var Article $articleModel */
        $articleModel = $am->getById($article_id);
        if (!$articleModel) {
            throw new Exception("Cannot find article with id $article_id");
        }

        // Add to rayon
        $rayon_id = $request->request->get('add_to_rayon');
        if ($rayon_id) {
            /** @var Rayon $rayon */
            $rayon = $rm->getById($rayon_id);
            if (!$rayon) {
                throw new Exception("Cannot find rayon with id $rayon_id");
            }
            $rayon->addArticle($articleModel);
            $session->getFlashBag()->add('info', 'L\'article <strong>' . $articleModel->get('title') . '</strong> a été ajouté au rayon <strong>' . $rayon->get('name') . '</strong>.');
        }

        $stockId = $request->request->get("stock_id");
        for ($i = 0; $i < $_POST['stock_num']; ++$i) {
            // Creation de l'exemplaire
            if (empty($stockId) or $_POST['stock_num'] > 1) {
                $stockEntity = $sm->create();
                $stockId = $stockEntity->get('id');
                $mode = 'insert';
            } else {
                $mode = 'update';
            }

            $stockEntity = $sm->getById($stockId);
            $stock = StockQuery::create()->findPk($stockId);

            // Suppression de la photo
            if (isset($_POST['delete_photo']) && $_POST['delete_photo'] == 1) {
                if ($imagesService->imageExistsFor($stock)) {
                    $imagesService->deleteImageFor($stock);
                    $flashMessagesService->add("success", "La photo de l'exemplaire a été supprimée.");
                }
            }

            // Update copy
            $stockEntity->set('article_id', $request->request->get('article_id'))
                ->set('stock_invoice', $request->request->get('stock_invoice'))
                ->set('stock_depot', $request->request->get('stock_depot'))
                ->set('stock_stockage', $request->request->get('stock_stockage'))
                ->set('stock_condition', $request->request->get('stock_condition'))
                ->set('stock_condition_details', $request->request->get('stock_condition_details'))
                ->set('stock_purchase_price', $request->request->get('stock_purchase_price'))
                ->set('stock_selling_price', $request->request->get('stock_selling_price'))
                ->set('stock_selling_price_saved', $request->request->get('stock_selling_price_saved'))
                ->set('stock_weight', $request->request->get('stock_weight'))
                ->set('stock_pub_year', $request->request->get('stock_pub_year'))
                ->set('stock_purchase_date', $request->request->get('stock_purchase_date'))
                ->set('stock_onsale_date', $request->request->get('stock_onsale_date'))
                ->set('stock_selling_date', $request->request->get('stock_selling_date'))
                ->set('stock_cart_date', $request->request->get('stock_cart_date'))
                ->set('stock_return_date', $request->request->get('stock_return_date'))
                ->set('stock_lost_date', $request->request->get('stock_lost_date'))
                ->set('stock_condition', $request->request->get('stock_condition'))
                ->set('cart_id', $request->request->get('cart_id'))
                ->set('order_id', $request->request->get('order_id'));

            // Update campaign_id
            $campaign_id = (int)$request->request->get('campaign_id');
            $stockEntity->set('campaign_id', $campaign_id);

            // Update reward_id
            $reward_id = (int)$request->request->get('reward_id');
            $stockEntity->set('reward_id', $reward_id);

            // Persist copy
            $sm->update($stockEntity);

            // Calculate tax
            $stockEntity = $sm->calculateTax($stockEntity);
            $sm->update($stockEntity);

            // Update related order
            $orderId = $stockEntity->get("order_id");
            if ($orderId) {
                /** @var Order $order */
                $order = $om->getById($orderId);
                if ($order) {
                    $om->updateFromStock($order);
                }
                $session->getFlashBag()->add('success', "Le montant de la commande n° $orderId a été mis à jour.");
            }

            // Update related campaign
            if ($campaign_id) {
                $cfcm = new CFCampaignManager();
                $campaign = $cfcm->getById($campaign_id);
                if ($campaign) {
                    $cfcm->updateFromSales($campaign);
                    $session->getFlashBag()->add('success', 'La campagne <strong>' . $campaign->get('title') . '</strong> a été mise à jour.');
                }
            }

            // Upload de la photo de l'exemplaire
            if (!empty($_FILES['upload_photo']['tmp_name'])) {
                if ($imagesService->imageExistsFor($stock)) {
                    $imagesService->deleteImageFor($stock);
                }
                $imagesService->addImageFor($stock, $_FILES['upload_photo']['tmp_name']);
            }
        }

        // Flash messages
        if ($mode == 'update') {
            /** @var Stock $stockEntity */
            $flashMessagesService->add("success",
                "L'exemplaire n° {$stockEntity->get('id')} a été mis à jour."
            );
        } elseif ($_POST['stock_num'] == 1) {
            $flashMessagesService->add("success",
                "Un exemplaire de {$articleModel->get('title')} a été ajouté au stock."
            );
        } else {
            $flashMessagesService->add("success",
                "{$_POST['stock_num']} exemplaires de {$articleModel->get('title')} ont été ajoutés au stock."
            );
        }

        // Update CFReward if necessary
        $rewards = $articleModel->getCFRewards();
        if ($rewards) {
            $cfrm = new CFRewardManager();
            $rewards_updated = 0;

            foreach ($rewards as $reward) {
                $cfrm->updateQuantity($reward);
                ++$rewards_updated;
            }

            if ($rewards_updated) {
                $session->getFlashBag()->add('info', 'Les quantités de <strong>' . $rewards_updated . ' contrepartie' . s($rewards_updated) . '</strong> ont été mises à jour.');
            }
        }

        // Update article weight if different from stock weight
        if (!empty($_POST['stock_weight']) && $articleModel->get('weight') != $_POST['stock_weight']) {
            $articleModel->set('article_weight', $_POST['stock_weight']);
            $am->update($articleModel);
            $session->getFlashBag()->add('info', "Le poids de l'article <strong>" . $articleModel->get('title') . '</strong> a été mis à <strong>' . $articleModel->get('weight') . 'g</strong>.');
        }

        $copyCondition = $request->request->get("stock_condition");
        if (_shouldAlertsBeSent($mode, $copyCondition, $currentSite)) {
            /** @var PDO $_SQL */
            /** @var Mailer $mailer */
            $copyYear = $request->request->get("stock_pub_year");
            $copyPrice = $request->request->get("stock_selling_price");
            $result = _sendAlertsForArticle($articleModel, $copyYear, $copyPrice, $copyCondition, $mailer, $currentSite);
            if ($result["sent"] > 0) {
                $session->getFlashBag()->add(
                    "info",
                    $result["sent"] . ' alerte' . s($result["sent"]) . " " . s($result["sent"], 'a', 'ont') . ' été envoyée' . s($result["sent"]) . '.');
            }
            if (count($result["errors"]) > 0) {
                foreach ($result["errors"] as $error) {
                    $session->getFlashBag()->add(
                        "warning",
                        "L'alerte pour <strong>" . $error["email"] . "</strong> n'a pas pu être envoyée : "
                        . $error["reason"]
                    );
                }
            }
        }

        return new RedirectResponse('/pages/adm_stock?id=' . $stockId);
    }

    $photo_field = null;

    $copyId = $request->query->get('copy');
    $delId = $request->query->get('del');

    // Modifier un exemplaire existant
    if (!empty($_GET['id'])) {
        $request->attributes->set("page_title", "Modifier l'exemplaire n° {$_GET['id']}");
        $content .= "<h1><span class=\"fa fa-cubes\"></span> Modifier l'exemplaire n°{$_GET['id']}</h1>";

        if (isset($_GET['created'])) {
            $content .= '<p class="success">' . $_GET['created'] . ' exemplaire' . s($_GET['created']) . ' ajouté' . s($_GET['created']) . ' au stock !</p>';
        } elseif (isset($_GET['returned'])) {
            $content .= '<p class="success">L\'exemplaire a été retourné.</p>';
        } elseif (isset($_GET['losted'])) {
            $content .= '<p class="success">L\'exemplaire a été marqué comme perdu.</p>';
        } elseif (isset($_GET['solded'])) {
            $content .= '<p class="success">L\'exemplaire a été marqué comme vendu en magasin.</p>';
        }

        if (isset($_GET['alerts'])) {
            $content .= '<p class="success">' . $_GET['alerts'] . ' alerte' . s($_GET['alerts']) . ' ' . s($_GET['alerts'], 'a', 'ont') . ' été envoyée' . s($_GET['alerts']) . '</p>';
        }

        $stockId = $request->query->get('id');
        $stockEntity = $sm->getById($stockId);
        $stock = StockQuery::create()->findPk($stockId);

        if (!$stockEntity) {
            throw new Exception('Cet exemplaire n\'existe pas');
        }
        $mode = 'update';

        if ($stockEntity['stock_pub_year'] == 0) {
            $stockEntity['stock_pub_year'] = null;
        }

        // Photo
        /** @var Stock $stockEntity */
        if ($imagesService->imageExistsFor($stock)) {
            $photoThumbnailUrl = $imagesService->getImageUrlFor($stock, width: 250);
            $photoUrl = $imagesService->getImageUrlFor($stock);
            $photo_field = '
            <div class="floatR center">
                <a href="'.$photoThumbnailUrl.'" rel="lightbox">
                    <img src="' .$photoUrl.'" alt="Photo de l‘exemplaire" width="200">
                </a><br/>
                <input type="checkbox" name="delete_photo" value="1" /> Supprimer
            </div>';
        }

        $div_admin = '
        <p>Exemplaire n&deg; ' . $stockEntity['stock_id'] . '</p>
        <p><a href="/pages/adm_stocks?article_id=' . $stockEntity['article_id'] . '">autres exemplaires</a></p>
        <p><a href="/pages/adm_stock?add=' . $stockEntity['article_id'] . '#add">nouvel exemplaire</a></p>
        <p><a href="/pages/adm_stock?del=' . $stockEntity['stock_id'] . '" data-confirm="Voulez-vous vraiment SUPPRIMER cet exemplaire ?">supprimer</a></p>
    ';
    } elseif (!empty($copyId)) {
        $request->attributes->set("page_title", "Dupliquer l'exemplaire n&deg; $copyId");
        $content .= '<h1><span class="fa fa-copy"></span> Dupliquer l\'exemplaire n<sup>o</sup> ' . $_GET['copy'] . '</h1>';
        $stockEntity = $sm->getById($copyId);
        if (!$stockEntity) {
            throw new Exception('Cet exemplaire n\'existe pas');
        }
        $mode = 'insert';
        $_GET['id'] = null;
    } elseif (!empty($_GET['add'])) { // Ajouter un exemplaire
        $request->attributes->set("page_title", "Ajouter au stock un nouvel exemplaire de...");
        $content .= '<h1 id="add"><span class="fa fa-plus"></span>Ajouter au stock un nouvel exemplaire de...</h1>';
        $stockEntity = new Stock([]);
        $stockEntity['article_id'] = $_GET['add'];
        $mode = 'insert';

        // Default values
        $stockEntity['stock_pub_year'] = null;
        $stockEntity['stock_selling_price_saved'] = null;

        $stockEntity['stock_invoice'] = $currentSite->getOption('default_stock_invoice') ? $currentSite->getOption('default_stock_invoice') : null;
        $stockEntity['stock_stockage'] = $currentSite->getOption('default_stock_stockage') ? $currentSite->getOption('default_stock_stockage') : null;
        $stockEntity['stock_selling_price'] = $currentSite->getOption('default_stock_selling_price') ? $currentSite->getOption('default_stock_selling_price') : null;
        $stockEntity['stock_purchase_price'] = $currentSite->getOption('default_stock_purchase_price') ? $currentSite->getOption('default_stock_purchase_price') : null;
        $stockEntity['stock_condition'] = $currentSite->getOption('default_stock_condition') ? $currentSite->getOption('default_stock_condition') : null;
        $stockEntity['stock_condition_details'] = $currentSite->getOption('default_stock_condition_details') ? $currentSite->getOption('default_stock_condition_details') : null;

        if (!$currentSite->getOption('default_stock_purchase_date')) {
            $stockEntity['stock_purchase_date'] = date('Y-m-d H:i:s');
            $stockEntity['stock_onsale_date'] = date('Y-m-d H:i:s');
        } else {
            $stockEntity['stock_purchase_date'] = $currentSite->getOption('default_stock_purchase_date');
            $stockEntity['stock_onsale_date'] = $currentSite->getOption('default_stock_purchase_date');
        }
        $_GET['id'] = 0;
    } elseif ($delId) {
        $copyToDelete = $sm->getById($delId);
        $sm->delete($copyToDelete);
        $session->getFlashBag()->add('success', 'L\'exemplaire ' . $delId . ' a bien été supprimé.');
        return new RedirectResponse('/pages/adm_stock');
    }

    if (!isset($stockEntity)) {
        $stockEntity = new Stock([]);
    }

    $addParam = $request->query->get('add');
    if ($addParam) {
        $stockEntity->set('article_id', $addParam);
    }

    $article = ArticleQuery::create()->findPk($stockEntity->get("article_id"));
    if (!$article) {
        throw new Exception("Cannot find article with id " . $stockEntity->get("article_id"));
    }

    /** @var Article $articleModel */
    $articleModel = $am->getById($article->getId());
    $a = $articleModel;

    $articleUrl = $urlgenerator->generate(
        'article_show',
        [
            'slug' => $articleModel->get('url'),
        ]
    );
    $articleCover = null;

    if ($imagesService->imageExistsFor($article)) {
        $articleCover = $templateService->render("AppBundle:Article:_cover.html.twig", [
                "article" => $article,
                "height" => 100,
                "class" => "article-thumb-cover",
                "link" => $articleUrl,
            ]
        );
    }

    $content .= '
        <div class="article-thumb">
            ' . $articleCover . '
            <div class="article-thumb-data">
                <h3>
                    <a href="' . $articleUrl . '"> ' . $a['article_title'] . '</a>
                </h3>
                <p>
                    de ' . truncate($a['article_authors'], 65, '...', true, true) . '<br />
                    coll. ' . $a['article_collection'] . ' ' . numero($a['article_number']) . ' (' . $a['article_publisher'] . ')<br />
                    Prix éditeur : ' . price($a['article_price'], 'EUR') . '
                </p>
            </div>
        </div>
    </a>
';

    if (!empty($a['article_weight'])) {
        $article_weight = '<input type="hidden" name="article_weight" value="' . $a['article_weight'] . '">';
    } else {
        $article_weight = null;
    }

    // Pas de poids minimum si livre numérique
    $stock_weight_minimum = 0;
    $weight_required = $currentSite->getOption('weight_required');
    if ($weight_required) {
        $stock_weight_minimum = $weight_required;
    }

    // Autres exemplaires
    if ($mode == 'insert') {
        $exs = null;
        $copies = $articleModel->getStock();
        $in_stock = 0;
        $in_base = 0;
        foreach ($copies as $copy) {
            $os = $copy;
            if (!$os['stock_selling_date'] && !$os['stock_return_date'] && !$os['stock_lost_date']) {
                ++$in_stock;
            }
            ++$in_base;
            $exs .= '
                    <tr>
                        <td><a href="adm_stock?id=' . $os['stock_id'] . '">' . $os['stock_id'] . '</a></td>
                        <td>' . price($os['stock_purchase_price'], 'EUR') . '</td>
                        <td>' . price($os['stock_selling_price'], 'EUR') . '</td>
                        <td>' . $os['stock_condition'] . '</td>
                        <td>' . $os['stock_weight'] . 'g</td>
                        <td>' . $os['stock_pub_year'] . '</td>
                        <td>' . _date($os['stock_purchase_date'], 'd/m/Y') . '</td>
                        <td>' . _date($os['stock_selling_date'], 'd/m/Y') . ' ' . _date($os['stock_return_date'], 'd/m/Y') . '</td>
                    </tr>
        ';
        }
        if (isset($exs)) {
            $content .= '
            <table class="unfold admin-table">
                <thead>
                    <tr>
                        <th colspan="9">
                            <span class="fa fa-chevron-down"></span>
                            ' . $in_base . ' exemplaire' . s($in_base) . ' en base dont ' . $in_stock . ' en stock
                        </th>
                    </tr>
                </thead>
                <tbody id="stockBody">
                    ' . $exs . '
                </tbody>
            </table>
        ';
        }
    }

    // alertes
    if ($mode == 'insert') {
        $alerts = AlertQuery::create()
            ->filterBySite($currentSite->getSite())
            ->_or()
            ->filterByUserId(null, Criteria::ISNULL)
            ->findByArticleId($articleModel->get("id"))
            ->getArrayCopy();
        $alerts = array_map(/**
         * @throws PropelException
         */ function (\Model\Alert $alert) use ($currentSite) {
            $recipientEmail = $alert->getUser()?->getEmail() ?? $alert->getRecipientEmail();
            if (!$recipientEmail) {
                return "";
            }

            return '
            <tr>
                <td>' . $recipientEmail . '</a></td>
            </tr>
        ';
        }, $alerts);
        $alerts = array_filter($alerts, function ($alert) {
            return $alert !== "";
        });
        $alerts_num = count($alerts);

        if ($alerts_num > 0) {
            $disabledAlertsWarning = null;
            if (!$currentSite->hasOptionEnabled("alerts")) {
                $disabledAlertsWarning = '
                <span class="fa fa-exclamation-triangle" title="Les envois d\'alertes sont désactivés."></span>
            ';
            }

            $content .= '
            <table class="unfold admin-table">
                <thead>
                    <tr>
                        <th colspan="9">
                            <span class="fa fa-chevron-down"></span>
                            ' . $alerts_num . ' alerte' . s($alerts_num) . '
                            ' . $disabledAlertsWarning . '
                        </th>
                    </tr>
                </thead>
                <tbody>
                    ' . join($alerts) . '
                </tbody>
            </table>
        ';
        }
    }

    // Fournisseur
    $suppliers = $articleModel->get('publisher')->getSuppliers();
    $supplier = null;
    if ($suppliers) {
        $su = $suppliers[0];
        $supplier = '
        <label for="Fournisseur" class="disabled">Fournisseur :</label>
        <input type="text" name="Fournisseur" id="Fournisseur" value="' . $su['supplier_name'] . '" class="short disabled" disabled />
        <br />
    ';
    }

    // TVA d'après article
    $tva = null;
    $stockEntity['stock_tva'] = 0;
    $TVA = 1 + $stockEntity['stock_tva'] / 100;

    // Prix d'achat par defaut
    if (empty($stockEntity['stock_selling_price']) && $currentSite->getOption('default_stock_discount') && !empty($a['article_price']) and !empty($a['article_tva'])) {
        $stockEntity['stock_purchase_price'] = round($a['article_price'] / $TVA * (100 - $currentSite->getOption('default_stock_discount')) / 100);
        if ($currentSite->getOption('default_stock_super_discount')) {
            $stockEntity['stock_purchase_price'] = round($stockEntity['stock_purchase_price'] * (1 - $currentSite->getOption('default_stock_super_discount') / 100));
        }
        if ($currentSite->getOption('default_stock_cascading_discount')) {
            for ($ir = 0; $ir < $currentSite->getOption('default_stock_cascading_discount'); ++$ir) {
                $stockEntity['stock_purchase_price'] = $stockEntity['stock_purchase_price'] - ($stockEntity['stock_purchase_price'] * 0.01);
            }
            $stockEntity['stock_purchase_price'] = round($stockEntity['stock_purchase_price']);
        }
    }

    // Exemplaire vendu : afficher le prix HT et la TVA
    $tva_fields = null;
    if ($currentSite->getOption('tva') && $stockEntity->has('selling_date')) {
        $tva_fields = '
        <p>
            <label>Prix de vente HT :</label>
            <input type="text" readonly value="' . $stockEntity->get('selling_price_ht') . '" class="mini"> centimes
        </p>
        <p>
            <label>Taux de TVA :</label>
            <input type="text" readonly value="' . $stockEntity->get('tva_rate') . '" class="mini"> %
        </p>
        <p>
            <label>TVA sur PdV :</label>
            <input type="text" readonly value="' . $stockEntity->get('selling_price_tva') . '" class="mini"> centimes
        </p>
        <p>
            <label>Remise recalculée :</label>
            <input type="text" readonly value="' . $stockEntity->getDiscountRate() . '" class="mini"> %
        </p>
    ';
    } elseif ($currentSite->getOption('tva') && $stockEntity->has('id')) {
        $tva_fields = '
        <p>
            <label>Remise recalculée :</label>
            <input type="text" readonly value="' . $stockEntity->getDiscountRate() . '" class="mini"> %
        </p>
    ';
    }

    // Prix de vente par défaut d'après prix éditeur
    if (empty($stockEntity['stock_selling_price']) and !empty($a['article_price'])) {
        $stockEntity['stock_selling_price'] = $a['article_price'];
    }

    // Poids d'après fiche article
    if (empty($stockEntity['stock_weight']) && !$a['collection_incorrect_weights']) {
        $stockEntity['stock_weight'] = $a['article_weight'];
    }

    $invoice = '<input type="text" name="stock_invoice" id="stock_invoice" value="' . $stockEntity['stock_invoice'] . '" />';
    if (empty($stockEntity['stock_invoice'])) {
        $invoicesQuery = EntityManager::prepareAndExecute(
            'SELECT `stock_invoice` FROM `stock` WHERE `site_id` = :site_id
                    GROUP BY `stock_invoice`',
            ['site_id' => $currentSite->getId()]
        );
        $invoices = $invoicesQuery->fetchAll(PDO::FETCH_ASSOC);
        $invoices_options = null;
        foreach ($invoices as $invoice) {
            $invoices_options .= '<option>' . $invoice['stock_invoice'] . '</option>';
        }
        $invoice = '<select name="stock_invoice" id="stock_invoice">' . $invoices_options . '</select>';
    }

    $remises = null;
    if (!empty($currentSite->getOption('default_stock_discount')) and $mode == 'insert') {
        $remises .= '
            <label for="Remise" class="disabled">Remise :</label>
            <input type="text" name="Remise" id="Remise" value="' . $currentSite->getOption('default_stock_discount') . ' %" class="court" disabled />
            <br />
    ';
    }
    if (!empty($currentSite->getOption('default_stock_super_discount')) and $mode == 'insert') {
        $remises .= '
            <label for="Remise2" class="disabled">Sur-remise :</label>
            <input type="text" name="Remise2" id="Remise2" value="' . $currentSite->getOption('default_stock_super_discount') . ' %" class="court" disabled />
            <br />
    ';
    }
    if (!empty($currentSite->getOption('default_stock_cascading_discount')) and $mode == 'insert') {
        $remises .= '
            <label for="Remise3" class="disabled">Remise en cascade :</label>
            <input type="text" name="Remise3" id="Remise3" value="' . $currentSite->getOption('default_stock_cascading_discount') . ' %" class="court" disabled />
            <br />
    ';
    }

    // Add article to rayons
    $rayons = $rm->getAll();
    $rayon_select = null;
    if ($rayons) {
        $rayons_options = array_map(function ($rayon) {
            return '<option value="' . $rayon->get('id') . '">' . $rayon->get('name') . '</option>';
        }, $rayons);
        $rayon_select = '<select name="add_to_rayon"><option value="">Ajouter l\'article au rayon...</option>' . join($rayons_options) . '</select><br>';
    }

    $orderLink = null;
    $removeLink = null;
    $order = $om->getById($stockEntity->get('order_id'));
    if ($order) {
        $orderLink = '
        <a class="btn btn-primary" href="/pages/adm_order?order_id=' . $order->get('id') . '">
            Modifier
        </a>
    ';
        $removeLink = '
        <a class="btn btn-primary"
            href="/pages/adm_order?order_id=' . $order->get('id') . '&stock_remove=' . $stockEntity->get('id') . '">
                Retirer de la commande et remettre en vente
        </a>
    ';
    }

    $cancelReturnLink = null;
    if ($stockEntity->isReturned()) {
        $cancelReturnLink = '
        <a class="btn btn-primary"
            href="' . $urlgenerator->generate(
                'stock_cancel_return',
                ['stockId' => $stockEntity->get('id')]
            ) . '">
            Annuler le retour et remettre en vente
        </a>
    ';
    }

    $nextYear = (new DateTime("next year"))->format("Y");
    $content .= '

    <div class="buttons">
        ' . $removeLink . ' ' . $cancelReturnLink . '
    </div>

    <form enctype="multipart/form-data" method="post" action="/pages/adm_stock" class="fieldset">
        <fieldset>

            <label title="Chaque exemplaire en base a un numéro unique" for="stock_id" class="readonly">Exemplaire n&deg; </label>
            <input type="text" name="stock_id" id="stock_id" value="' . $_GET['id'] . '" class="mini" readonly />
            <br />

            <label for="article_id" class="required">Article n° </label>
            <input type="number" name="article_id" id="article_id" value="' . $a['article_id'] . '" class="mini required" required />
            ' . $rayon_select . '
            <br />
            <br />

            ' . $supplier . '
            <label for="stock_invoice">Lot / Facture n&deg; :</label>
            ' . $invoice . ' <input type="checkbox" name="stock_depot" id="stock_depot" value=1' . (isset($stockEntity['stock_depot']) && $stockEntity['stock_depot'] ? ' checked' : null) . '> <label for="stock_depot" class="after">Dép&ocirc;t</label>
            <br />
            <label for="stock_stockage">Emplacement :</label>
            <input 
                type="text" 
                name="stock_stockage" 
                id="stock_stockage" 
                value="' . $stockEntity["stock_stockage"] . '" 
                class="short"
                maxlength="16" 
            />
            <br /><br />

            <label for="stock_condition" class="required">État :</label>
            <select name="stock_condition" id="stock_condition" class="required" autofocus required>
                <option selected>' . $stockEntity['stock_condition'] . '</option>
                <option>Neuf</option>
                <option>Très bon</option>
                <option>Bon</option>
                <option>Correct</option>
                <option>Moyen</option>
                <option>Mauvais</option>
                <option>Très mauvais</option>
            </select> <input type="text" name="stock_condition_details" placeholder="Précisions sur l\'état..." value="' . $stockEntity['stock_condition_details'] . '" />
            <br />

            <label for="stock_selling_price" class="required">Prix de vente :</label>
            <input type="number" name="stock_selling_price" id="stock_selling_price" maxlength="5" value="' . $stockEntity['stock_selling_price'] . '" class="mini required" required /> centimes
            <br />
            <label for="stock_weight" class="required">Poids :</label>
            <input type="number" name="stock_weight" id="stock_weight" maxlength=5 min=' . $stock_weight_minimum . ' max=100000 value="' . $stockEntity['stock_weight'] . '" class="mini" required /> grammes
            ' . $article_weight . '
            <br /><br />

            ' . $photo_field . '

            <label for="stock_pub_year">Dép&ocirc;t légal :</label>
            <input type="number" id="stock_pub_year" name="stock_pub_year" maxlength="4" min="1900" max="' . $nextYear . '" class="mini" placeholder="AAAA" value="' . $stockEntity['stock_pub_year'] . '" />
            </span>
            <br /><br />

            <label for="upload_photo">Photo :</label>
            <input type="file" accept="image/jpeg" id="upload_photo" name="upload_photo" />
            <br /><br />

            ' . $tva . $remises . '

            ' . $tva_fields . '

            <br />
            <label for="stock_purchase_price" class="required">Prix d\'achat :</label>
            <input type="number" name="stock_purchase_price" id="stock_purchase_price" maxlength="5" value="' . $stockEntity['stock_purchase_price'] . '" class="mini" /> centimes
            <br />
            <label for="stock_selling_price_saved">Prix sauvegardé :</label>
            <input type="number" name="stock_selling_price_saved" id="stock_selling_price_saved" maxlength="5" value="' . $stockEntity['stock_selling_price_saved'] . '" class="mini" /> centimes
            <br /><br />

';

    $cfcm = new CFCampaignManager();
    $campaigns = $cfcm->getAll();
    if ($campaigns) {
        $campaigns = array_map(function ($campaign) use ($stockEntity) {
            return '<option value="' . $campaign->get('id') . '"' . ($stockEntity->get('campaign_id') == $campaign->get('id') ? ' selected' : null) . '>' . $campaign->get('title') . '</option>';
        }, $campaigns);

        $cfrm = new CFRewardManager();
        $rewards = $cfrm->getAll([], ['order' => 'reward_price']);
        $rewards = array_map(function ($reward) use ($stockEntity) {
            return '<option value="' . $reward->get('id') . '"' . ($stockEntity->get('reward_id') == $reward->get('id') ? ' selected' : null) . '>[' . price($reward->get('price'), 'EUR') . '] ' . $reward->get('content') . '</option>';
        }, $rewards);

        $content .= '
        <p>
            <label for="campaign_id">Campagne liée :</label>
            <select name="campaign_id" id="campaign_id" class="form-control">
                <option></option>
                ' . join($campaigns) . '
            </select>
        </p>
        <p>
            <label for="campaign_id">Contrepartie liée :</label>
            <select name="reward_id" id="reward_id" class="form-control">
                <option></option>
                ' . join($rewards) . '
            </select>
        </p>
        <br>
    ';
    } else {
        $content .= '
        <input type="hidden" name="campaign_id" value="' . $stockEntity->get('campaign_id') . '">
        <input type="hidden" name="reward_id" value="' . $stockEntity->get('reward_id') . '">
    ';
    }

    $content .= '

            <label for="stock_purchase_date" class="required">Date d\'achat :</label>
            <input type="text" name="stock_purchase_date" id="stock_purchase_date" value="' . $stockEntity['stock_purchase_date'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime required" required />
            <br />
            <label for="stock_onsale_date" class="required">Mis en vente le :</label>
            <input type="text" name="stock_onsale_date" id="stock_onsale_date" value="' . $stockEntity['stock_onsale_date'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime required" required />
            <br /><br />
';

    if ($mode == 'insert') {
        $content .= '
            <div class="center">
                <button type="submit" class="btn btn-primary">Ajouter au stock</button>
                <input type="number" name="stock_num" min="1" max="99" maxlength="2" value="1" class="nano" /> exemplaire(s)
            </div>
            <br />
        </fieldset>
    ';
    } else {
        $content .= '
            <div class="center">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <input type="hidden" name="stock_num" value="1" class="mini" />
            </div>
            <br />
        </fieldset>
    ';
    }

    $content .= '
    <fieldset>
        <legend>Statut de l\'exemplaire</legend>
        <label for="stock_cart_date">Mis en panier le :</label>
        <input readonly type="text" name="stock_cart_date" id="stock_cart_date" value="' . (isset($stockEntity['stock_cart_date']) ? $stockEntity['stock_cart_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
        <br />
        <label for="cart_id">Panier n&deg; :</label>
        <input readonly type="text" name="cart_id" id="cart_id" value="' . (isset($stockEntity['cart_id']) ? $stockEntity['cart_id'] : null) . '" class="mini" />
        <br />
        <br />
        <label for="stock_selling_date">Vendu le :</label>
        <input readonly type="text" name="stock_selling_date" id="stock_selling_date" value="' . (isset($stockEntity['stock_selling_date']) ? $stockEntity['stock_selling_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
        ' . $removeLink . '
        <br />
        <label for="order_id">Commande n&deg; :</label>
        <input readonly type="text" name="order_id" id="order_id" value="' . (isset($stockEntity['order_id']) ? $stockEntity['order_id'] : null) . '" class="mini" />
        ' . $orderLink . '
        <br />
        <br />
        <label readonly for="stock_return_date">Retourné le :</label>
        <input type="text" name="stock_return_date" id="stock_return_date" value="' . (isset($stockEntity['stock_return_date']) ? $stockEntity['stock_return_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
        ' . $cancelReturnLink . '
        <br />
        <br />
        <label for="stock_lost_date">Perdu le :</label>
        <input readonly type="text" name="stock_lost_date" id="stock_lost_date" value="' . (isset($stockEntity['stock_lost_date']) ? $stockEntity['stock_lost_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
        <br />
    </fieldset>
';

    if ($mode == 'update') {
        $content .= '
        <fieldset>
            <legend>Base de données</legend>
            <label for="stock_insert" class="readonly">Fiche créée le :</label>
            <input type="text" name="stock_insert" id="stock_insert" value="' . $stockEntity['stock_created'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" readonly />
            <br />
            <label for="stock_update" class="readonly">Fiche modifiée le :</label>
            <input type="text" name="stock_update" id="stock_update" value="' . $stockEntity['stock_updated'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" readonly />
            <br /><br />
        </fieldset>
    ';
    }

    $content .= '</form>';

    // Add to cart
    if ($mode == 'update' && $stockEntity->isAvailable()) {
        $carts = CartQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByType("web")
            ->orderByUpdatedAt()
            ->limit(100)
            ->find();
        $cartOptions = array_map(/**
         * @throws PropelException
         */ function (\Model\Cart $cart) {
            $identity = "Utilisateur inconnu";
            if ($cart->getUser() !== null) {
                $identity = $cart->getUser()->getEmail();
            } elseif ($cart->getAxysAccountId()) {
                $identity = "Utilisateur Axys n° {$cart->getAxysAccountId()}";
            }

            return "<option value=\"{$cart->getId()}\">Panier {$cart->getId()} de $identity</option>";
        }, $carts->getArrayCopy());

        $content .= '
    <form method="post" action="' . $urlgenerator->generate('stock_add_to_cart', ['stock_id' => $stockEntity->get('id')]) . '" class="fieldset form-inline">
        <fieldset>
            <legend>Ajouter à un panier</legend>
                <p class="text-center">
                    Ajouter l\'exemplaire au panier :
                    <select class="form-control" name="cart_id">
                        ' . join($cartOptions) . '
                    </select>
                    <button class="btn btn-primary" type="submit">OK</button>
                </p>
        </fieldset>
    </form>
    ';
    }

    $content .= '
    <div class="admin">
        ' . $div_admin . '
    </div>
';

    return new Response($content);
};

/**
 * @throws PropelException
 */
function _shouldAlertsBeSent(string $mode, string $copyCondition, CurrentSite $currentSite): bool
{
    return $currentSite->hasOptionEnabled("alerts") && $mode === "insert" && $copyCondition !== "Neuf";
}

/**
 * @throws TransportExceptionInterface
 * @throws PropelException
 * @throws Exception
 */
function _sendAlertsForArticle(
    Article     $article,
    string      $copyYear,
    string      $copyPrice,
    string      $copyCondition,
    Mailer      $mailer,
    CurrentSite $currentSite,
): array
{
    $alerts = AlertQuery::create()
        ->filterBySite($currentSite->getSite())
        ->_or()
        ->filterByUserId(null, Criteria::ISNULL)
        ->findByArticleId($article->get("id"))
        ->getArrayCopy();
    $sentAlerts = 0;
    $errors = [];
    foreach ($alerts as $alert) {
        $recipientEmail = $alert->getUser()?->getEmail() ?? $alert->getRecipientEmail();
        if (!$recipientEmail) {
            continue;
        }

        $subject = "{$article->get("title")} est disponible !";

        $customMessage = $currentSite->getOption("alerts_custom_message");
        if ($customMessage) {
            $customMessage = '<p><strong>' . $customMessage . '</strong></p>';
        }

        $articleUrl = "https://{$currentSite->getSite()->getDomain()}/a/{$article->get("url")}";

        $message = '
            <p>Bonjour,</p>
            <p>Vous avez créé une alerte pour le livre&nbsp;:</p>
            <p>
                <a href="' . $articleUrl . '">' . $article->get("title") . '</a><br />
                de ' . authors($article->get("authors")) . '<br />
                coll. ' . $article->get("collection")->get("name") . numero($article->get("number")) . ' (' . $article->get("publisher")->get("name") . ')
            </p>
            <p>
                Un exemplaire de ce livre vient d\'être mis en vente chez 
                <a href="https://' . $currentSite->getSite()->getDomain() . '/a/' . $article->get("url") . '">' . $currentSite->getTitle() . '</a>&nbsp;!
            </p>
            <p>
                Édition de ' . $copyYear . '<br />
                État : ' . $copyCondition . '<br />
                Prix : ' . currency($copyPrice / 100) . '
            </p>
            
            ' . $customMessage . '
            
            <p>
                Pour en savoir plus ou acheter ce livre, rendez-vous sur :<br />
                <a href="' . $articleUrl . '">' . $articleUrl . '</a>
            </p>
            <p>
                Attention !<br />
                Une alerte pouvant être créée par plusieurs personnes sur le même livre, le premier arrivé est le 
                premier servi. Il se peut donc que le livre ne soit déjà plus disponible lors de votre visite. Ne 
                perdez pas de temps !
            </p>
            <p><a href="https://' . $currentSite->getSite()->getDomain() . '/pages/log_myalerts">Modifier ou annuler mes alertes</a></p>
            <p>À très bientôt dans les librairies Biblys !</p>
        ';

        try {
            $mailer->send($recipientEmail, $subject, $message);
            $sentAlerts++;
        } catch (InvalidEmailAddressException $exception) {
            $errors[] = [
                "email" => $recipientEmail,
                "reason" => $exception->getMessage(),
            ];
        }
    }

    return [
        "sent" => $sentAlerts,
        "errors" => $errors
    ];
}

