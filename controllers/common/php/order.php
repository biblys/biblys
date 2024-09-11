<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection CommaExpressionJS */
/** @noinspection BadExpressionStatementJS */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Model\StockQuery;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

$om = new OrderManager();
$am = new ArticleManager();

$config = Config::load();
$request = Request::createFromGlobals();
$currentSiteService = CurrentSite::buildFromConfig($config);
$currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);
$imagesService = new ImagesService($config, $currentSiteService, new Filesystem());

$orderUrl = LegacyCodeHelper::getRouteParam("url");
$order = $om->get(["order_url" => $orderUrl]);


if (!$order) {
    throw new NotFoundException("Order $orderUrl not found.");
}

$o = $order;
$content = '';

if (_isAnonymousOrder($order) || _orderBelongsToVisitor($order, $currentUserService) || $currentUserService->isAdmin()) {

    $buttons = NULL;

    $request->attributes->set("page_title", "Commande n° {$o["order_id"]}");

    $content .= '<h2>Commande n° ' . $o["order_id"] . '</h2>';

    if ($currentUserService->isAdmin()) {
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

    if ($order->has("phone")) {
        $content .= '<p>Tel: ' . $order->get("phone") . '</p>';
    }

    if ($order->has('comment') && $currentUserService->isAdmin()) {
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

    // Paiement
    if (!$o["order_payment_date"]) $buttons .= '<a href="/payment/' . $o["order_url"] . '" class="btn btn-primary"><i class="fa fa-money"></i>&nbsp; Payer la commande (' . currency($o["order_amount_tobepaid"] / 100) . ')</a> ';
    else $buttons .= '<a href="/invoice/' . $o["order_url"] . '" class="btn btn-default"><i class="fa fa-print"></i> Imprimer une facture</a> ';

    $currentSite = $currentSiteService->getSite();
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
        $article = $copy->getArticle() ?: ArticleManager::buildUnknownArticle();
        $stockItem = StockQuery::create()->findPk($copy->get('id'));
        $articleModel = $stockItem->getArticle();
        $a = $article;
        $i++;

        // Image
        $cover = null;
        $stockItemPhotoUrl = $imagesService->getImageUrlFor($stockItem, height: 60);
        $articleCoverUrl = $imagesService->getImageUrlFor($articleModel, height: 60);
        if ($stockItemPhotoUrl) {
            $cover = '<img src="' . $stockItemPhotoUrl . '" alt="' . $articleModel->getTitle() . '" height="60" />';
        } elseif ($articleCoverUrl) {
            $cover = '<img src="' . $articleCoverUrl . '" alt="' . $articleModel->getTitle() . '" /></a>';
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
                        $a['dl_links'] .= ' <a href="' . $f->getUrl() . '" title="' . $f->get('version') . ' | ' . file_size($f->get('size')) . ' | ' . $f->getType('name') . '"><img src="' . $f->getType('icon') . '" width=16 alt="Télécharger"> ' . $f->get('title') . '</a> &nbsp;';
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
        if ($currentUserService->isAdmin()) {
            $copyId = '<a href="/pages/adm_stock?id=' . $copyId . '">' . $copyId . '</a>';
        }

        $books .= '
            <tr>
                <td class="center">' . $copyId . '</td>
                <td class="center">' . $cover . '</td>
                <td>
                    <strong><a href="/a/' . $a["article_url"] . '">' . $article->get("title") . '</a><br /></strong>
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

    if (isset($o["order_weight"]) && $currentSite->getShippingFee()) {
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

    $contactPageUrl = \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate("main_contact");
    $content .= '
                <tr>
                    <th colspan="3" class="right">Total T.T.C.&nbsp;:</th>
                    <th class="right">' . currency(($o["order_amount"] + $o["order_shipping"]) / 100) . '</th>
                </tr>
            </tfoot>
        </table>
        
        <p class="text-center">
            <strong>
                Un problème avez votre commande ? 
                <a href="'.$contactPageUrl.'" class="btn btn-info">Contactez-nous</a>
            </strong>
        </p>
    ';

} elseif (!$currentUserService->isAuthentified()) {
    throw new UnauthorizedHttpException("", "Vous n'avez pas le droit d'accéder à cette page.");
} else {
    throw new AccessDeniedHttpException("Vous n'avez pas le droit d'accéder à cette page.");
}

return new Response($content);

function _isAnonymousOrder(Order $order): bool
{
    return !$order->has("user_id");
}

function _orderBelongsToVisitor(Order $order, CurrentUser $currentUser): bool
{
    if (!$currentUser->isAuthentified()) {
        return false;
    }

    return $order->get("user_id") === $currentUser->getUser()->getId();
}
