<?php

/** @var CurrentSite $currentSite */

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

$sm = new StockManager();
$am = new ArticleManager();
$alm = new AlertManager();
$om = new OrderManager();
$um = new UserManager();
$rm = new RayonManager();

$_JS_CALLS[] = '//cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.pack.js';
$_CSS_CALLS[] = 'screen://cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.css';

$content = null;

/** @var Session $session */
foreach ($session->getFlashBag()->get('success', []) as $message) {
    $content .= "<p class='alert alert-success'>$message</p>";
}
foreach ($session->getFlashBag()->get('info', []) as $message) {
    $content .= "<p class='alert alert-info'>$message</p>";
}

$div_admin = null;

// Exemplaire retourne
/** @var Request $request */
$returnedStockId = $request->query->get('return');
if ($returnedStockId) {
    $stock = $sm->getById($returnedStockId);
    $stock->setReturned();
    $sm->update($stock);
    return new RedirectResponse('/pages/adm_stock?id=' . $_GET['return'] . '&returned=1');
}

// Exemplaire perdu
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

// Copies sold in shop
if (isset($_GET['sold'])) {
    // All copies sold in shop are associated to a fake customer that needs
    // to be created and specified via the `fake_shop_customer` site option.
    // This allows to create only one order for shop sales per day.
    /** @var Site $site */
    $fakeCustomerId = $site->getOpt('fake_shop_customer');
    if (!$fakeCustomerId) {
        throw new Exception("L'option de site `fake_shop_customer` doit être définie.");
    }

    if ($stock = $sm->get(['stock_id' => $_GET['sold']])) {
        if (!$stock->isAvailable()) {
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
                $om->addStock($order, $stock);

                // Update the order amount from stock
                $om->updateFromStock($order);
            } catch (Exception $e) {
                trigger_error($e->getMessage());
            }

            return new RedirectResponse('/pages/adm_stock?id=' . $stock->get('id') . '&solded=1');
        }
    } else {
        trigger_error('Exemplaire introuvable');
    }
}

