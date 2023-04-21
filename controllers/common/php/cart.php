<?php

use Biblys\Service\Config;
use Biblys\Service\CurrentUrlService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Framework\Exception\AuthException;
use Symfony\Component\Routing\Generator\UrlGenerator;

$am  = new ArticleManager();

$cfrm = new CFRewardManager();
$cm = new CartManager();
$com = new CollectionManager();
$om = new OrderManager();
$shm = new ShippingManager();
$sm = new StockManager();
$um = new UserManager();

$content = null;

$config = Config::load();

/** @var Request $request */
/** @var UrlGenerator $urlGenerator */
$currentUrlService = new CurrentUrlService($request);
$currentUrl = $currentUrlService->getRelativeUrl();
$loginUrl = $urlGenerator->generate("user_login", ["return_url" => $currentUrl]);

$cart_id = $request->query->get('cart_id', false);
if ($cart_id) {

    if (!$_V->isAdmin()) {
        throw new AuthException("Seuls les administrateurs peuvent prévisualiser un panier.");
    }

    $cart = $cm->getById($cart_id);
    if (!$cart) {
        throw new NotFoundException(sprintf("Panier %s introuvable.", htmlentities($cart_id)));
    }
} else {
    $cart = $_V->getCart('create');
    if (!$cart) {
        throw new Exception("Impossible de créer le panier.");
    }
}

$request->attributes->set("page_title", "Panier");

$alert = null;
$OneArticle = 0;
$books = 0;
$ebooks = 0;
$crowdfunding = 0;
$Poids = 0;
$Total = 0;
$Articles = 0;
$pre_order = 0;
$on_order = 0;
$physical = 0;
$downloadable = 0;
$physical_total_price = 0;

$sm = new StockManager();
$stocks = $sm->getAll(['cart_id' => $cart->get('id')], ['order' => 'stock_cart_date']);

$cart_content = array();

