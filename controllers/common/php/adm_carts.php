<?php

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\Response;

$cm = new CartManager();

// Flash messages
$flashs = [];
foreach ($session->getFlashBag()->get('success', array()) as $message) {
    $flashs[] = "<p class='alert alert-success'>$message</p>";
}
foreach ($session->getFlashBag()->get('error', array()) as $message)  {
    $flashs[] = "<p class='alert alert-danger'>$message</p>";
}

// Empty a single cart
$empty = $request->query->get('empty');
if ($empty) {
    $cart = $cm->getById($empty);
    if ($cart) {
        $cm->vacuum($cart);
        $cm->delete($cart);
        $session->getFlashbag()->add('success', "Le panier n° $empty a été vidé.");
    } else {
        $session->getFlashbag()->add(
            "error",
            "Impossible de vider le panier n° $empty : il n'existe plus."
        );
    }
    redirect('/pages/adm_carts');
}

$_PAGE_TITLE = 'Paniers';

$alert = null;

// Date limite
if($_SITE["site_id"] == 8) $datelimite = date('Y-m-d h:i:s',(strtotime("-2 days")));
else $datelimite = date('Y-m-d h:i:s',(strtotime("-1 days")));

$content = '
    <h1><span class="fa fa-shopping-basket"></span> '.$_PAGE_TITLE. '</h1>

    <p class="buttonset">
        <a href="?refresh=1" class="btn btn-info"><i class="fa fa-refresh"></i> Actualiser les paniers</a>
        <a
            href="/pages/adm_carts?go=1"
            class="btn btn-warning"
            data-confirm="Voulez-vous vraiment vider les paniers de clients inconnus antérieurs au ' . _date($datelimite, 'd/m/Y H:i') . ' ?"
        >
            <i class="fa fa-trash-o"></i> Vider les paniers obsolètes
        </a>
    </p><br>

      '.$alert.join($flashs).'

    <table class="admin-table">
        <thead>
            <tr>
                <th>Ref.</th>
                <th>Client</th>
                <th>Livres</th>
                <th>Montant</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
';

$refresh = $request->query->get('refresh');

$config = new Config();
$usersTableName = $config->get("users_table_name");

$emptied = 0;
$carts = $_SQL->prepare("
    SELECT
        `cart_id`, `carts`.`site_id`, `carts`.`user_id`, `cart_ip`, `cart_date`, `cart_count`,
        `cart_amount`, `Email`, COUNT(`stock_id`) AS `num`, SUM(`stock_selling_price`) AS `total`,
        MAX(`stock_cart_date`) AS `stock_cart_date`
    FROM `carts`
    LEFT JOIN `$usersTableName` ON `carts`.`user_id` = `$usersTableName`.`id`
    LEFT JOIN `stock` USING(`cart_id`)
    WHERE `carts`.`site_id` = :site_id AND `cart_type` = 'web' AND `cart_deleted` IS NULL
    GROUP BY `cart_id`
    ORDER BY `stock_cart_date` DESC
");
$carts->execute(['site_id' => $site->get('id')]);
while($c = $carts->fetch(PDO::FETCH_ASSOC)) {
    if(isset($c["Email"])) $c["user"] = $c["Email"];
    else $c["user"] = $c["cart_ip"];
    $c["style"] = null;

    if ($refresh) {
        $cart = $cm->get(array('cart_id' => $c['cart_id'], 'site_id' => $c['site_id']));
        $cm->updateFromStock($cart);
    }

    if ($c["stock_cart_date"] < $datelimite && empty($c["Email"]) || empty($c["num"]))
    {
        $c["style"] = ' style="text-decoration:line-through;"';

        if (isset($_GET["go"]))
        {
            if ($cart = $cm->get(array('cart_id' => $c['cart_id'], 'site_id' => $c['site_id'])))
            {
                $cm->vacuum($cart);
                $cm->delete($cart);
                $emptied++;
            }
            else trigger_error('Impossible de vider le panier '.$c['cart_id']);
        }
    }

    $content .= '
        <tr>
            <td><a href="/pages/cart?cart_id='.$c["cart_id"].'">'.$c["cart_id"].'</a></td>
            <td'.$c["style"].'>'.$c["user"].'</td>
            <td class="right">'.$c["cart_count"]. '</td>
            <td class="right">' . price($c["cart_amount"], 'EUR') . '</td>
            <td class="center">' . _date($c["stock_cart_date"], 'd/m/Y H:i:s') . '</td>
            <td class="center">
                <a
                    href="/pages/adm_carts?empty=' . $c["cart_id"] . '"
                    class="btn btn-danger btn-sm"
                    data-confirm="Voulez-vous vraiment vider le panier n° ' . $c["cart_id"] . ' ?"
                >
                    <i class="fa fa-trash-o"> Vider</i>
                </a>
            </td>
        <tr>
    ';
}

$content .= '
        </tbody>
    </table>
';

if ($refresh) {
    $session->getFlashbag()->add('success', "Les paniers ont été actualisés.");
    redirect('/pages/adm_carts');
}

if (isset($_GET["go"])) {
    $session->getFlashbag()->add('success', "$emptied paniers ont été vidés.");
    redirect('/pages/adm_carts');
}

return new Response($content);
