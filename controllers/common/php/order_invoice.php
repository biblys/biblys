<?php

use Framework\Exception\AuthException;

$om = new OrderManager();
$sm = new StockManager();

$colspan = 2;

if ($order = $om->get(array('order_url' => $_GET['url']))) {
    $_PAGE_TITLE = 'Facture n&deg; '.$order->get('id');

    // Get customer
    $customer_ref = null;
    if ($order->has('customer')) {
        $customer = $order->get('customer');

        // Check access right
        if ($customer->get('user_id') != $_V->get('id') && !$_V->isAdmin()) {
            throw new AuthException('Accès non autorisé.');
        }

        // Calculate customer reference
        $stock = $_SQL->query("SELECT COUNT(`order_id`) AS `orders`, SUM(`order_amount`) AS `revenue` FROM `orders` WHERE `customer_id` = '".$customer->get('id')."' AND `site_id` = ".$_SITE["site_id"]." AND `order_payment_date` IS NOT NULL AND `order_cancel_date` IS NULL GROUP BY `user_id`");
        if ($s = $stock->fetch(PDO::FETCH_ASSOC)) {
            $customer_ref = '<p>Ref. client '.$customer->get('id').'-'.$s["orders"].'-'.round($s["revenue"]/100).'</p>';
        }
    }

    // Condition column for bookshops
    $condition = null;
    if ($_SITE["site_shop"]) {
        $condition = '<th>&Eacute;tat</th>';
        $colspan = 3;
    }

    // Get order content
    $content = array();
    $total_tva = 0;
    $total_ht = 0;
    $total_weight = 0;
    $stocks = $sm->getAll(array('order_id' => $order->get('id')));
    foreach ($stocks as $stock) {
        $article = $stock->get('article');

        // TVA
        $total_ht += $stock->get('selling_price_ht');
        $total_tva += $stock->get('selling_price_tva');

        $total_weight += $stock->get('weight');

        // E-books & preorder after title
        if ($article->get('type_id') == 2) {
            $article['title'] .= ' (num&eacute;rique)';
        }
        if ($article->get('pubdate') > $order->get('created')) {
            $article["title"] .= ' (pr&eacute;commande)';
        }

        // Build content table
        $content[] = '
            <tr>
                <td class="center">'.$stock->get('id').'</td>
                <td>
                    <strong>'.$article->get('title').'</strong><br>
                    <em>de '.truncate($article->get('authors'), 100, '...', true, true).'</em><br>
                    coll. '.$article->get('collection')->get('name').' '.numero($article->get('number')).'
                </td>
                '.($_SITE['site_shop'] ? '<td class="center">'.$stock->get('condition').'</td>' : null).'
                <td class="right">
                    '.currency($stock->get('selling_price') / 100).'
                </td>
            </tr>
        ';
    }

    // TVA
    $tva_line = null;
    if ($total_tva) {
        $tva_line .= '
                    <tr>
                        <th colspan="'.$colspan.'" class="right">Total H.T. :</th>
                        <th class="right">'.currency($total_ht / 100).'</th>
                    </tr>
                    <tr>
                        <th colspan="'.$colspan.'" class="right">T.V.A. :</th>
                        <th class="right">'.currency($total_tva / 100).'</th>
                    </tr>
        ';
    }

    // No TVA legal notice
    $notva = null;
    if (!$_SITE["site_tva"]) {
        $notva = '<p class="center"><strong>TVA non applicable en application de l\'article 293 B du CGI.</strong></p><br>';
    }

    // Payment
    $payment = null;
    if ($order->has('order_payment_date')) {
        $payment = '<p class="center">R&egrave;glement effectu&eacute; le '._date($order->get('payment_date'), 'd/m/Y').' par '.ucwords($order->get('payment_mode')).'.</p><br>';
    }

    $_ECHO .= '

        <div class="pull-right">
            '.$customer_ref.'
            <h3>'.$order->get('title').' '.$order->get('firstname').' '.$order->get('lastname').'</h3>
            <p>
                '.$order->get('address1').'<br>
                '.($order->has('address2') ? $order->get('address2').'<br>' : null).'
                '.$order->get('postalcode').' '.$order->get('city').'<br>
                '.($order->has('country') ? $order->get('country')->get('name') : null).'
            </p>
            <p>'.$order->get('order_email').'</p>
        </div>

        <h3>'.$_SITE["site_title"].'<br />'.str_replace("|","<br />",$_SITE["site_address"]).'</h3>

        <h2>'.$_PAGE_TITLE.'</h2>

        <br>
        <table class="table invoice-table">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Articles</th>
                    '.$condition.'
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
            '.implode($content).'
            </tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="'.$colspan.'" class="right">Articles :</th>
                    <th class="right">'.count($stocks).'</th>
                </tr>
            ';
            if ($total_weight > 0) {
                $_ECHO .= '
                    <tr>
                        <th colspan="'.$colspan.'" class="right">Poids :</th>
                        <th class="right">'.round($total_weight / 1000, 2).'&nbsp;kg</th>
                    </tr>
                ';
            }
            $_ECHO .= '
                <tr>
                    <th colspan="'.$colspan.'" class="right">Frais de port ('.$order->get('shipping_mode').') :</th>
                    <th class="right">'.currency($order->get('shipping') / 100).'</th>
                </tr>
                    '.$tva_line.'
                <tr>
                    <th colspan="'.$colspan.'" class="right">Total T.T.C.&nbsp;:</th>
                    <th class="right">'.currency(($order->get('amount') + $order->get('shipping')) / 100).'</th>
                </tr>
            </tfoot>
        </table>

        '.$notva.$payment.'

        <p class="text-center">
            Confirmez la r&eacute;ception de votre commande ou signalez un incident : <br>
            <strong>'.$_SITE["site_domain"].'/confirmer/'.$order->get('order_id').'</strong>
        </p>

    ';

    $notice = $site->getOpt('invoice_notice');
    if ($notice) {
        $_ECHO .= '<p class="text-center">'.str_replace('\n', '<br/>', $notice).'</p>';
    }


} else {
    $_ECHO .= '<p class="error">Facture inexistante</p>';
}