foreach ($stocks as $stock) {
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
    if ($type->getId() === 1) {
        $books++;
    } elseif ($type->getId() == 2) {
        $article_type = ' (numérique)';
        $ebooks++;
        if ($_V->hasPurchased($article)) {
            $purchased = '<p class="warning left"><a href="/pages/log_mybooks" title="Vous avez déjà acheté ce titre. Juste pour info.">Déjà acheté !</a></p>';
        }
    }

    // Physical or downloadable types
    if ($type->isPhysical()) {
        $physical++;
        $physical_total_price += $article->get('price');
    } elseif ($type->isDownloadable()) {
        $downloadable++;
    }

    // Crowdfunding
    if ($stock->has('campaign')) {
        $crowdfunding++;
    }

    // On order
    $availability = null;
    if (!$stock->get('purchase_date') && !$_SITE["publisher_id"]) {
        $on_order = 1;
        $availability = '<i class="fa fa-square lightblue" alt="Sur commande" title="Sur commande"></i>&nbsp;';
    }

    // Preorder
    $preorder = null;
    if ($article->get('pubdate') > date("Y-m-d")) {
        $pre_order = 1;
        $preorder = '<p class="warning left">&Agrave; para&icirc;tre le '._date($article->get('pubdate'), 'd/m/Y').'</p>';
        $availability = '<i class="fa fa-square lightblue" alt="Précommande" title="Précommande"></i>&nbsp;';
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

    $cart_content[] = '
        <tr id="cart_tr_'.$stock->get('id').'">
            <td class="center">'.$stock->get('id').'</td>
            <td class="center">'.$cover.'</td>
            <td>
                <a href="/'.$article->get('url').'">'.$article->get('title').'</a>'.$article_type.'<br>
                de '.authors($article->get('authors')).'<br>
                coll. '.$article->get('collection')->get('name').' '.numero($article->get('number')).'<br>
                '.$purchased.$preorder.'
                '.($stock->has('condition') ? 'État : '.$stock->get('condition').'<br>' : null).'
                '.$editable_price_form.'
            </td>
            '.($_SITE["site_shipping_fee"] == "fr" ? '<td class="right">'.$stock->get('weight').'g</td>' : null).'
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

if ($_V->isAdmin()) {
    $content .= '
        <div class="admin">
            <p>Panier n&deg; '.$cart->get('id').'</p>
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
if ($_SITE["site_shipping_fee"] == "fr") {
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
    if (!auth()) {
        $content .= '
            <p class="warning">
                Attention : vous n\'&ecirc;tes pas connect&eacute;. Si vous quittez le site, votre
                panier ne sera pas sauvegard&eacute;.
                <a href="'.$loginUrl.'">Connectez-vous</a> 
                pour sauvegarder votre panier.
            </p><br />';
    }

    // Deja une commande en cours ?
    if (auth()) {
        $order = $om->get(
            [
                'order_type' => 'web',
                'user_id' => $_V->get('user_id'),
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

                <p>Vous avez d&eacute;j&agrave; une commande en attente de paiement. Les livres de votre panier seront ajout&eacute;s aux livres de la commande ci-dessous et les frais de port recalcul&eacute;s en cons&eacute;quence. Si vous ne souhaitez plus commander les livres de la commande n&deg; '.$o["order_id"].', <a href="/contact/">contactez-nous</a> pour faire annuler la commande.</p>

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
                if (media_exists('stock', $s["stock_id"])) {
                    $s["couv"] = '<a href="'.media_url('stock', $s["stock_id"]).'" rel="lightbox"><img src="'.media_url('stock', $s["stock_id"], 'h60').'" alt="'.$s["article_title"].'" height="60" /></a>';
                } elseif (media_exists('article', $article->get('id'))) {
                    $s["couv"] = '<a href="'.media_url('article', $article->get('id')).'" rel="lightbox"><img src="'.media_url('article', $article->get('id'), "h60").'" alt="'.$article->get('title').'" /></a>';
                }

                $content .= '
                    <tr>
                        <td>'.$s["stock_id"].'</td>
                        <td>'.$s["couv"].'</td>
                        <td>
                            <a href="'.$urlgenerator->generate('article_show', ['slug' => $article->get('url')]).'">
                                '.$article->get('title').'
                            </a><br />
                            de '.authors($article->get('authors')).'<br />
                            coll. '.$article->get('collection')->get('name').' '.numero($article->get('number')).'<br />
                ';
                if (!empty($s["stock_condition"])) {
                    $content .= '&Eacute;tat : '.$s["stock_condition"].'<br />';
                }
                $content .= '
                        </td>
                ';
                if ($_SITE["site_shipping_fee"] == "fr") {
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

    // Special offers
    $special_offer_amount = $site->getOpt('special_offer_amount');
    $special_offer_article = $site->getOpt('special_offer_article');
    $special_offer_collection = $site->getOpt('special_offer_collection');

    // Special offer: article for amount of articles in collection
    if ($special_offer_collection && $special_offer_amount
        && $special_offer_article) {
        $copies = $cart->getStock();

        // Count copies in offer's collection
        $copiesInCollection = array_reduce($copies, function ($total, $copy) use ($special_offer_collection) {
            $article = $copy->getArticle();
            if ($article->get('collection_id') === $special_offer_collection) {
                $total++;
            }
            return $total;
        }, 0);

        $price = null;
        $missing = $special_offer_amount - $copiesInCollection;
        $fa = $am->getById($special_offer_article);
        $sentence = 'Ajoutez encore '.$missing.' titre'.s($missing).' de la collection<br/>
                à votre panier pour en profiter&nbsp;!';
        $style = ' style="opacity: .5"';
        $offerCollection = $com->getById($special_offer_collection);

        if ($missing <= 0) {
            $style = null;
            $sentence = 'Si vous ne souhaitez pas bénéficier de l\'offre, vous pourrez
                    le préciser dans le champ Commentaires de la page suivante.';
            $price = 'Offert';
        }

        $cover = null;
        if ($fa->hasCover()) {
            $cover = $fa->getCoverTag(['size' => 'h60', 'rel' => 'lightbox', 'class' => 'cover']);
        }

        $content .= '
                <tr'.$style.'>
                    <td>Offre<br>spéciale</td>
                    <td>'.$cover.'</td>
                    <td>
                        <a href="/'.$fa->get('url').'">'.$fa->get('title').'</a><br />
                        de '.authors($fa->get('authors')).'<br />
                        coll. '.$fa->get('collection')->get('name').' '.numero($fa->get('number')).'<br />
                        <p>
                            <strong>
                                Offert pour '.$special_offer_amount.' titres de la
                                collection '.$offerCollection->get('name').' achetés&nbsp;!
                                <small>(hors numérique)</small><br>
                                <small>'.$sentence.'</small>
                            </strong>
                        </p>
                    </td>
                    <td class="right">
                        '.$price.'
                    </td>
                    <td class="center">
                    </td>
                </tr>
            ';
    }

    // Special offer: article for revenue amount
    elseif ($special_offer_amount && $special_offer_article) {
        $fa = $am->getById($special_offer_article);

        $missing = $special_offer_amount - $physical_total_price;
        $sentence = 'Ajoutez encore '.price($missing, 'EUR').' à votre panier pour en profiter !';
        $style = ' style="opacity: .5"';
        $price = null;
        if ($physical_total_price >= $special_offer_amount) {
            $style = null;
            $sentence = null;
            $price = 'Offert';
        }

        $content .= '
            <tr'.$style.'>
                <td>Offre<br>spéciale</td>
                <td>'.$fa->getCoverTag(['size' => 'h60', 'rel' => 'lightbox', 'class' => 'cover']).'</td>
                <td>
                    <a href="/'.$fa->get('url').'">'.$fa->get('title').'</a><br />
                    de '.authors($fa->get('authors')).'<br />
                    coll. '.$fa->get('collection')->get('name').' '.numero($fa->get('number')).'<br />
                    <p>
                        <strong>
                            Offert pour '.price($special_offer_amount, 'EUR').' d\'achat ! <small>(hors numérique et abonnement)</small><br>
                            '.$sentence.'
                        </strong>
                    </p>
                </td>
                <td class="right">
                    '.$price.'
                </td>
                <td class="center">
                </td>
            </tr>
        ';
    }

    $content .= '
            </tbody>
            <tfoot>
                <tr class="bold">
                    <td colspan="3" class="right">Total :</td>
    ';

    if ($_SITE["site_shipping_fee"] == "fr") {
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
        $content .= '<p class="warning">Certains des livres de votre panier (<i class="fa fa-square lightblue" alt="Précommande" title="Précommande"></i>) sont à paraître. Votre commande sera expédiée lorsque tous les articles qu\'elles contient seront parus.</p>';
    }

    // On order books
    if ($on_order) {
        $content .= '<p class="warning">Certains des livres de votre panier (<i class="fa fa-square lightblue" alt="Sur commande" title="Sur commande"></i>) ne sont pas disponibles en stock et doivent être commandés. L\'expédition de votre commande peut-être retardée de 72h.</p>';
    }

    $content .= '

        <form id="validate_cart" action="order_delivery" method="get">
            <fieldset>
    ';

    // If cart contains physical articles that needs to be shipped
    if ($cart->needsShipping()) {
        $com = new CountryManager();

        // Countries
        $destinations = null;
        $countries = $com->getAll();
        $destinations = array_map(function ($country) use ($_V) {
            $selected = null;
            if ($country->get('name') === $_V->get('country')) {
                $selected = " selected";
            }
            return '<option value="'.$country->get('id').'"'.$selected.'>'.$country->get('name').'</option>';
        }, $countries);
        $default_destination = $com->get(["country_name" => "France"]);

        if ($_V->isLogged() && $customer = $_V->getCustomer()) {
            $country_id = $customer->get('country_id');
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

        $content .= '
                <h3>Mode d\'exp&eacute;dition</h3>

                '.$plus30.'

                <input type="hidden" id="order_weight" value="'.$Poids.'">
                <input type="hidden" id="order_amount" value="'.$Total.'">
                <input type="hidden" id="articles_num" value="'.$Articles.'">

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 200px;">Pays de destination</th>
                            <th>Mode d\'exp&eacute;dition</th>
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
                            <td class="bold right">Montant &agrave; r&eacute;gler :</td>
                            <td id="total" class="right bold">'.currency($Total / 100).'</td>
                        </tr>
                    </tbody>
                </table>
        ';
    }

    // If cart contains downloadable and user not logged
    if ($downloadable && !$_V->isLogged()) {
        $content .= '<br />'
        . '<div class="center">'
        . '<p class="warning">Votre panier contient au moins un livre num&eacute;rique. Vous devez vous <a href="'.$loginUrl.'">identifier</a> pour continuer.</p>'
        . '<button type="button" disabled class="btn btn-default">Finaliser la commande</button>'
        . '</div>';

    // If cart contains crowdfunding rewards and user not logged
    } elseif (!empty($crowdfunding) && !$_V->isLogged()) {
        $content .= '<br>'
        . '<div class="center">'
        . '<p class="warning">Votre panier contient au moins une contrepartie de financement participatif.<br>Vous devez vous <a href="'.$loginUrl.'">identifier</a> pour continuer.</p>'
        . '<button type="button" disabled class="btn btn-default">Finaliser la commande</button>'
        . '</div>';
    } else {
        if (isset($o["order_id"])) {
            $content .= '<div class="center"><button type="submit" class="btn btn-primary" id="continue">Ajouter à la commande en cours</button></div>';
        } else {
            $content .= '<div class="center"><button type="submit" class="btn btn-primary" id="continue">Finaliser votre commande</button></div>';
        }
    }

    $content .= '
            </fieldset>
        </form>
    ';
} else {
    $content .= '</table><p class="center">Votre panier est vide !</p>';
}

return new Response($content);
