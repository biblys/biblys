<?php

use Symfony\Component\HttpFoundation\Request;

$_JS_CALLS[] =  '//cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.pack.js';
$_CSS_CALLS[] = 'screen://cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.css';

$cm = new CartManager();
$um = new UserManager();

/* SELECTION DU PANIER COURANT */

// Si aucun panier n'est specifié, on recherche un panier magasin pour ce vendeur avec 0 livre, sinon on en créera un
/** @var Visitor $_V */
$where = array('cart_type' => 'shop', 'cart_seller_id' => $_V->get('user_id'), 'cart_count' => 0);

// Si un panier en particulier est demandé
if (isset($_GET['cart_id']))
{
    if ($getcart = $cm->get(array('cart_id' => $_GET['cart_id'])))
    {
        /** @var Cart $cart */
        $cart = $getcart;
    }
    else // s'il existe pas
    {
        trigger_error('Panier '.$_GET['cart_id'].' introuvable');
    }
}

// S'il existe déjà un panier en cours avec 0 livres pour ce vendeur
elseif ($getcart = $cm->get($where))
{
    $cart = $getcart;
    redirect('/pages/adm_checkout', array('cart_id' => $cart->get('id')));
}

// Aucun panier en cours, on en cree un nouveau
else
{
    $cart = $cm->create();
    $cart->set('cart_type', 'shop');
    /** @var Visitor $_LOG */
    $cart->set('cart_seller_id', $_LOG['user_id']);
    $cart = $cm->update($cart);
    redirect('/pages/adm_checkout', array('cart_id' => $cart->get('id')));
}

/* ACTIONS */

// Enregistrer la vente
if (isset($_POST['validate']))
{

    try
    {
        /** @var PDO $_SQL */
        $_SQL->beginTransaction();

        // Create new order
        $_O = new OrderManager();
        /** @var Order $order */
        $order = $_O->create();

        // Update order info
        if (!empty($_POST['seller_id'])) $order->set('seller_id', $_POST['seller_id']);
        if (!empty($_POST['customer_id'])) $order->set('customer_id', $_POST['customer_id']);

        $order->set('order_type', 'shop');
        $order->set('order_payment_cash', $_POST['cart_cash']);
        $order->set('order_payment_cheque', $_POST['cart_cheque']);
        $order->set('order_payment_card', $_POST['cart_card']);
        $order->set('order_amount_tobepaid', $_POST['cart_topay']);
        $order->set('order_payment_left', $_POST['cart_togive']);
        $order->set('order_payment_date', date('Y-m-d H:i:s'));

        // Hydrate order from cart
        $_O->hydrateFromCart($order, $cart);

        // Update order
        $order = $_O->update($order);

        // Reset cart
        $cm->vacuum($cart);
        $cm->delete($cart);

        $_SQL->commit();
    }
    catch (Exception $e)
    {
        $_SQL->rollBack();
        trigger_error($e->getMessage());
    }

    $r = array('created_order' => $order->get('order_id'));

    /** @var Request $request */
    if ($request->isXmlHttpRequest()) {
        die(json_encode($r));
    }
//        else redirect('/pages/adm_checkout', $r);
}

// Changer le nom du panier
if (isset($_POST['set_title']))
{
    $cart->set('cart_title', $_POST['set_title']);
    $cart = $cm->update($cart);
    if ($cart->get('cart_title') == $_POST['set_title']) $p['success'] = "Le nom du panier a bien été modifié.";
    else $p['error'] = "Le nom du panier n'a pas pu être modifié.";
    if ($request->isXmlHttpRequest()) {
        die(json_encode($p));
    }
}

// Changer le client du panier
elseif (isset($_POST['set_customer']))
{

    try
    {
        $cart->set('customer_id', $_POST['set_customer']);
        $cm->update($cart);
    }
    catch (Exception $e)
    {
        $error = $e;
    }

    if (isset($error)) $p['error'] = $error;
    elseif ($cart->get('customer_id') == $_POST['set_customer']) $p['success'] = "Le client du panier a bien été modifié.";
    //else $p['error'] = "Le client du panier n'a pas pu être modifié. (".$cart->get('customer_id')." / ".$_POST['set_customer'].") ";

    if ($request->isXmlHttpRequest()) {
        die(json_encode($p));
    }
    else redirect('/pages/adm_checkout',$p);
}

$copyToRemoveId = (int) $request->query->get('remove_stock', false);

// Ajouter au panier
if (isset($_GET['add']) && isset($_GET['id']))
{
    // Ajouter un exemplaire
    if ($_GET['add'] == 'stock')
    {
        try
        {
            $cm->addStock($cart, $_GET['id']);
            $p['success'] = 'L\'exemplaire n&deg; '.$_GET['id'].' a été ajouté au panier.';
            $cm->updateFromStock($cart);
        }
        catch (Exception $e)
        {
            trigger_error($e->getMessage());
        }

        if ($request->isXmlHttpRequest()) {
            $stocks = $cm->getStock($cart);
            foreach ($stocks as $stock) {
                if ($stock->get('id') == $_GET['id']) {
                    $p['line'] = $cart->getLine($stock);
                }
            }
            die(json_encode($p));
        } else {
            redirect('/pages/adm_checkout', $p);
        }
    }
}

