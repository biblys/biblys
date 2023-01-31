<?php /** @noinspection CommaExpressionJS */

/** @var Site $site */

use Biblys\Service\Config;
use Biblys\Service\Mailer;
use Framework\Exception\AuthException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

$_JS_CALLS[] = '//cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.pack.js';
$_CSS_CALLS[] = 'screen://cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.css';

$om = new OrderManager();
$am = new ArticleManager();

/** @var Request $request */
$order_url = $request->query->get("url");
$order = $om->get(["order_url" => $order_url]);

if (!$order) {
    throw new NotFoundException("Order $order_url not found.");
}

$o = $order;
$content = '';

function _isAnonymousOrder(Order $order): bool
{
    return !$order->has("user_id");
}

function _orderBelongsToVisitor(Order $order, Visitor $visitor): bool
{
    if (!$visitor->isLogged()) {
        return false;
    }

    return $order->get("user_id") === $visitor->get("user_id");
}

/** @var Visitor $_V */
if (_isAnonymousOrder($order) || _orderBelongsToVisitor($order, $_V) || $_V->isAdmin()) {

    $buttons = NULL;

    $request->attributes->set("page_title", "Commande n° {$o["order_id"]}");

    $content .= '<h2>Commande n° ' . $o["order_id"] . '</h2>';

    if (auth('admin')) {
        $content .= '
            <div class="admin">
                <p>Commande n° ' . $o["order_id"] . '</p>
                <p><a href="/pages/adm_order?order_id=' . $o["order_id"] . '">modifier</a></p>
                <p><a href="/invoice/' . $o["order_url"] . '">facture</a></p>
            </div>
        ';
    }

    // Etat de la commande
    $content .= '
        <div class="floatR">
            <h4 class="text-right">état de la commande</h4>
            <p class="right">Validée le ' . _date($o["order_insert"], 'j f Y') . '</p>
    ';

    if ($o["order_followup_date"]) {
        $content .= '<p class="right">Relancée le ' . _date($o["order_followup_date"], 'j f Y') . '</p>';
    }
    if ($o["order_payment_date"]) {
        $content .= '<p class="right">Payée le ' . _date($o["order_payment_date"], 'j f Y') . '</p>';
    }
    if ($order->has('shipping_date')) {
        if ($order->get('shipping_mode') == 'magasin') {
            $content .= '<p class="right">Mise à dispo. en magasin le ' . _date($o["order_shipping_date"], 'j f Y') . '</p>';
        } else {
            $content .= '<p class="right">Expédiée le ' . _date($o["order_shipping_date"], 'j f Y') . '</p>';
        }
    }
    if ($o["order_cancel_date"]) {
        $content .= '<p class="right">Annulée le ' . _date($o["order_cancel_date"], 'j f Y') . '</p>';
    }

    $content .= '
        </div>
    ';

    $country = $order->get('country');
    if ($country instanceof Country) {
        $country = $country->get('name');
    }

    if (!empty($o["order_address2"])) $o["order_address2"] = $o["order_address2"] . '<br />';
    $content .= '
        <h4>Coordonnées</h4>
        <p>
            ' . $o["order_title"] . ' ' . $o["order_firstname"] . ' ' . $o["order_lastname"] . '<br />
            ' . $o["order_address1"] . '<br />
            ' . $o["order_address2"] . '
            ' . $o["order_postalcode"] . ' ' . $o["order_city"] . '<br />
            ' . $country . '
        </p>
        <p><a href="mailto:' . $o["order_email"] . '">' . $o["order_email"] . '</a></p>
    ';

    // Ref client
    if (!empty($o["user_id"]) and auth("admin")) {
        /** @var PDO $_SQL */
        $stock = $_SQL->prepare("SELECT COUNT(`order_id`) AS `num`, SUM(`order_amount`) AS `CA` FROM `orders` WHERE `user_id` = :user_id AND `site_id` = :site_id AND `order_payment_date` IS NOT NULL AND `order_cancel_date` IS NULL GROUP BY `user_id`");
        $stock->execute([
            'user_id' => $o['user_id'],
            'site_id' => $site->get('id')
        ]);
        $s = $stock->fetch(PDO::FETCH_ASSOC);
        if ($s) {
            $content .= '<p>Ref. Client : ' . $o["user_id"] . '-' . round($s["num"]) . '-' . round($s["CA"] / 100) . '</p>';
        }
    }

    if ($order->has('comment') && $_V->isAdmin()) {
        $content .= '
        <h4>Commentaire du client</h4>
        <p>' . nl2br($order->get('comment')) . '</p>
        ';
    }

    $content .= '<br /><div class="center">';

    // Creation de la commande
    if (isset($_GET["created"])) {
        $content .= '<br /><p class="success">Votre commande a bien été enregistrée sous le numéro ' . $o["order_id"] . '.</p><p class="center">Vous pouvez la régler en utilisant le bouton ci-dessous.</p><br />';
    }

    // MAJ de la commande
    if (isset($_GET["updated"])) $content .= '<p class="success">La commande a été mise à jour.</p><br />';

    // Paiement de la commande
    if (isset($_GET["payed"])) $content .= '<p class="success">La commande a été payée.</p><br />';

    // Confirmation
    if (isset($_GET["confirm"])) {
        $order->set('order_confirmation_date', date('Y-m-d H:i:s'));
        $om->update($order);
        return new RedirectResponse("/order/{$o["order_url"]}?confirmed=1");
    } elseif (isset($_GET["confirmed"])) {
        $content .= '<p class="success">Merci d\'avoir confirmé la réception de votre commande.</p><br />';
    }

    // Signaler un incident
    if (isset($_GET["flagged"])) $content .= '<p class="success">L\'incident a bien été enregistré.</p><br />';

    // Paiement
    if (!$o["order_payment_date"]) $buttons .= '<a href="/payment/' . $o["order_url"] . '" class="btn btn-primary"><i class="fa fa-money"></i>&nbsp; Payer la commande (' . currency($o["order_amount_tobepaid"] / 100) . ')</a> ';
    else $buttons .= '<a href="/invoice/' . $o["order_url"] . '" class="btn btn-default"><i class="fa fa-print"></i> Imprimer une facture</a> ';

    // Suivi et confirmation
    if ($o["order_shipping_date"]) {
        if ($o["order_track_number"]) {
            $content .= '
                <p class="center">
                    Numéro de suivi : 
                    <a href="https://www.laposte.fr/outils/suivre-vos-envois?code='.$o["order_track_number"].'">
                        '.$o["order_track_number"].'
                    </a>
                </p><br />
            ';
        }
        if (!$o["order_confirmation_date"]) {
            $buttons .= '
                <a href="/order/' . $o["order_url"] . '?confirm=1" class="btn btn-success"><i class="fa fa-check-circle"></i> Confirmer la réception</a>
                <button id="dialog_incident" class="dialogThis btn btn-warning"><i class="fa fa-warning"></i>&nbsp; Signaler un incident</button>
            ';

            // Incident
            if (!empty($_POST["incident"])) {
                // Envoi du mail
                /** @var Site $_SITE */
                $content = '
                    <html lang="fr">
                        <head>
                            <title>' . $_SITE["site_tag"] . ' | Commande n° ' . $o["order_id"] . ' : incident</title>
                        </head>
                        <body>
                            <p>Le client souhaite retourner la commande n°&nbsp;<a href="https://' . $_SERVER["HTTP_HOST"] . '/order/' . $o["order_url"] . '">' . $o["order_id"] . '</a></p>
                            <p>---</p>
                            <p>Commentaire du client :</p>
                            <p>' . nl2br($_POST["incident"]) . '</p>
                        </body>
                    </html>
                ';

                $to = $site->get("contact");
                $subject = $site->get("tag") . " | Commande n° " . $o["order_id"] . " : incident";
                $body = stripslashes($content);
                $from = [$site->get("contact") => $o["order_firstname"] . " " . $o["order_lastname"]];
                $options = ["reply-to" => $o["order_email"]];
                $mailer = new Mailer();
                $mailer->send($to, $subject, $body, $from, $options);

                $order->set('order_confirmation_date', date('Y-m-d H:i:s'));
                $om->update($order);
                return new RedirectResponse("/order/{$o["order_url"]}?flagged=1");
            }

            $content .= '
                <form id="incident" method="post" class="hidden" data-title="Signaler un incident">
                    <fieldset>
                        <p>
                            Nous voulons que vous soyez totalement satisfait·e de votre commande. En cas de
                            problème, vous pouvez la renvoyer intégralement ou en partie à l\'adresse ci-dessous sous 7
                            jours. Le montant des livres retournés vous sera remboursés intégralement.
                        </p>
                        <p class="center"><strong>' . $site->get("title") . '<br />' . str_replace("|", "<br />", $site->get("address")) . '</strong></p>
                        <p>Merci d\'indiquer les raisons pour lesquelles vous souhaitez renvoyer votre commande :</p>
                        <textarea name="incident"></textarea>
                        <br />
                        <p class="center"><input type="submit" value="Enregistrer l\'incident" /></p>
                    </fieldset>
                </form>
            ';
        }
    }

    $content .= $buttons;

    $content .= '<br /></div>';

    $copies = $order->getCopies();

    $i = 0;
    $numerique = 0;
    $total_tva = 0;
    $total_ht = 0;
    $o['order_weight'] = 0;
    $ArticlesCount = 0;
    $books = NULL;
    foreach ($copies as $copy) {
        $article = $copy->getArticle();
        $a = $article;
        $i++;

        if (!$article) {
            continue;
        }

        // Image
        $cover_stock = new Media('stock', $copy->get('id'));
        $cover_article = new Media('stock', $copy->get('id'));
        $cover = NULL;
        if ($cover_article->exists()) {
            $cover = '<a href="' . $cover_article->url() . '" rel="fancybox"><img src="' . $cover_article->url('h100') . '" height=60 alt="' . $a["article_title"] . '"></a>';
        } elseif ($cover_stock->exists()) {
            $cover = '<a href="' . $cover_stock->url() . '" rel="fancybox"><img src="' . $cover_stock->url('h100') . '" height=60 alt="' . $a["article_title"] . '"></a>';
        }

        // Precommande
        if ($a["article_pubdate"] > $o["order_insert"]) $a["article_title"] .= ' (précommande)';

        // Livres numeriques
        $a['dl_links'] = NULL;
        if ($a["type_id"] == 2) {
            $a["article_title"] .= ' (numérique)';
            $numerique++;
        }

        // Downloadable files
        if ($order->isPayed() && $article->getType()->isDownloadable()) {
            if ($article->isPublished()) {
                $fm = new FileManager();
                $files = $fm->getAll(['article_id' => $a['article_id'], 'file_access' => 1]);
                if ($files) {
                    foreach ($files as $f) {
                        /** @var User $_LOG */
                        $a['dl_links'] .= ' <a href="' . $f->getUrl($_LOG['user_key']) . '" title="' . $f->get('version') . ' | ' . file_size($f->get('size')) . ' | ' . $f->getType('name') . '"><img src="' . $f->getType('icon') . '" width=16 alt="Télécharger"> ' . $f->get('title') . '</a> &nbsp;';
                    }
                    $a['dl_links'] = '<div class="btn btn-default"><i class="fa fa-cloud-download"></i> &nbsp; ' . $a['dl_links'] . '</a>';
                }
            } else {
                $a['dl_links'] = '<p class="alert alert-info">Téléchargement possible à partir du ' . _date($article->get('pubdate'), 'd/m') . '.</p>';
            }
        }

        // TVA
        $total_ht += $a['stock_selling_price_ht'];
        $total_tva += $a['stock_selling_price_tva'];

        $copyId = $copy->get('id');
        if (auth("admin")) {
            $copyId = '<a href="/pages/adm_stock?id=' . $copyId . '">' . $copyId . '</a>';
        }

        $books .= '
            <tr>
                <td class="center">' . $copyId . '</td>
                <td class="center">' . $cover . '</td>
                <td>
                    <strong><a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a><br /></strong>
                    <em>de ' . $a["article_authors"] . '</em><br />
                    coll. ' . $a["article_collection"] . ' ' . numero($a["article_number"]) . '<br />
                    ' . $a["dl_links"] . '
        ';
        if (!empty($a["stock_condition"])) $books .= 'état : ' . $a["stock_condition"] . '<br />';
        if (!empty($a["stock_weight"]) && !empty($_GET["weight"])) $books .= $a["stock_weight"] . 'g<br />';
        $books .= '
                </td>
                <td class="right">
                    ' . currency($copy->get('selling_price') / 100) . '<br />
                </td>
            </tr>
        ';
        $o["order_weight"] += $a["stock_weight"];

        $ArticlesCount++;
    }

    $content .= '
        <br />
        <table class="table cart list order">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th colspan="2">Articles</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                ' . $books . '
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="right">Articles :</th>
                <th class="right">' . $ArticlesCount . '</th>
            </tr>
    ';

    if (isset($o["order_weight"]) && $site->get("shipping_fee")) {
        $content .= '
                    <tr>
                        <th colspan="3" class="right">Poids :</th>
                        <th class="right">' . round($o["order_weight"] / 1000, 2) . '&nbsp;kg</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="right">Frais de port (' . $o["order_shipping_mode"] . ') :</th>
                        <th class="right">' . currency($o["order_shipping"] / 100) . '</th>
                    </tr>
        ';
    } elseif ($o["order_shipping"]) {
        $content .= '
                <tr>
                    <th colspan="3" class="right">Frais de port :</th>
                    <th class="right">' . currency($o["order_shipping"] / 100) . '</th>
                </tr>
        ';
    }

    if ($total_tva) {
        $content .= '
                    <tr>
                        <th colspan="3" class="right">Total H.T. :</th>
                        <th class="right">' . currency($total_ht / 100) . '</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="right">T.V.A. :</th>
                        <th class="right">' . currency($total_tva / 100) . '</th>
                    </tr>
        ';
    }

    $content .= '
                <tr>
                    <th colspan="3" class="right">Total T.T.C.&nbsp;:</th>
                    <th class="right">' . currency(($o["order_amount"] + $o["order_shipping"]) / 100) . '</th>
                </tr>
            </tfoot>
        </table>
    ';

    /** @var Config $config */
    $matomo = $config->get("matomo");
    if ($matomo && $order->isPayed()) {

        $groups = StockManager::groupByArticles($order->getCopies());
        foreach ($groups as $group) {
            $article = $group["article"];
            $content .= "
                <script>
                    _paq.push(['addEcommerceItem',
                        // (required) SKU: Product unique identifier
                        " . ($article->has("ean") ?
                $article->get("ean") :
                $article->get("id")) . ",
                        // (optional) Product name
                        '" . addSlashes($article->get("title")) . "',
                        // (optional) Product category.
                        " . $article->getRayonsAsJsArray() . ",
                         // (recommended) Product price
                        " . price($group["unit_price"]) . ",
                         // (optional, default to 1) Product quantity
                        " . $group["quantity"] . "
                    ])
                </script>
            ";
        }

        $content .= "
            <script>
                _paq.push(['trackEcommerceOrder',
                    " . $order->get("id") . ", // (required) Unique Order ID
                    " . price($order->getTotal()) . ", // (required) Order Revenue grand total (includes tax, shipping, and subtracted discount)
                    " . price($order->get("amount")) . ", // (optional) Order sub total (excludes shipping)
                    0, // (optional) Tax amount
                    " . price($order->get("shipping")) . ", // (optional) Shipping amount
                    // false // (optional) Discount offered (set to false for unspecified parameter)
                ])

            </script>
        ";
    }

    if ($_V->isAdmin()) {
        /** @var UrlGenerator $urlgenerator */
        $content .= '
            <h3 class="text-center">Origine de la commande</h3>
            <p class="text-center">
                Source : <a href="' . $urlgenerator->generate('orders_conversions') . '?source=' . $order->get('utm_source') . '">' . $order->get('utm_source') . '</a><br>
                Campagne : <a href="' . $urlgenerator->generate('orders_conversions') . '?campaign=' . $order->get('utm_campaign') . '">' . $order->get('utm_campaign') . '</a><br>
                Medium : <a href="' . $urlgenerator->generate('orders_conversions') . '?medium=' . $order->get('utm_medium') . '">' . $order->get('utm_medium') . '</a><br>
            </p>
        ';
    }
} elseif (!auth()) {
    throw new AuthException("Vous n'avez pas le droit d'accéder à cette page.");
} else {
    throw new AuthException("Vous n'avez pas le droit d'accéder à cette page.");
}

return new Response($content);