if ($request->getMethod() === 'POST') {
    // AddSlashes
    foreach ($_POST as $key => $value) {
        $_POST[$key] = addslashes($value);
    }

    // Get related article
    $article_id = $request->request->get('article_id');
    $article = $am->getById($article_id);
    if (!$article) {
        throw new Exception("Cannot find article with id $article_id");
    }

    // Add to rayon
    $rayon_id = $request->request->get('add_to_rayon');
    if ($rayon_id) {
        $rayon = $rm->getById($rayon_id);
        if (!$rayon) {
            throw new Exception("Cannot find rayon with id $rayon_id");
        }
        $rayon->addArticle($article);
        $session->getFlashBag()->add('info', 'L\'article <strong>' . $article->get('title') . '</strong> a été ajouté au rayon <strong>' . $rayon->get('name') . '</strong>.');
    }

    for ($i = 0; $i < $_POST['stock_num']; ++$i) {
        // Creation de l'exemplaire
        if (empty($_POST['stock_id']) or $_POST['stock_num'] > 1) {
            $stock = $sm->create();
            $_POST['stock_id'] = $stock->get('id');
            $content .= '<p class="success">L\'exemplaire n&deg; <a href="/pages/adm_stock?id=' . $_POST['stock_id'] . '">' . $_POST['stock_id'] . '</a> a bien &eacute;t&eacute; ajout&eacute; au stock !</p>';
            $mode = 'insert';
            $update_date = 'NULL';
        } else {
            $mode = 'update';
            $update_date = 'NOW()';
        }

        $stock = $sm->getById($_POST['stock_id']);

        // Photo
        $photo = new Media('stock', $_POST['stock_id']);

        // Suppression de la photo
        if (isset($_POST['delete_photo']) && $_POST['delete_photo'] == 1) {
            if ($photo->exists()) {
                $photo->delete();
                $content = '<p class="success">La photo de l\'exemplaire a &eacute;t&eacute; supprim&eacute;e !</p>';
            }
        }

        // Update copy
        $stock->set('article_id', $request->request->get('article_id'))
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
        $campaign_id = (int) $request->request->get('campaign_id');
        $stock->set('campaign_id', $campaign_id);

        // Update reward_id
        $reward_id = (int) $request->request->get('reward_id');
        $stock->set('reward_id', $reward_id);

        // Persist copy
        $sm->update($stock);

        // Calculate tax
        $stock = $sm->calculateTax($stock);
        $sm->update($stock);

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
        if (!empty($_FILES['upload_photo']['tmp_name']) && !isset($stock_photo)) {
            if ($photo->exists()) {
                $photo->delete();
            }
            $photo->upload($_FILES['upload_photo']['tmp_name']);
            $stock_photo = $photo->path();
        }

        // Recuperation de la photo en cas de duplication
        if (isset($stock_photo) && $i > 0) {
            if ($photo->exists()) {
                $photo->delete();
            }
            $photo->upload($stock_photo);
        }
    }

    // Flash messages
    if ($mode == 'update') {
        $session->getFlashBag()->add('success', "L'exemplaire n° " . $stock->get('id') . ' a été mis à jour.');
    } elseif ($_POST['stock_num'] == 1) {
        $session->getFlashBag()->add('success', 'Un exemplaire de <strong>' . $article->get('title') . '</strong> a été ajouté au stock.');
    } else {
        $session->getFlashBag()->add('success', $_POST['stock_num'] . ' exemplaires de <strong>' . $article->get('title') . '</strong> ont été ajoutés au stock.');
    }

    // Update CFReward if necessary
    $rewards = $article->getCFRewards();
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
    if (!empty($_POST['stock_weight']) && $article->get('weight') != $_POST['stock_weight']) {
        $article->set('article_weight', $_POST['stock_weight']);
        $am->update($article);
        $session->getFlashBag()->add('info', "Le poids de l'article <strong>" . $article->get('title') . '</strong> a été mis à <strong>' . $article->get('weight') . 'g</strong>.');
    }

    $copyCondition = $request->request->get("stock_condition");
    if (_shouldAlertsBeSent($mode, $copyCondition, $currentSite)) {
        /** @var PDO $_SQL */
        /** @var Mailer $mailer */
        $copyYear = $request->request->get("stock_pub_year");
        $copyPrice = $request->request->get("stock_selling_price");
        $sentAlerts = _sendAlertsForArticle($article, $copyYear, $copyPrice, $copyCondition, $mailer, $site);
        if ($sentAlerts) {
            $session->getFlashBag()->add('info', '<strong>'.$sentAlerts.' alerte'.s($sentAlerts).'</strong> '.s($sentAlerts, 'a', 'ont').' été envoyée'.s($sentAlerts).'.');
        }
    }

    return new RedirectResponse('/pages/adm_stock?id=' . $_POST['stock_id']);
}

$photo_field = null;

$copyId = $request->query->get('copy');
$delId = $request->query->get('del');