// Retirer du panier
elseif ($copyToRemoveId)
{
    $sm = new StockManager();
    $copy_to_remove = $sm->getById($copyToRemoveId);
    if ($copy_to_remove) {
        if ($cm->removeStock($cart, $copy_to_remove)) {
            $p['success'] = 'L\'exemplaire n&deg; '.$copy_to_remove->get('id').' a été retiré du panier et remis en stock.';
            $cm->updateFromStock($cart);
        }
    }
    else $p['error'] = 'L\'exemplaire n&deg; '.$_GET['id'].' n\'a pas pu être supprimé du panier.';
    if ($request->isXmlHttpRequest()) {
        die(json_encode($p));
    } else {
        redirect('/pages/adm_checkout',$p);
    }
}

// Vider le panier
elseif (isset($_GET['vacuum_cart']))
{

    if ($cm->vacuum($cart))
    {
        $params['success'] = 'Le panier n&deg; '.$cart->get('cart_id').' a été vidé !';
    }
    else $params['error'] = 'Le panier n&deg; '.$cart->get('cart_id').' n\'a pas pu être vidé.';

//        $params['cart_id'] = $cart->get('cart_id');
    redirect('/pages/adm_checkout',$params);
}


/* AUTRES */

// Client du panier en cours
$customer_field = '
    <input type="text" name="customer" id="customer" placeholder="Rechercher un client..." class="event verylong">
    <input type="hidden" name="customer_id" id="customer_id" required>
    <span id="newsletter2" class="hidden">
        <input type="checkbox" name="customer_mailing" id="customer_mailing" value="1" disabled>
        <label for="customer_mailing" class="after">Abonné à la newsletter</label>
    </span>
';

if ($cart->has('customer_id'))
{
    $_CUSTOMERS = new CustomerManager();

    if ($customer = $_CUSTOMERS->get(array('customer_id' => $cart->get('customer_id'))))
    {

        if ($customer->get('customer_newsletter') == 1) $newsletter_checked = ' checked';
        else $newsletter_checked = NULL;

        $customer_field = '
            <input type="text" name="customer" id="customer" placeholder="Rechercher un client..." value="'.$customer->get('customer_last_name').', '.$customer->get('customer_first_name').' ('.$customer->get('customer_email').')'.'" readonly class="pointer event verylong">
            <input type="hidden" name="customer_id" id="customer_id" value="'.$customer->get('customer_id').'" required>
            <span id="newsletter">
                <input type="checkbox" name="customer_mailing" id="customer_mailing" value="1" disabled="disabled" '.$newsletter_checked.'>
                <label for="customer_mailing" class="after">Abonné à la newsletter</label>
            </span>
        ';
    }
}

// Current cart's seller
$seller_field = null;
if ($cart->has('seller_id'))
{
    if ($seller = $um->get(array('user_id' => $cart->get('seller_id'))))
    {
        $seller_field = '
            <input type="text" name="seller" id="seller" value="'.$seller->getUserName().'" class="long" required readonly>
            <input type="hidden" name="seller_id" id="seller_id" value="'.$seller->get('user_id').'" required>
        ';
    }
} else trigger_error('No seller id.');

// Livres du panier en cours
$cart_content = NULL;
$cart_count = 0;
$cart_total = 0;
$stock = $cm->getStock($cart);
foreach ($stock as $s)
{
    $cart_content .= $cart->getLine($s);
    $cart_count++;
    $cart_total += $s->get('selling_price');
}

$cm->updateFromStock($cart);

// Paniers sauvegardés & VPC
$s_carts = $cm->getAll(array());


$shop_carts = NULL; $web_carts = NULL;
foreach ($s_carts as $s)
{
    $s_cart = '
        <tr>
            <td><a href="/pages/adm_checkout?cart_id='.$s->get('cart_id').'">'.$s->get('cart_title').'</a></td>
            <td>'.($s->hasSeller() ? $s->getSeller()->getUserName() : null).'</td>
            <td>'.($s->has('customer') ? $s->get('customer')->get('first_name').' '.$s->get('customer')->get('last_name') : null).'</td>
            <td class="right">'.$s->get('cart_count').'</td>
            <td class="right">'.currency($s->get('cart_amount') / 100).'</td>
            <td class="center">
                <a href="/pages/adm_checkout?cart_id='.$s->get('cart_id').'&vacuum_cart=1" data-confirm="Voulez-vous vraiment VIDER ce panier et remettre '.$s->get('cart_count').' exemplaire'.s($s->get('cart_count')).' en vente ?" title="Vider le panier et remettre '.$s->get('cart_count').' exemplaire'.s($s->get('cart_count')).' en vente." class="btn btn-danger btn-xs">
                    <i class="fa fa-trash-o"></i>
                </a>
            </td>
        </tr>
    ';
    if ($s->get('cart_count') > 0)
    {
        if ($s->get('cart_type') == 'shop') $shop_carts .= $s_cart;
        elseif ($s->has('client_id')) $web_carts .= $s_cart;
    }
}

