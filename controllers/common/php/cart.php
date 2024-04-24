<?php

/** @noinspection PhpUnhandledExceptionInspection */

global $urlgenerator;

use Biblys\Legacy\CartHelpers;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Model\CartQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @throws InvalidDateFormatException
 * @throws PropelException
 */

return function (
    Request $request,
    Config $config,
    CurrentSite $currentSite,
    CurrentUser $currentUser,
    UrlGenerator $urlGenerator,
): Response
{
    $om = new OrderManager();

    $content = null;

    $imagesService = new ImagesService(config: $config, filesystem: new Filesystem());

    $currentUrlService = new CurrentUrlService($request);
    $currentUrl = $currentUrlService->getRelativeUrl();
    $loginUrl = $urlGenerator->generate("user_login", ["return_url" => $currentUrl]);

    $cartId = $request->query->get('cart_id', false);
    if ($cartId) {

        if (!$currentUser->isAdmin()) {
            throw new AccessDeniedHttpException("Seuls les administrateurs peuvent prévisualiser un panier.");
        }

        /** @var \Model\Cart $cart */
        $cart = CartQuery::create()->filterBySite($currentSite->getSite())->findPk($cartId);
        if (!$cart) {
            throw new NotFoundException(sprintf("Panier %s introuvable.", htmlentities($cartId)));
        }
    } else {
        $cart = $currentUser->getOrCreateCart();
    }

    $request->attributes->set("page_title", "Panier");

    $alert = null;
    $OneArticle = 0;
    $crowdfunding = 0;
    $Poids = 0;
    $Total = 0;
    $Articles = 0;
    $pre_order = 0;
    $on_order = 0;
    $downloadable = 0;

    $sm = new StockManager();
    $stocks = $sm->getAll([
        'cart_id' => $cart->getId(),
        'site_id' => $currentSite->getSite()->getId(),
    ], ['order' => 'stock_cart_date']);

    $cart_content = array();

    foreach ($stocks as $stock) {
        /** @var Article $article */
        $article = $stock->get('article');
        $type = $article->getType();

        // Cover
        $cover = null;
        if ($stock->hasPhoto()) {
            $cover = $stock->getPhotoTag(['size' => 'h60', 'rel' => 'lightbox', 'class' => 'cover']);
        } elseif ($article->hasCover()) {
            $cover = $article->getCoverTag(['size' => 'h60', 'rel' => 'lightbox', 'class' => 'cover']);
        }

        // Books & ebooks
        $article_type = null;
        $purchased = null;
        if ($type->getId() == 2) {
            $article_type = ' (numérique)';
            $articleModel = \Model\ArticleQuery::create()->findPk($article->get("id"));
            if ($currentUser->hasPurchasedArticle($articleModel)) {
                $purchased = '<p class="warning left"><a href="/pages/log_mybooks" title="Vous avez déjà acheté ce titre. Juste pour info.">Déjà acheté !</a></p>';
            }
        }

        // Physical or downloadable types
        if ($type->isDownloadable()) {
            $downloadable++;
        }

        // Crowdfunding
        if ($stock->has('campaign')) {
            $crowdfunding++;
        }

        // On order
        $availability = null;
        if (!$stock->get('purchase_date') && $currentSite->getSite()->getPublisherId() === null) {
            $on_order = 1;
            $availability = '<span class="fa fa-square lightblue" title="Sur commande"></span>&nbsp;';
        }

        // Preorder
        $preorder = null;
        if ($article->get('pubdate') > date("Y-m-d")) {
            $pre_order = 1;
            $preorder = '<p class="warning left">&Agrave; para&icirc;tre le '._date($article->get('pubdate'), 'd/m/Y').'</p>';
            $availability = '<span class="fa fa-square lightblue" title="Précommande"></span>&nbsp;';
        }

        // Editable price
        $editable_price_form = null;
        if ($article->has('price_editable')) {
            $editable_price_form = '
            <form action="/stock/'.$stock->get("id").'/edit-free-price" method="post">
                <fieldset>
                    <input type="hidden" name="stock_id" value="'.$stock->get('id').'">
                    Modifier le montant :
                        <input type="number" name="new_price" min="'.($article->get('price') / 100).'" value="'.($stock->get('selling_price') / 100).'" step=10 class="nano" required> &euro;
                    <button type="submit" class="btn btn-info btn-xs">OK</button>
                </fieldset>
            </form>
        ';
        }

        $articleUrl = $urlGenerator->generate("article_show", ["slug" => $article->get("url")]);

        $cart_content[] = '
        <tr id="cart_tr_'.$stock->get('id').'">
            <td class="center">'.$stock->get('id').'</td>
            <td class="center">'.$cover.'</td>
            <td>
                <a href="'.$articleUrl.'">'.$article->get('title').'</a>'.$article_type.'<br>
                de '.authors($article->get('authors')).'<br>
                coll. '.$article->get('collection')->get('name').' '.numero($article->get('number')).'<br>
                '.$purchased.$preorder.'
                '.($stock->has('condition') ? 'État : '.$stock->get('condition').'<br>' : null).'
                '.$editable_price_form.'
            </td>
            '.($currentSite->getSite()->getShippingFee() == "fr" ? '<td class="right">'.$stock->get('weight').'g</td>' : null).'
            <td class="right">
                '.$availability.'
                '.currency($stock->get('selling_price') / 100).'<br />
            </td>
            <td class="center">
                <form method="POST" action="/cart/remove-stock/'.$stock->get("id").'">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <span class="fa fa-close"></span> Retirer
                    </button>
                </form>
            </td>
        </tr>
    ';

        if ($OneArticle == 0) {
            $OneArticle = $article->get('id');
        } elseif ($OneArticle != $article->get('id')) {
            $OneArticle = "no";
        }

        // Totaux
        $Poids += $stock->get('weight');
        $Total += $stock->get('selling_price');
        $Articles++;
    }

    if ($currentUser->isAdmin()) {
        $content .= '
        <div class="admin">
            <p>Panier n&deg; '.$cart->getId().'</p>
        </div>
    ';
    }

    $content .= '
    <h1><i class="fa fa-shopping-cart"></i> Mon panier</h1>

    '.$alert.'

    <table class="table cart-table">
        <thead>
            <tr>
                <th>Ref.</th>
                <th></th>
                <th>Article</th>
';
    if ($currentSite->getSite()->getShippingFee() == "fr") {
        $content .= '<th class="center">Poids</th>';
    }
    $content .= '
                <th class="center">Prix</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
'.implode($cart_content);

    if (isset($Articles) && $Articles > 0) {
        if (!$currentUser->isAuthentified()) {
            $content .= '
            <p class="warning">
                Attention : '."vous n'êtes pas connecté".'. Si vous quittez le site, votre
                panier ne sera pas sauvegardé.
                <a href="'.$loginUrl.'">Connectez-vous</a> 
                pour sauvegarder votre panier.
            </p><br />';
        }

        // Deja une commande en cours ?
        if ($currentUser->isAuthentified()) {
            $order = $om->get(
                [
                    'order_type' => 'web',
                    'user_id' => $currentUser->getUser()->getId(),
                    'order_payment_date' => 'NULL',
                    'order_shipping_date' => 'NULL',
                    'order_cancel_date' => 'NULL'
                ]
            );
            if ($order) {
                $o = $order;

                $content .= '
                </table>

                <h3>Commande en cours (n&deg; <a href="/order/'.$o["order_url"].'">'.$o["order_id"].'</a>)</h3>

                <p>Vous avez déj&agrave; une commande en attente de paiement. Les livres de votre panier seront ajoutés aux livres de la commande ci-dessous et les frais de port recalculés en conséquence. Si vous ne souhaitez plus commander les livres de la commande n&deg; '.$o["order_id"].', <a href="/contact/">contactez-nous</a> pour faire annuler la commande.</p>

                <br />
                <table class="table">
                    <tbody>
            ';

                $copies = $order->getCopies();
                foreach ($copies as $copy) {
                    $s = $copy;
                    $article = $copy->getArticle();

                    // Image
                    $s["couv"] = null;
                    $stockPhoto = new Media("stock", $s["stock_id"]);
                    $articleCover = new Media("article", $article->get("id"));
                    if ($stockPhoto->exists()) {
                        $s["couv"] = '<a href="'.$stockPhoto->getUrl().'" rel="lightbox"><img src="'.$stockPhoto->getUrl(["size" => "h60"]).'" alt="'.$s["article_title"].'" height="60" /></a>';
                    } elseif ($articleCover->exists()) {
                        $s["couv"] = '<a href="'.$articleCover->getUrl().'" rel="lightbox"><img src="'.$articleCover->getUrl(["size" => "h60"]).'" alt="'.$article->get('title').'" /></a>';
                    }

                    $content .= '
                    <tr>
                        <td>'.$s["stock_id"].'</td>
                        <td>'.$s["couv"].'</td>
                        <td>
                            <a href="'.$urlGenerator->generate('article_show', ['slug' => $article->get('url')]).'">
                                '.$article->get('title').'
                            </a><br />
                            de '.authors($article->get('authors')).'<br />
                            coll. '.$article->get('collection')->get('name').' '.numero($article->get('number')).'<br />
                ';
                    if (!empty($s["stock_condition"])) {
                        $content .= 'État : '.$s["stock_condition"].'<br />';
                    }
                    $content .= '
                        </td>
                ';
                    if ($currentSite->getSite()->getShippingFee() == "fr") {
                        $content .= '<td class="right">'.$s["stock_weight"].'g</td>';
                    }
                    $content .= '
                        <td class="right">
                            '.currency($s["stock_selling_price"] / 100).'<br />
                        </td>
                        <td class="center">
                        </td>
                    </tr>
                ';

                    // Totaux
                    $Poids += $s["stock_weight"];
                    $Total += $s["stock_selling_price"];
                    $Articles++;
                }
            }
        }

        $specialOfferNotice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            $cart
        );

        $content .= '
                '.$specialOfferNotice.'
            </tbody>
            <tfoot>
                <tr class="bold">
                    <td colspan="3" class="right">Total :</td>
        ';

        if ($currentSite->getSite()->getShippingFee() == "fr") {
            $content .= '<td class="right">'.$Poids.'g <input type="hidden" id="stock_weight" value="'.$Poids.'"></td>';
        }
        $content .= '
                    <td class="right">'.currency($Total / 100).' <input type="hidden" id="sub_total" value="'.$Total.'"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    ';

        // Pre-order books
        if ($pre_order) {
            $content .= '<p class="warning">Certains des livres de votre panier (<span class="fa fa-square lightblue" title="Précommande"></span>) sont à paraître. Votre commande sera expédiée lorsque tous les articles qu\'elle contient seront parus.</p>';
        }

        // On order books
        if ($on_order) {
            $content .= '<p class="warning">Certains des livres de votre panier (<span class="fa fa-square lightblue" title="Sur commande"></span>) ne sont pas disponibles en stock et doivent être commandés. L\'expédition de votre commande peut être retardée de 72h.</p>';
        }

        $content .= CartHelpers::getCartSuggestions($currentSite, $urlGenerator, $imagesService);

        $content .= '
            <form id="validate_cart" action="order_delivery" method="get">
                <fieldset>
        ';

        // If cart contains physical articles that needs to be shipped
        if (CartHelpers::cartNeedsShipping($cart)) {
            $com = new CountryManager();

            // Countries
            $countries = $com->getAll();
            $destinations = array_map(function ($country) {
                return '<option value="'.$country->get('id').'">'.$country->get('name').'</option>';
            }, $countries);
            $default_destination = $com->get(["country_name" => "France"]);

            if ($currentUser->isAuthentified()) {
                $customer = $currentUser->getOrCreateCustomer();
                $country_id = $customer->getCountryId();
                $country = $com->getById($country_id);
                if ($country) {
                    $default_destination = $country;
                }
            }

            // Poids > 30 kg
            if ($Poids >= 29950) {
                $plus30 = '<br><p class="warning"> Attention ! Votre commande excède le poids maximum autorisé par La Poste pour un colis (30 kg). Votre commande sera expédiée en plusieurs colis.</p>';
            } else {
                $plus30 = null;
            }

            $freeShippingNotice = CartHelpers::getFreeShippingNotice($currentSite, $cart, $Total);

            $content .= '
                <h3>Mode d\'expédition</h3>
                
                '.$freeShippingNotice.'

                '.$plus30.'

                <input type="hidden" id="order_weight" value="'.$Poids.'">
                <input type="hidden" id="order_amount" value="'.$Total.'">
                <input type="hidden" id="articles_num" value="'.$Articles.'">

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 200px;">Pays de destination</th>
                            <th>Mode d\'expédition</th>
                            <th style="width: 75px;">Frais</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select id="country_id" name="country_id" class="form-control" required>
                                    <option value="'.$default_destination->get('id').'">'.$default_destination->get('name').'</option>
                                    '.implode($destinations).'
                                </select>
                            </td>
                            <td>
                                <select id="shipping_id" name="shipping_id" class="form-control" disabled required>
                                    <option>Choisir un pays de destination...</option>
                                </select>
                            </td>
                            <td id="shipping_amount" class="right" style="vertical-align: middle;">0,00&nbsp;&euro;</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <p id="shipping_info">Choisissez un mode d\'expédition ci-dessus pour continuer.</p>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="bold right">Montant &agrave; régler :</td>
                            <td id="total" class="right bold">'.currency($Total / 100).'</td>
                        </tr>
                    </tbody>
                </table>
        ';
        }

        $salesDisabled = $currentSite->getOption("sales_disabled");

        if ($salesDisabled) {
            $content .= '<p class="alert alert-warning">La vente en ligne est temporairement désactivée sur ce site.</p>';
        } elseif ($downloadable && !$currentUser->isAuthentified()) {
            $content .= '<br />'
               .'<div class="center">'
               .'<p class="warning">Votre panier contient au moins un livre numérique. Vous devez vous <a href="'.$loginUrl.'">identifier</a> pour continuer.</p>'
               .'<button type="button" disabled class="btn btn-default">Finaliser la commande</button>'
               .'</div>';

            // If cart contains crowdfunding rewards and user not logged
        } elseif (!empty($crowdfunding) && !$currentUser->isAuthentified()) {
            $content .= '<br>'
               .'<div class="center">'
               .'<p class="warning">Votre panier contient au moins une contrepartie de financement participatif.<br>Vous devez vous <a href="'.$loginUrl.'">identifier</a> pour continuer.</p>'
               .'<button type="button" disabled class="btn btn-default">Finaliser la commande</button>'
               .'</div>';
        } elseif (isset($o["order_id"])) {
            $content .= '<div class="center"><button type="submit" class="btn btn-primary" id="continue">Ajouter à la commande en cours</button></div>';
        } else {
            $content .= '<div class="center"><button type="submit" class="btn btn-primary" id="continue">Finaliser votre commande</button></div>';
        }

        $content .= '
            </fieldset>
        </form>
    ';
    } else {
        $content .= '</table><p class="center">Votre panier est vide !</p>';
    }

    return new Response($content);
};

