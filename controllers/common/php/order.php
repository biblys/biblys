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

/** @noinspection CommaExpressionJS */
/** @noinspection DuplicatedCode */
/** @noinspection BadExpressionStatementJS */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use DansMaCulotte\MondialRelay\DeliveryChoice;
use Model\OrderQuery;
use Model\StockQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;


return function (
    CurrentUser   $currentUserService,
    Request       $request,
    Config        $config,
    CurrentSite   $currentSiteService,
    ImagesService $imagesService,
    UrlGenerator  $urlGenerator
): Response {
    $om = new OrderManager();

    $orderUrl = LegacyCodeHelper::getRouteParam("url");
    $orderEntity = $om->get(["order_url" => $orderUrl]);

    if (!$orderEntity) {
        throw new NotFoundException("Order $orderUrl not found.");
    }

    $order = OrderQuery::create()->findPk($orderEntity->get('id'));

    $o = $orderEntity;
    $content = '';

    if (_isAnonymousOrder($orderEntity) || _orderBelongsToVisitor($orderEntity, $currentUserService) || $currentUserService->isAdmin()) {

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

        $content .= '
            <div class="floatR">
                <h4 class="text-right">État de la commande</h4>
                <p class="right">Validée le ' . _date($o["order_insert"], 'j f Y') . '</p>
        ';

        if ($o["order_followup_date"]) {
            $content .= '<p class="right">Relancée le ' . _date($o["order_followup_date"], 'j f Y') . '</p>';
        }
        if ($o["order_payment_date"]) {
            $content .= '<p class="right">Payée le ' . _date($o["order_payment_date"], 'j f Y') . '</p>';
        }
        if ($orderEntity->has('shipping_date')) {
            if ($orderEntity->get('shipping_mode') == 'magasin') {
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

        $country = $orderEntity->get('country');
        if ($country instanceof Country) {
            $country = $country->get('name');
        }

        if (!empty($o["order_address2"])) $o["order_address2"] = $o["order_address2"] . '<br />';

        if (in_array($orderEntity->get("shipping_mode"), ["normal", "colissimo"])) {
            $content .= '
                <h4>Adresse de livraison</h4>
                <p>
                    ' . $o["order_firstname"] . ' ' . $o["order_lastname"] . '<br />
                    ' . $o["order_address1"] . '<br />
                    ' . $o["order_address2"] . '
                    ' . $o["order_postalcode"] . ' ' . $o["order_city"] . '<br />
                    ' . $country . '
                </p>
            ';
        } elseif ($orderEntity->get("shipping_mode") === "retrait") {
            $content .= '<p>Retrait en magasin</p>';
        } elseif ($orderEntity->get("shipping_mode") === "mondial-relay") {
            if ($config->isMondialRelayEnabled()) {
                $content .= '<p></p>';

                $delivery = new DeliveryChoice([
                    "site_id" => $config->get("mondial_relay.code_enseigne"),
                    "site_key" => $config->get("mondial_relay.private_key"),
                ]);
                try {
                    $pickupPoint = $delivery->findPickupPointByCode(
                        country: 'FR',
                        code: $orderEntity->get("mondial_relay_pickup_point_code")
                    );
                    $content .= '
                        <h4>Adresse de livraison</h4>
                        <p>            
                            ' . $o["order_firstname"] . ' ' . $o["order_lastname"] . '<br />
                            Point Mondial Relay n° ' . $orderEntity->get("mondial_relay_pickup_point_code") . '<br>
                            <strong>' . $pickupPoint->name . '</strong><br />
                            ' . $pickupPoint->address . '<br />
                            ' . $pickupPoint->postalCode . ' ' . $pickupPoint->city . '
                        </p>
                    ';
                } catch (\DansMaCulotte\MondialRelay\Exceptions\Exception) {
                    $content .= '<p>Point Mondial Relay inconnu (n° '.$orderEntity->get("mondial_relay_pickup_point_code").').</p>';
                }
            }

            $content .= '
                <h4>Adresse de facturation</h4>
                <p>
                    ' . $o["order_firstname"] . ' ' . $o["order_lastname"] . '<br />
                    ' . $o["order_address1"] . '<br />
                    ' . $o["order_address2"] . '
                    ' . $o["order_postalcode"] . ' ' . $o["order_city"] . '<br />
                    ' . $country . '
                </p>
            ';
        } else {
            $content .= '
                <h4>Coordonnées</h4>
                <p>
                    ' . $o["order_firstname"] . ' ' . $o["order_lastname"] . '<br />
                    ' . $o["order_address1"] . '<br />
                    ' . $o["order_address2"] . '
                    ' . $o["order_postalcode"] . ' ' . $o["order_city"] . '<br />
                    ' . $country . '
                </p>
            ';
        }

        $content .= '<p><a href="mailto:' . $o["order_email"] . '">' . $o["order_email"] . '</a></p>';

        if ($orderEntity->has("phone")) {
            $content .= '<p>Tel: ' . $orderEntity->get("phone") . '</p>';
        }

        if ($orderEntity->has('comment') && $currentUserService->isAdmin()) {
            $content .= '
                <h4>Commentaire du client</h4>
                <p>' . nl2br($orderEntity->get('comment')) . '</p>
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
        $paymentPageUrl = $urlGenerator->generate("payment_pay", ["slug" => $order->getSlug()]);
        if (!$o["order_payment_date"]) $buttons .= '<a href="'.$paymentPageUrl.'" class="btn btn-primary"><i class="fa-regular fa-money-bill-1"></i>&nbsp; Payer la commande (' . currency($o["order_amount_tobepaid"] / 100) . ')</a> ';

        $trackingLink = $order->getTrackingLink();
        if ($trackingLink) {
            $content .= '
                <form class="form-inline text-center">
                  <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Numéro de suivi</div>
                    </div>
                    <input type="text" class="form-control" id="inlineFormInputGroupUsername2" value="' . $o["order_track_number"] . '">
                  </div>
                
                  <a href="'.$trackingLink.'" class="btn btn-primary mb-2">
                      Suivre l’envoi
                    </a>
                </form>
            ';
        }

        $content .= $buttons;

        $content .= '<br /></div>';

        $copies = $orderEntity->getCopies();

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

            // Image
            $stockItemImageUrl = $imagesService->getImageUrlFor($stockItem);
            $stockItemThumbnailUrl = $imagesService->getImageUrlFor($stockItem, height: 100);
            $articleImageUrl = $imagesService->getImageUrlFor($articleModel);
            $articleThumbnailUrl = $imagesService->getImageUrlFor($articleModel, height: 100);
            $imageUrl = $stockItemImageUrl ?: $articleImageUrl;
            $thumbnailUrl = $stockItemThumbnailUrl ?: $articleThumbnailUrl;
            $cover = $thumbnailUrl ? '
                <a href="'.$imageUrl.'" rel="lightbox">
                    <img src="' . $thumbnailUrl . '" alt="' . $articleModel->getTitle() . '" height="100" />
                </a>
            ' : null;

            if ($a["article_pubdate"] > $o["order_insert"]) $a["article_title"] .= ' (précommande)';

            $a['dl_links'] = NULL;

            // Downloadable files
            if ($orderEntity->isPayed() && $article->getType()->isDownloadable()) {
                if ($article->isPublished()) {
                    $fm = new FileManager();
                    $files = $fm->getAll(['article_id' => $a['article_id'], 'file_access' => 1]);
                    if ($files) {
                        foreach ($files as $f) {
                            $a['dl_links'] .= ' <a href="' . $f->getUrl() . '" title="' . $f->get('version') . ' | ' . file_size($f->get('size')) . ' | ' . $f->getType('name') . '"><img src="' . $f->getType('icon') . '" width=16 alt="Télécharger"> ' . $f->get('title') . '</a> &nbsp;';
                        }
                        $a['dl_links'] = '<div class="btn btn-outline-secondary"><i class="fa fa-cloud-download"></i> &nbsp; ' . $a['dl_links'] . '</a>';
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
            if (!empty($a["stock_condition"])) $books .= 'État : ' . $a["stock_condition"] . '<br />';
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

        $orderWeight = $order->getTotalWeight();
        if ($orderWeight > 0) {
            $content .= '
                <tr>
                    <th colspan="3" class="right">Poids :</th>
                    <th class="right">' . round($orderWeight / 1000, 2) . '&nbsp;kg</th>
                </tr>
            ';
        }

        $shippingOption = $order->getShippingOption();
        if ($shippingOption) {
            $content .= '
                <tr>
                    <th colspan="3" class="right">Frais de port :</th>
                    <th class="right">' . currency($shippingOption->getFee() / 100) . '</th>
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

        $contactPageUrl = $urlGenerator->generate("main_contact");
        $content .= '
                <tr>
                    <th colspan="3" class="right">Total T.T.C.&nbsp;:</th>
                    <th class="right">' . currency(($o["order_amount"] + $o["order_shipping"]) / 100) . '</th>
                </tr>
            </tfoot>
        </table>
        
        <p class="text-center">
            <strong>
                Un problème avec votre commande ? 
                <a href="' . $contactPageUrl . '" class="btn btn-info">Contactez-nous</a>
            </strong>
        </p>
      ';

    } elseif (!$currentUserService->isAuthenticated()) {
        throw new UnauthorizedHttpException("", "Vous n'avez pas le droit d'accéder à cette page.");
    } else {
        throw new AccessDeniedHttpException("Vous n'avez pas le droit d'accéder à cette page.");
    }

    return new Response($content);
};

function _isAnonymousOrder(Order $order): bool
{
    return !$order->has("user_id");
}

function _orderBelongsToVisitor(Order $order, CurrentUser $currentUser): bool
{
    if (!$currentUser->isAuthenticated()) {
        return false;
    }

    return $order->get("user_id") === $currentUser->getUser()->getId();
}