// Modifier un exemplaire existant
if (!empty($_GET['id'])) {
    $_PAGE_TITLE = 'Modifier l\'exemplaire n&deg; ' . $_GET['id'];
    $content .= '<h1><span class="fa fa-cubes"></span> Modifier l\'exemplaire n&deg; ' . $_GET['id'] . '</h1>';

    if (isset($_GET['created'])) {
        $content .= '<p class="success">' . $_GET['created'] . ' exemplaire' . s($_GET['created']) . ' ajout&eacute;' . s($_GET['created']) . ' au stock !</p>';
    } elseif (isset($_GET['returned'])) {
        $content .= '<p class="success">L\'exemplaire a &eacute;t&eacute; retourn&eacute;.</p>';
    } elseif (isset($_GET['losted'])) {
        $content .= '<p class="success">L\'exemplaire a &eacute;t&eacute; marqu&eacute; comme perdu.</p>';
    } elseif (isset($_GET['solded'])) {
        $content .= '<p class="success">L\'exemplaire a &eacute;t&eacute; marqu&eacute; comme vendu en magasin.</p>';
    }

    if (isset($_GET['alerts'])) {
        $content .= '<p class="success">' . $_GET['alerts'] . ' alerte' . s($_GET['alerts']) . ' ' . s($_GET['alerts'], 'a', 'ont') . ' été envoyée' . s($_GET['alerts']) . '</p>';
    }

    $stockId = $request->query->get('id');
    $stock = $sm->getById($stockId);

    if (!$stock) {
        throw new Exception('Cet exemplaire n\'existe pas');
    }
    $s = $stock;
    $mode = 'update';

    if ($s['stock_pub_year'] == 0) {
        $s['stock_pub_year'] = null;
    }

    // Photo
    if ($stock->hasPhoto()) {
        $photo_field = '
            <div class="floatR center">
                ' . $stock->getPhotoTag(['size' => 'w90']) . '<br/>
                <input type="checkbox" name="delete_photo" value="1" /> Supprimer
            </div>';
    } else {
        $photo_field = null;
    }

    $div_admin = '
        <p>Exemplaire n&deg; ' . $s['stock_id'] . '</p>
        <p><a href="/pages/adm_stocks?article_id=' . $s['article_id'] . '">autres exemplaires</a></p>
        <p><a href="/pages/adm_stock?add=' . $s['article_id'] . '#add">nouvel exemplaire</a></p>
        <p><a href="/pages/adm_stock?del=' . $s['stock_id'] . '" data-confirm="Voulez-vous vraiment SUPPRIMER cet exemplaire ?">supprimer</a></p>
    ';
} elseif (!empty($copyId)) {
    $_PAGE_TITLE = 'Dupliquer l\'exemplaire n&deg; ' . $copyId;
    $content .= '<h1><span class="fa fa-copy"></span> Dupliquer l\'exemplaire n<sup>o</sup> ' . $_GET['copy'] . '</h1>';
    $stock = $sm->getById($copyId);
    if (!$stock) {
        throw new Exception('Cet exemplaire n\'existe pas');
    }
    $mode = 'insert';
    $_GET['id'] = null;
} elseif (!empty($_GET['add'])) { // Ajouter un exemplaire
    $request->attributes->set("page_title", "Ajouter au stock un nouvel exemplaire de...");
    $content .= '<h1 id="add"><span class="fa fa-plus"></span> ' . $_PAGE_TITLE . '</h1>';
    $s['article_id'] = $_GET['add'];
    $mode = 'insert';

    // Default values
    $s['stock_pub_year'] = null;
    $s['stock_selling_price_saved'] = null;
    $s['stock_insert'] = null;
    $s['stock_update'] = null;

    $s['stock_invoice'] = $site->getOpt('default_stock_invoice') ? $site->getOpt('default_stock_invoice') : null;
    $s['stock_stockage'] = $site->getOpt('default_stock_stockage') ? $site->getOpt('default_stock_stockage') : null;
    $s['stock_shop'] = $site->getOpt('default_stock_shop') ? $site->getOpt('default_stock_shop') : null;
    $s['stock_selling_price'] = $site->getOpt('default_stock_selling_price') ? $site->getOpt('default_stock_selling_price') : null;
    $s['stock_purchase_price'] = $site->getOpt('default_stock_purchase_price') ? $site->getOpt('default_stock_purchase_price') : null;
    $s['stock_condition'] = $site->getOpt('default_stock_condition') ? $site->getOpt('default_stock_condition') : null;
    $s['stock_condition_details'] = $site->getOpt('default_stock_condition_details') ? $site->getOpt('default_stock_condition_details') : null;

    if (!$site->getOpt('default_stock_purchase_date')) {
        $s['stock_purchase_date'] = date('Y-m-d H:i:s');
        $s['stock_onsale_date'] = date('Y-m-d H:i:s');
    } else {
        $s['stock_purchase_date'] = $site->getOpt('default_stock_purchase_date');
        $s['stock_onsale_date'] = $site->getOpt('default_stock_purchase_date');
    }
    $_GET['id'] = 0;
} elseif ($delId) {
    $copyToDelete = $sm->getById($delId);
    $sm->delete($copyToDelete);
    $session->getFlashBag()->add('success', 'L\'exemplaire ' . $delId . ' a bien été supprimé.');
    return new RedirectResponse('/pages/adm_stock');
}

