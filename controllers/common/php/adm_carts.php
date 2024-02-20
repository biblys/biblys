<?php

use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @throws InvalidDateFormatException
 */
return function (Request $request, Session $session, CurrentSite $currentSite): Response|RedirectResponse
{
    $cm = new CartManager();

    // Empty a single cart
    $empty = $request->query->get('empty');
    if ($empty) {
        /** @var Cart $cart */
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
        return new RedirectResponse("/pages/adm_carts");
    }

    $request->attributes->set("page_title", "Paniers");

    // Date limite
    if ($currentSite->getId()) $datelimite = date('Y-m-d h:i:s', (strtotime("-2 days")));
    else $datelimite = date('Y-m-d h:i:s', (strtotime("-1 days")));

    $content = '
    <h1><span class="fa fa-shopping-basket"></span> Paniers</h1>

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

    $emptied = 0;
    $carts = EntityManager::prepareAndExecute("
        SELECT
            `cart_id`, `carts`.`site_id`, `carts`.`axys_account_id`, `cart_uid`, `cart_date`, `cart_count`, 
            `cart_amount`, `axys_account_email`, COUNT(`stock_id`) AS `num`, SUM(`stock_selling_price`) AS `total`,
            MAX(`stock_cart_date`) AS `stock_cart_date`
        FROM `carts`
        LEFT JOIN `axys_accounts` ON `carts`.`axys_account_id` = `axys_accounts`.`axys_account_id`
        LEFT JOIN `stock` USING(`cart_id`)
        WHERE `carts`.`site_id` = :site_id AND `cart_type` = 'web'
        GROUP BY `cart_id`
        ORDER BY `stock_cart_date` DESC",
        ['site_id' => $currentSite->getId()]
    );
    while ($c = $carts->fetch(PDO::FETCH_ASSOC)) {
        if (isset($c["axys_account_email"])) $c["user"] = $c["axys_account_email"];
        else $c["user"] = "Anonyme (".substr($c["cart_uid"], 0, 7)."…)";
        $c["style"] = null;

        if ($refresh) {
            $cart = $cm->get(array('cart_id' => $c['cart_id'], 'site_id' => $c['site_id']));
            $cm->updateFromStock($cart);
        }

        if ($c["stock_cart_date"] < $datelimite && empty($c["axys_account_email"]) || empty($c["num"])) {
            $c["style"] = ' style="text-decoration:line-through;"';

            if (isset($_GET["go"])) {
                if ($cart = $cm->get(array('cart_id' => $c['cart_id'], 'site_id' => $c['site_id']))) {
                    $cm->vacuum($cart);
                    $cm->delete($cart);
                    $emptied++;
                } else trigger_error('Impossible de vider le panier ' . $c['cart_id']);
            }
        }

        $content .= '
        <tr>
            <td><a href="/pages/cart?cart_id=' . $c["cart_id"] . '">' . $c["cart_id"] . '</a></td>
            <td' . $c["style"] . '>' . $c["user"] . '</td>
            <td class="right">' . $c["cart_count"] . '</td>
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
        return new RedirectResponse("/pages/adm_carts");
    }

    if (isset($_GET["go"])) {
        $session->getFlashbag()->add('success', "$emptied paniers ont été vidés.");
        return new RedirectResponse("/pages/adm_carts");
    }

    return new Response($content);
};
