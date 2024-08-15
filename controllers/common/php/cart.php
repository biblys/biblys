<?php

/** @noinspection PhpUnhandledExceptionInspection */

global $urlgenerator;

use Biblys\Legacy\CartHelpers;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Model\ArticleQuery;
use Model\CartQuery;
use Model\StockQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request         $request,
    Config          $config,
    CurrentSite     $currentSite,
    CurrentUser     $currentUser,
    UrlGenerator    $urlGenerator,
    ImagesService   $imagesService,
    TemplateService $templateService,
    MetaTagsService $metaTagsService,
): Response {
    $metaTagsService->disallowSeoIndexing();

    $om = new OrderManager();

    $content = null;

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

    $OneArticle = 0;
    $crowdfunding = 0;
    $Poids = 0;
    $Total = 0;
    $Articles = 0;
    $pre_order = 0;
    $downloadable = 0;

    $sm = new StockManager();
    /** @var Stock[] $stocks */
    $stocks = $sm->getAll([
        'cart_id' => $cart->getId(),
        'site_id' => $currentSite->getSite()->getId(),
    ], ['order' => 'stock_cart_date']);

    $cartPhysicalItems = [];
    $cartDownloadableItems = [];

    foreach ($stocks as $stockEntity) {
        $stockItem = StockQuery::create()->findPk($stockEntity->get("id"));
        /** @var Article $articleEntity */
        $articleEntity = $stockEntity->get('article');
        $article = ArticleQuery::create()->findPk($stockEntity->get("article_id"));
        $type = $articleEntity->getType();

        // Cover
        $cover = null;
        if ($imagesService->imageExistsFor($stockItem)) {
            $cover = $templateService->render("AppBundle:StockItem:_photo.html.twig", [
                    "stockItem" => $stockItem,
                    "height" => 60,
                    "class" => "cover",
                    "rel" => "lightbox",
                ]
            );
        } elseif ($imagesService->imageExistsFor($article)) {
            $cover = $templateService->render("AppBundle:Article:_cover.html.twig", [
                    "article" => $article,
                    "height" => 60,
                    "class" => "cover",
                    "link" => false,
                ]
            );
        }

        // Books & ebooks
        $articleType = null;
        $purchased = null;
        $articleModel = ArticleQuery::create()->findPk($articleEntity->get("id"));
        if ($type->getId() == 2) {
            $articleType = ' <strong>(numérique)</strong>';
            /** @var \Model\Article $articleModel */
            if ($currentUser->hasPurchasedArticle($articleModel)) {
                $purchased = '<p class="warning left"><a href="/pages/log_mybooks" title="Vous avez déjà acheté ce titre. Juste pour info.">Déjà acheté !</a></p>';
            }
        }

        // Physical or downloadable types
        if ($type->isDownloadable()) {
            $downloadable++;
        }

        // Crowdfunding
        if ($stockEntity->has('campaign')) {
            $crowdfunding++;
        }

        // Preorder
        $availability = "";
        $preorder = null;
        if ($articleEntity->get('pubdate') > date("Y-m-d")) {
            $pre_order = 1;
            $preorder = '<p class="warning left">&Agrave; para&icirc;tre le ' . _date($articleEntity->get('pubdate'), 'd/m/Y') . '</p>';
            $availability = '<span class="fa fa-square lightblue" title="Précommande"></span>&nbsp;';
        }

        // Editable price
        $editable_price_form = null;
        if ($articleEntity->has('price_editable')) {
            $editable_price_form = '
            <form action="/stock/' . $stockEntity->get("id") . '/edit-free-price" method="post">
                <fieldset>
                    <input type="hidden" name="stock_id" value="' . $stockEntity->get('id') . '">
                    Modifier le montant :
                        <input type="number" name="new_price" min="' . ($articleEntity->get('price') / 100) . '" value="' . ($stockEntity->get('selling_price') / 100) . '" step=10 class="nano" required> &euro;
                    <button type="submit" class="btn btn-info btn-xs">OK</button>
                </fieldset>
            </form>
        ';
        }

        $articleUrl = $urlGenerator->generate("article_show", ["slug" => $articleEntity->get("url")]);

        $cartLine = '
            <tr id="cart_tr_' . $stockEntity->get('id') . '">
                <td class="center">' . $cover . '</td>
                <td>
                    <a href="' . $articleUrl . '">' . $articleEntity->get('title') . '</a>' . $articleType . '<br>
                    de ' . authors($articleEntity->get('authors')) . '<br>
                    coll. ' . $articleEntity->get('collection')->get('name') . ' ' . numero($articleEntity->get('number')) . '<br>
                    ' . $purchased . $preorder . '
                    ' . ($stockEntity->has('condition') ? 'État : ' . $stockEntity->get('condition') . '<br>' : null) . '
                    ' . $editable_price_form . '
                </td>
                ' . ($currentSite->getSite()->getShippingFee() == "fr" ? '<td class="right">' . $stockEntity->get('weight') . 'g</td>' : null) . '
                <td class="right">
                    ' . $availability . '
                    ' . currency($stockEntity->get('selling_price') / 100) . '<br />
                </td>
                <td class="center">
                    <form method="POST" action="/cart/remove-stock/' . $stockEntity->get("id") . '">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <span class="fa fa-close"></span> Retirer
                        </button>
                    </form>
                </td>
            </tr>
        ';

        if ($articleEntity->isPhysical()) {
            $cartPhysicalItems[] = $cartLine;
        }

        if ($articleEntity->isDownloadable()) {
            $cartDownloadableItems[] = $cartLine;
        }

        if ($OneArticle == 0) {
            $OneArticle = $articleEntity->get('id');
        } elseif ($OneArticle != $articleEntity->get('id')) {
            $OneArticle = "no";
        }

        // Totaux
        $Poids += $stockEntity->get('weight');
        $Total += $stockEntity->get('selling_price');
        $Articles++;
    }

    if ($currentUser->isAdmin()) {
        $content .= '
            <div class="admin">
                <p>Panier n&deg; ' . $cart->getId() . '</p>
            </div>
        ';
    }

    $content .= '
        <h1><i class="fa fa-shopping-basket"></i> Mon panier</h1>
    
        <table class="table cart-table">
    ';

    if ($cart->containsPhysicalArticles()) {
        $content .= '
            <thead>
                <tr>
                  <th colspan="100">Articles qui seront expédiés</th>
                </tr>
            </thead>
            <tbody>
                ' . implode($cartPhysicalItems) . '
            </tbody>
        ';
    }

    if ($cart->containsDownloadableArticles()) {
        $content .= '
            <thead>
                <tr>
                  <th colspan="100">Articles numériques à télécharger</th>
                </tr>
            </thead>
            <tbody>
                ' . implode($cartDownloadableItems) . '
            </tbody>
        ';
    }


    if (isset($Articles) && $Articles > 0) {
        if (!$currentUser->isAuthentified()) {
            $content .= '
            <p class="warning">
                Attention : ' . "vous n'êtes pas connecté" . '. Si vous quittez le site, votre
                panier ne sera pas sauvegardé.
                <a href="' . $loginUrl . '">Connectez-vous</a> 
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

                <h2>Commande en cours (n&deg; <a href="/order/' . $o["order_url"] . '">' . $o["order_id"] . '</a>)</h2>

                <p>Vous avez déj&agrave; une commande en attente de paiement. Les livres de votre panier seront ajoutés aux livres de la commande ci-dessous et les frais de port recalculés en conséquence. Si vous ne souhaitez plus commander les livres de la commande n&deg; ' . $o["order_id"] . ', <a href="/contact/">contactez-nous</a> pour faire annuler la commande.</p>

                <br />
                <table class="table">
                    <tbody>
            ';

                $copies = $order->getCopies();
                foreach ($copies as $copy) {
                    $s = $copy;
                    $articleEntity = $copy->getArticle();
                    $stockItem = ArticleQuery::create()->findPk($s["stock_id"]);
                    $articleModel = $stockItem->getArticle();

                    // Image
                    $s["couv"] = null;
                    $stockItemPhotoUrl = $imagesService->getImageUrlFor($stockItem, height: 60);
                    $stockItemPhotoThumbnailUrl = $imagesService->getImageUrlFor($stockItem, height: 60);
                    $articleCoverUrl = $imagesService->getImageUrlFor($articleModel, height: 100);
                    if ($stockItemPhotoUrl) {
                        $s["couv"] = '<a href="' . $stockItemPhotoUrl . '" rel="lightbox"><img src="' . $stockItemPhotoThumbnailUrl . '" alt="' . $s["article_title"] . '" height="60" /></a>';
                    } elseif ($articleCoverUrl) {
                        $s["couv"] = '<img src="' . $articleCoverUrl . '" alt="' . $articleEntity->get('title') . '" /></a>';
                    }

                    $content .= '
                    <tr>
                        <td>' . $s["stock_id"] . '</td>
                        <td>' . $s["couv"] . '</td>
                        <td>
                            <a href="' . $urlGenerator->generate('article_show', ['slug' => $articleEntity->get('url')]) . '">
                                ' . $articleEntity->get('title') . '
                            </a><br />
                            de ' . authors($articleEntity->get('authors')) . '<br />
                            coll. ' . $articleEntity->get('collection')->get('name') . ' ' . numero($articleEntity->get('number')) . '<br />
                ';
                    if (!empty($s["stock_condition"])) {
                        $content .= 'État : ' . $s["stock_condition"] . '<br />';
                    }
                    $content .= '
                        </td>
                ';
                    if ($currentSite->getSite()->getShippingFee() == "fr") {
                        $content .= '<td class="right">' . $s["stock_weight"] . 'g</td>';
                    }
                    $content .= '
                        <td class="right">
                            ' . currency($s["stock_selling_price"] / 100) . '<br />
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

        $content .= '
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td class="text-right">' . currency($Total / 100) . ' <input type="hidden" id="sub_total" value="' . $Total . '"></td>
                    </tr>
                </tfoot>
            </table>
        ';


        $content .= CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            $imagesService,
            $templateService,
            $cart
        );

        // Pre-order books
        if ($pre_order) {
            $content .= '<p class="warning">Certains des livres de votre panier (<span class="fa fa-square lightblue" title="Précommande"></span>) sont à paraître. Votre commande sera expédiée lorsque tous les articles qu\'elle contient seront parus.</p>';
        }

        $content .= CartHelpers::getCartSuggestions($currentSite, $urlGenerator, $imagesService);

        /** @noinspection HtmlUnknownTarget */
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
                return '<option value="' . $country->get('id') . '">' . $country->get('name') . '</option>';
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
                <h2>Mode d\'expédition</h2>
                
                ' . $freeShippingNotice . '

                ' . $plus30 . '

                <input type="hidden" id="order_weight" value="' . $Poids . '">
                <input type="hidden" id="order_amount" value="' . $Total . '">
                <input type="hidden" id="articles_num" value="' . $Articles . '">

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
                                    <option value="' . $default_destination->get('id') . '">' . $default_destination->get('name') . '</option>
                                    ' . implode($destinations) . '
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
                            <td id="total" class="right bold">' . currency($Total / 100) . '</td>
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
                . '<div class="center">'
                . '<p class="warning">Votre panier contient au moins un livre numérique. Vous devez vous <a href="' . $loginUrl . '">identifier</a> pour continuer.</p>'
                . '<button type="button" disabled class="btn btn-default">Finaliser la commande</button>'
                . '</div>';

            // If cart contains crowdfunding rewards and user not logged
        } elseif (!empty($crowdfunding) && !$currentUser->isAuthentified()) {
            $content .= '<br>'
                . '<div class="center">'
                . '<p class="warning">Votre panier contient au moins une contrepartie de financement participatif.<br>Vous devez vous <a href="' . $loginUrl . '">identifier</a> pour continuer.</p>'
                . '<button type="button" disabled class="btn btn-default">Finaliser la commande</button>'
                . '</div>';
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