$stock = new Stock([]);
if (isset($s['stock_id'])) {
    $stock = $sm->getById($s['stock_id']);
}

$addParam = $request->query->get('add');
if ($addParam) {
    $stock->set('article_id', $addParam);
}

$article = $stock->getArticle();
if ($article) {
    $a = $article;
    /** @var \Symfony\Component\Routing\Generator\UrlGenerator $urlgenerator */
    $articleUrl = $urlgenerator->generate(
        'article_show',
        ['slug' => $article->get('url')]
    );

    $articleCover = null;
    if ($article->hasCover()) {
        $articleCover = $article->getCoverTag(
            [
                'size' => 'h100',
                'class' => 'article-thumb-cover',
                'link' => false,
            ]
        );
    }

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
                'height' => '100',
            ]
        );
    }

    $content .= '
        <a href="' . $articleUrl . '">
            <div class="article-thumb">
                ' . $articleCover . '
                <div class="article-thumb-data">
                    <h3>' . $a['article_title'] . '</h3>
                    <p>
                        de ' . truncate($a['article_authors'], 65, '...', true, true) . '<br />
                        coll. ' . $a['article_collection'] . ' ' . numero($a['article_number']) . ' (' . $a['article_publisher'] . ')<br />
                        Prix &eacute;diteur : ' . price($a['article_price'], 'EUR') . '
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
    $weight_required = $site->getOpt('weight_required');
    if ($weight_required) {
        $stock_weight_minimum = $weight_required;
    }

    // Autres exemplaires
    if ($mode == 'insert') {
        $ex = null;
        $exs = null;
        $copies = $article->getStock();
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
        $ex = null;
        $als = null;
        $alerts = $alm->getAll(['article_id' => $article->get('id')]);
        $alerts = array_map(function ($alert) use ($um) {
            $user = $um->getById($alert->get('user_id'));

            return '
                <tr>
                    <td>' . $user->get('email') . '</a></td>
                    <td>' . $alert->get('condition') . '</td>
                    <td>' . $alert->get('pub_year') . '</td>
                    <td>' . currency($alert->get('max_price'), true) . '</td>
                </tr>
            ';
        }, $alerts);
        $alerts_num = count($alerts);

        if ($alerts_num > 0) {
            $disabledAlertsWarning = null;
            /** @var CurrentSite $currentSite */
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
                                '. $disabledAlertsWarning . '
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
    $suppliers = $article->get('publisher')->getSuppliers();
    $supplier = null;
    $su = null;
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
    $s['stock_tva'] = 0;
    if (!$su['supplier_notva'] && !$site['default_notva']) {
        $s['stock_tva'] = $article->getTaxRate();
        $tva = '
            <label for="stock_tva" class="disabled">TVA :</label>
            <input type="text" name="stock_tva" id="stock_tva" value="' . $s['stock_tva'] . ' %" class="mini" disabled />
            <br />
        ';
    }
    $TVA = 1 + $s['stock_tva'] / 100;

    // Prix d'achat par defaut
    if (empty($s['stock_selling_price']) && $site->getOpt('default_stock_discount') && !empty($a['article_price']) and !empty($a['article_tva'])) {
        $s['stock_purchase_price'] = round($a['article_price'] / $TVA * (100 - $site->getOpt('default_stock_discount')) / 100);
        if ($site->getOpt('default_stock_super_discount')) {
            $s['stock_purchase_price'] = round($s['stock_purchase_price'] * (1 - $site->getOpt('default_stock_super_discount') / 100));
        }
        if ($site->getOpt('default_stock_cascading_discount')) {
            for ($ir = 0; $ir < $site->getOpt('default_stock_cascading_discount'); ++$ir) {
                $s['stock_purchase_price'] = $s['stock_purchase_price'] - ($s['stock_purchase_price'] * 0.01);
            }
            $s['stock_purchase_price'] = round($s['stock_purchase_price']);
        }
    }

    // Exemplaire vendu : afficher le prix HT et la TVA
    $tva_fields = null;
    if ($site->has('tva') && $stock->has('selling_date')) {
        $tva_fields = '
            <p>
                <label>Prix de vente HT :</label>
                <input type="text" readonly value="' . $stock->get('selling_price_ht') . '" class="mini"> centimes
            </p>
            <p>
                <label>Taux de TVA :</label>
                <input type="text" readonly value="' . $stock->get('tva_rate') . '" class="mini"> %
            </p>
            <p>
                <label>TVA sur PdV :</label>
                <input type="text" readonly value="' . $stock->get('selling_price_tva') . '" class="mini"> centimes
            </p>
            <p>
                <label>Remise recalculée :</label>
                <input type="text" readonly value="' . $stock->getDiscountRate() . '" class="mini"> %
            </p>
        ';
    } elseif ($site->has('tva') && $stock->has('id')) {
        $tva_fields = '
            <p>
                <label>Remise recalculée :</label>
                <input type="text" readonly value="' . $stock->getDiscountRate() . '" class="mini"> %
            </p>
        ';
    }

    // Prix de vente par d&eacute;faut d'après prix &eacute;diteur
    if (empty($s['stock_selling_price']) and !empty($a['article_price'])) {
        $s['stock_selling_price'] = $a['article_price'];
    }

    // Poids d'après fiche article
    if (empty($s['stock_weight']) && !$a['collection_incorrect_weights']) {
        $s['stock_weight'] = $a['article_weight'];
    }

    // Rabais sur le prix neuf
    if (!empty($site['Rabais']) and !empty($a['article_price'])) {
        $s['stock_selling_price'] = $a['article_price'] - ($a['article_price'] / 100 * $site['Rabais']);
    }

    $invoice = '<input type="text" name="stock_invoice" id="stock_invoice" value="' . $s['stock_invoice'] . '" />';
    if (empty($s['stock_invoice'])) {
        $invoicesQuery = EntityManager::prepareAndExecute(
            'SELECT `stock_invoice` FROM `stock` WHERE `site_id` = :site_id
                        GROUP BY `stock_invoice`',
            ['site_id' => $site->get('id')]
        );
        $invoices = $invoicesQuery->fetchAll(PDO::FETCH_ASSOC);
        $invoices_options = null;
        foreach ($invoices as $invoice) {
            $invoices_options .= '<option>' . $invoice['stock_invoice'] . '</option>';
        }
        $invoice = '<select name="stock_invoice" id="stock_invoice">' . $invoices_options . '</select>';
    }

    // STOCK
    $stock_default = null;
    if (!empty($s['stock_shop'])) {
        if ($s['stock_shop'] == 1) {
            $stock_default = '<option value="1" selected="selected">Ys</option>';
        } elseif ($s['stock_shop'] == 2) {
            $stock_default = '<option value="2" selected="selected">Scylla</option>';
        } elseif ($s['stock_shop'] == 7) {
            $stock_default = '<option value="7" selected="selected">Dystopia</option>';
        }
    }

    $remises = null;
    if (!empty($site->getOpt('default_stock_discount')) and $mode == 'insert') {
        $remises .= '
                <label for="Remise" class="disabled">Remise :</label>
                <input type="text" name="Remise" id="Remise" value="' . $site->getOpt('default_stock_discount') . ' %" class="court" disabled />
                <br />
        ';
    }
    if (!empty($site->getOpt('default_stock_super_discount')) and $mode == 'insert') {
        $remises .= '
                <label for="Remise2" class="disabled">Sur-remise :</label>
                <input type="text" name="Remise2" id="Remise2" value="' . $site->getOpt('default_stock_super_discount') . ' %" class="court" disabled />
                <br />
        ';
    }
    if (!empty($site->getOpt('default_stock_cascading_discount')) and $mode == 'insert') {
        $remises .= '
                <label for="Remise3" class="disabled">Remise en cascade :</label>
                <input type="text" name="Remise3" id="Remise3" value="' . $site->getOpt('default_stock_cascading_discount') . ' %" class="court" disabled />
                <br />
        ';
    }

    $stock_shop = '<input type="hidden" name="stock_shop" value="' . $site['site_id'] . '" />';

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
    $order = $om->getById($stock->get('order_id'));
    if ($order) {
        $orderLink = '
            <a class="btn btn-primary" href="/pages/adm_order?order_id=' . $order->get('id') . '">
                Modifier
            </a>
        ';
        $removeLink = '
            <a class="btn btn-primary"
                href="/pages/adm_order?order_id=' . $order->get('id') . '&stock_remove=' . $stock->get('id') . '">
                    Retirer de la commande et remettre en vente
            </a>
        ';
    }

    $cancelReturnLink = null;
    if ($stock->isReturned()) {
        $cancelReturnLink = '
            <a class="btn btn-primary"
                href="' . $urlgenerator->generate(
            'stock_cancel_return',
            ['stockId' => $stock->get('id')]
        ) . '">
                Annuler le retour et remettre en vente
            </a>
        ';
    }

    $content .= '

        <div class="buttons">
            ' . $removeLink . ' ' . $cancelReturnLink . '
        </div>

        <form enctype="multipart/form-data" method="post" action="/pages/adm_stock" class="fieldset">
            <fieldset>

                <label title="Chaque exemplaire en base a un num&eacute;ro unique" for="stock_id" class="readonly">Exemplaire n&deg; </label>
                <input type="text" name="stock_id" id="stock_id" value="' . $_GET['id'] . '" class="mini" readonly />
                <br />

                <label for="article_id" class="required">Article n° </label>
                <input type="number" name="article_id" id="article_id" value="' . $a['article_id'] . '" class="mini required" required />
                ' . $rayon_select . '
                <br />
                <br />

                ' . $supplier . '
                <label for="stock_invoice">Lot / Facture n&deg; :</label>
                ' . $invoice . ' <input type="checkbox" name="stock_depot" id="stock_depot" value=1' . (isset($s['stock_depot']) && $s['stock_depot'] ? ' checked' : null) . '> <label for="stock_depot" class="after">D&eacute;p&ocirc;t</label>
                <br />
                <label for="stock_stockage">Emplacement :</label>
                <input 
                    type="text" 
                    name="stock_stockage" 
                    id="stock_stockage" 
                    value="'.$s["stock_stockage"].'" 
                    class="short"
                    maxlength="16" 
                    required 
                />
                <br /><br />

                <label for="stock_condition" class="required">&Eacute;tat :</label>
                <select name="stock_condition" id="stock_condition" class="required" autofocus required>
                    <option selected>' . $s['stock_condition'] . '</option>
                    <option>Neuf</option>
                    <option>Très bon</option>
                    <option>Bon</option>
                    <option>Correct</option>
                    <option>Moyen</option>
                    <option>Mauvais</option>
                    <option>Très mauvais</option>
                </select> <input type="text" name="stock_condition_details" placeholder="Pr&eacute;cisions sur l\'&eacute;tat..." value="' . $s['stock_condition_details'] . '" />
                <br />

                <label for="stock_selling_price" class="required">Prix de vente :</label>
                <input type="number" name="stock_selling_price" id="stock_selling_price" maxlength="5" value="' . $s['stock_selling_price'] . '" class="mini required" required /> centimes
                <br />
                <label for="stock_weight" class="required">Poids :</label>
                <input type="number" name="stock_weight" id="stock_weight" maxlength=5 min=' . $stock_weight_minimum . ' max=100000 value="' . $s['stock_weight'] . '" class="mini" required /> grammes
                ' . $article_weight . '
                <br /><br />

                ' . $photo_field . '

                <label for="stock_pub_year">D&eacute;p&ocirc;t l&eacute;gal :</label>
                <input type="number" id="stock_pub_year" name="stock_pub_year" maxlength="4" min="1900" max="' . (date('Y') + 1) . '" class="mini" placeholder="AAAA" value="' . $s['stock_pub_year'] . '" />
                </span>
                <br /><br />

                <label for="upload_photo">Photo :</label>
                <input type="file" accept="image/jpeg" id="upload_photo" name="upload_photo" />
                <br /><br />

                ' . $tva . $remises . '

                ' . $tva_fields . '

                <br />
                <label for="stock_purchase_price" class="required">Prix d\'achat :</label>
                <input type="number" name="stock_purchase_price" id="stock_purchase_price" maxlength="5" value="' . $s['stock_purchase_price'] . '" class="mini required" required /> centimes
                <br />
                <label for="stock_selling_price_saved">Prix sauvegardé :</label>
                <input type="number" name="stock_selling_price_saved" id="stock_selling_price_saved" maxlength="5" value="' . $s['stock_selling_price_saved'] . '" class="mini" /> centimes
                <br /><br />

    ';

    $cfcm = new CFCampaignManager();
    $campaigns = $cfcm->getAll();
    if ($campaigns) {
        $campaigns = array_map(function ($campaign) use ($stock) {
            return '<option value="' . $campaign->get('id') . '"' . ($stock->get('campaign_id') == $campaign->get('id') ? ' selected' : null) . '>' . $campaign->get('title') . '</option>';
        }, $campaigns);

        $cfrm = new CFRewardManager();
        $rewards = $cfrm->getAll([], ['order' => 'reward_price']);
        $rewards = array_map(function ($reward) use ($stock) {
            return '<option value="' . $reward->get('id') . '"' . ($stock->get('reward_id') == $reward->get('id') ? ' selected' : null) . '>[' . price($reward->get('price'), 'EUR') . '] ' . $reward->get('content') . '</option>';
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
            <input type="hidden" name="campaign_id" value="' . $stock->get('campaign_id') . '">
            <input type="hidden" name="reward_id" value="' . $stock->get('reward_id') . '">
        ';
    }

    $content .= '

                <label for="stock_purchase_date" class="required">Date d\'achat :</label>
                <input type="text" name="stock_purchase_date" id="stock_purchase_date" value="' . $s['stock_purchase_date'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime required" required />
                <br />
                <label for="stock_onsale_date" class="required">Mis en vente le :</label>
                <input type="text" name="stock_onsale_date" id="stock_onsale_date" value="' . $s['stock_onsale_date'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime required" required />
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
            <input readonly type="text" name="stock_cart_date" id="stock_cart_date" value="' . (isset($s['stock_cart_date']) ? $s['stock_cart_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
            <br />
            <label for="cart_id">Panier n&deg; :</label>
            <input readonly type="text" name="cart_id" id="cart_id" value="' . (isset($s['cart_id']) ? $s['cart_id'] : null) . '" class="mini" />
            <br />
            <br />
            <label for="stock_selling_date">Vendu le :</label>
            <input readonly type="text" name="stock_selling_date" id="stock_selling_date" value="' . (isset($s['stock_selling_date']) ? $s['stock_selling_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
            ' . $removeLink . '
            <br />
            <label for="order_id">Commande n&deg; :</label>
            <input readonly type="text" name="order_id" id="order_id" value="' . (isset($s['order_id']) ? $s['order_id'] : null) . '" class="mini" />
            ' . $orderLink . '
            <br />
            <br />
            <label readonly for="stock_return_date">Retourn&eacute; le :</label>
            <input type="text" name="stock_return_date" id="stock_return_date" value="' . (isset($s['stock_return_date']) ? $s['stock_return_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
            ' . $cancelReturnLink . '
            <br />
            <br />
            <label for="stock_lost_date">Perdu le :</label>
            <input readonly type="text" name="stock_lost_date" id="stock_lost_date" value="' . (isset($s['stock_lost_date']) ? $s['stock_lost_date'] : null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" />
            <br />
        </fieldset>
    ';

    if ($mode == 'update') {
        $content .= '
            <fieldset>
                <legend>Base de donn&eacute;es</legend>
                <label for="stock_insert" class="readonly">Fiche cr&eacute;&eacute;e le :</label>
                <input type="text" name="stock_insert" id="stock_insert" value="' . $s['stock_created'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" readonly />
                <br />
                <label for="stock_update" class="readonly">Fiche modifi&eacute;e le :</label>
                <input type="text" name="stock_update" id="stock_update" value="' . $s['stock_updated'] . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" readonly />
                <br /><br />
            </fieldset>
        ';
    }

    $content .= '</form>';

    // Add to cart
    if ($mode == 'update' && $stock->isAvailable()) {
        $cm = new CartManager();
        $carts = $cm->getAll(['cart_type' => 'web'], ['order' => 'cart_updated', 'sort' => 'desc', 'limit' => 100]);
        $carts_options = array_map(function ($cart) {
            return '<option value="' . $cart->get('id') . '">' . $cart->get('id') . ' — ' . $cart->getUserInfo() . '</option>';
        }, $carts);

        $content .= '
        <form method="post" action="' . $urlgenerator->generate('stock_add_to_cart', ['stock_id' => $stock->get('id')]) . '" class="fieldset form-inline">
            <fieldset>
                <legend>Ajouter à un panier</legend>
                    <p class="text-center">
                        Ajouter l\'exemplaire au panier :
                        <select class="form-control" name="cart_id">
                            ' . join($carts_options) . '
                        </select>
                        <button class="btn btn-primary" type="submit">OK</button>
                    </p>
            </fieldset>
        </form>
        ';
    }
} elseif (isset($_GET['add']) or isset($_GET['id'])) {
    $content .= '<p class="error">Erreur : article inconnu</p>';
}

$content .= '
    <div class="admin">
        ' . $div_admin . '
    </div>
';

return new Response($content);

function _shouldAlertsBeSent(string $mode, string $copyCondition, CurrentSite $currentSite): bool
{
    return $currentSite->hasOptionEnabled("alerts") && $mode === "insert" && $copyCondition !== "Neuf";
}

function _sendAlertsForArticle(
    Article $article,
    string $copyYear,
    string $copyPrice,
    string $copyCondition,
    Mailer  $mailer,
    Site    $currentSite
): int
{
    $am = new AlertManager();
    $um = new UserManager();

    $alerts = $am->getAll(["article_id" => $article->get("id")]);

    $sentAlerts = 0;
    foreach ($alerts as $alert) {
        $subject = "Alerte : {$article->get("title")} est disponible !";

        $customMessage = null;
        if ($currentSite->getOpt("alerts_custom_message")) {
            $customMessage = '<p><strong>'.$currentSite->getOpt('alerts_custom_message').'</strong></p>';
        }

        $articleUrl = "https://{$currentSite->get("domain")}/a/{$article->get("url")}";

        $message = '
            <p>Bonjour,</p>
            <p>Vous avez créé une alerte Biblys pour le livre&nbsp;:</p>
            <p>
                <a href="'.$articleUrl.'">'.$article->get("title").'</a><br />
                de '.authors($article->get("authors")).'<br />
                coll. '.$article->get("collection")->get("name").numero($article->get("number")).' ('.$article->get("publisher")->get("name").')
            </p>
            <p>
                Un exemplaire de ce livre vient d\'être mis en vente chez 
                <a href="https://'.$currentSite->get("domain").'/a/'.$article->get("url").'">'.$currentSite['site_title'].'</a>&nbsp;!
            </p>
            <p>
                Édition de '.$copyYear.'<br />
                État : '.$copyCondition.'<br />
                Prix : '.currency($copyPrice / 100, ).'
            </p>
            
            '.$customMessage.'
            
            <p>
                Pour en savoir plus ou acheter ce livre, rendez-vous sur :<br />
                <a href="'.$articleUrl.'">'.$articleUrl.'</a>
            </p>
            <p>
                Attention !<br />
                Une alerte pouvant être créée par plusieurs personnes sur le même livre, le premier arrivé est le 
                premier servi. Il se peut donc que le livre ne soit déjà plus disponible lors de votre visite. Ne 
                perdez pas de temps !
            </p>
            <p><a href="https://www.biblys.fr/pages/log_myalerts">Modifier ou annuler mes alertes</a></p>
            <p>À très bientôt dans les librairies Biblys !</p>
        ';

        $user = $um->getById($alert->get("user_id"));
        $mailer->send($user->get("email"), $subject, $message);
        $sentAlerts++;
    }

    return $sentAlerts;
}