if (isset($_GET['success'])) $alert = '<p class="success">'.$_GET['success'].'</p>';
elseif (isset($_GET['error'])) $alert = '<p class="error">'.$_GET['error'].'</p>';
elseif (isset($_GET['order_created'])) $alert = '<p class="success">La vente a été enregistrée sous le numéro '.$_GET['order_created'].'.</p>';
else $alert = NULL;

$_PAGE_TITLE = 'Caisse';
$_ECHO = '';

/** @var Site $_SITE */
if ($_SITE['site_tva'] === 'fr') {
    $_ECHO .= '
        <p class="alert alert-warning">
            <span class="fa fa-warning"></span>
            La caisse Biblys n\'est pas un logiciel de caisse certifié.<br/>
            En tant que professionnel asujetti à la TVA, vous risquez en
            l\'utilisant une amende de 7500 €.
        </p>';
}

$_ECHO .= '
    <h1><span class="fa fa-money"></span> '.$_PAGE_TITLE.'</h1>

    '.$alert.'

    <form id="createCustomer" hidden>
        <fieldset>
            <p>
                <label for="customer_last_name">Nom :</label>
                <input type="text" id="customer_last_name" name="customer_last_name" required>
            </p>
            <p>
                <label for="customer_first_name">Prénom :</label>
                <input type="text" id="customer_first_name" name="customer_first_name">
            </p>
            <p>
                <label for="customer_email">Adresse e-mail :</label>
                <input type="email" id="customer_email" name="customer_email" class="long">
            </p>
            <p>
                <label for="customer_phone">Téléphone :</label>
                <input type="number" id="customer_phone" name="customer_phone">
            </p>
            <p class="center">
                <input type="checkbox" name="customer_newsletter" id="customer_newsletter" value="1" checked>
                <label for="customer_newsletter" class="after">Abonner à la newsletter</label>
            </p>
            <div class="right"><input type="submit" value="Valider" /></div>
        </fieldset>
    </form>

    <h3>
        <span id="cart_title" class="event pointer" contenteditable>'.$cart->get('cart_title').'</span>
    </h3>

    <form>
        <fieldset>

            <input type="hidden" id="cart_id" name="cart_id" value='.$cart->get('cart_id').'>

            <p>
                <label for="seller">Libraire :</label>
                '.$seller_field.'
            <p>

            <p>
                <label for="customer">Client :</label>
                '.$customer_field.'
            </p>

        </fieldset>
    </form>

    <h3>Encaissement</h3>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Montant</th>
                <th>Espèces</th>
                <th>Chèque</th>
                <th>Carte</th>
                <th>À régler</th>
                <th>À rendre</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="right" id="cart_total" data-value="'.$cart_total.'">'.currency($cart_total / 100).'</td>
                <td class="center"><input id="cart_cash" class="cart_payment" type="number" step="0.01" min=0 max=999 style="text-align: right;"></td>
                <td class="center"><input id="cart_cheque" class="cart_payment" type="number" step="0.01" min=0 max=999 style="text-align: right;"></td>
                <td class="center"><input id="cart_card" class="cart_payment" type="number" step="0.01" min=0 max=999 style="text-align: right;"></td>
                <td id="cart_topay" data-value='.$cart_total.' class="right">'.currency($cart_total / 100).'</td>
                <td id="cart_togive" data-value=0 class="right">'.currency(0 / 100).'</td>
            </tr>
        </tbody>
    </table>

    <br>
    <form id="checkout" class="event">
        <fieldset class="center">
            <button class="btn btn-primary" type="submit">Enregistrer la vente</button>
        </fieldset>
    </form>

    <h3>Panier en cours (<span id="cart_count">'.$cart_count.'</span> article<span id="cart_count_s">'.s($cart_count).'</span>)</h3>

    <form id="checkout_add" class="event">
        <fieldset>

            <p>
                <label for="checkout_add_input">Ajouter un article :</label>
                <input type="text" name="checkout_add_input" id="checkout_add_input" class="verylong event" autocomplete="off" autofocus>
            </p>

        </fieldset>
    </form>

    <table id="checkout_add_results" class="admin-table">
    </table>

    <table id="checkout_cart" class="admin-table">
        <thead>
            <tr>
                <th>Ref.</th>
                <th></th>
                <th>Article</th>
                <th>Prix</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$cart_content.'
        </tbody>
    </table>

    <br>
    <h3>Autres paniers</h3>
    <p><a href="/pages/adm_checkout?new_cart" class="btn btn-primary">Nouveau panier</a></p>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Libraire</th>
                <th>Client</th>
                <th>Articles</th>
                <th>Montant</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$shop_carts.'
        </tbody>
    </table>

    <!-- br>
    <h3>Paniers VPC</h3>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Client</th>
                <th>Articles</th>
                <th>Montant</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$web_carts.'
        </tbody>
    </table //-->

';
