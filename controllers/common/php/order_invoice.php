<?php

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @throws InvalidDateFormatException
 * @throws PropelException
 */
return function (
    Request $request,
    CurrentUser $currentUser,
    CurrentSite $currentSite
): Response
{
    $om = new OrderManager();
    $sm = new StockManager();

    $colspan = 2;

    $content = "";

    /** @var Order $order */
    $orderUrl = LegacyCodeHelper::getRouteParam("url");
    if ($order = $om->get(["order_url" => $orderUrl])) {
        $pageTitle = "Facture n° {$order->get('id')}";
        $request->attributes->set("page_title", $pageTitle);

        if (!_isAnonymousOrder($order) && !_orderBelongsToVisitor($order, $currentUser) &&
            !$currentUser->isAdmin()) {
            throw new AccessDeniedHttpException();
        }

        // Condition column for bookshops
        $condition = null;
        if ($currentSite->getSite()->getShop()) {
            $condition = '<th>État</th>';
            $colspan = 3;
        }

        // Get order content
        $orderContent = array();
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
                $article['title'] .= ' (numérique)';
            }
            if ($article->get('pubdate') > $order->get('created')) {
                $article["title"] .= ' (précommande)';
            }

            // Build content table
            $orderContent[] = '
            <tr>
                <td class="center">' . $stock->get('id') . '</td>
                <td>
                    <strong>' . $article->get('title') . '</strong><br>
                    <em>de ' . truncate($article->get('authors'), 100, '...', true, true) . '</em><br>
                    coll. ' . $article->get('collection')->get('name') . ' ' . numero($article->get('number')) . '
                </td>
                ' . ($currentSite->getSite()->getShop() ? '<td class="center">' . $stock->get('condition') . '</td>' : null) . '
                <td class="right">
                    ' . currency($stock->get('selling_price') / 100) . '
                </td>
            </tr>
        ';
        }

        // TVA
        $tva_line = null;
        if ($total_tva) {
            $tva_line .= '
                    <tr>
                        <th colspan="' . $colspan . '" class="right">Total H.T. :</th>
                        <th class="right">' . currency($total_ht / 100) . '</th>
                    </tr>
                    <tr>
                        <th colspan="' . $colspan . '" class="right">T.V.A. :</th>
                        <th class="right">' . currency($total_tva / 100) . '</th>
                    </tr>
        ';
        }

        // No TVA legal notice
        $notva = null;
        if (!$currentSite->getSite()->getTva()) {
            $notva = '<p class="center"><strong>TVA non applicable en application de l\'article 293 B du CGI.</strong></p><br>';
        }

        // Payment
        $payment = null;
        if ($order->has('order_payment_date')) {
            $payment = '<p class="center">Règlement effectué le ' . _date($order->get('payment_date'), 'd/m/Y') . ' par ' . ucwords($order->get('payment_mode')) . '.</p><br>';
        }

        $content .= '

        <div class="pull-right">
            <h3>' . $order->get('title') . ' ' . $order->get('firstname') . ' ' . $order->get('lastname') . '</h3>
            <p>
                ' . $order->get('address1') . '<br>
                ' . ($order->has('address2') ? $order->get('address2') . '<br>' : null) . '
                ' . $order->get('postalcode') . ' ' . $order->get('city') . '<br>
                ' . ($order->getCountryName()) . '
            </p>
            <p>' . $order->get('order_email') . '</p>
        </div>

        <h3>' . $currentSite->getTitle() . '<br />' . str_replace("|", "<br />", $currentSite->getSite()->getAddress()) . '</h3>

        <h2>' . $pageTitle . '</h2>

        <br>
        <table class="table invoice-table">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Articles</th>
                    ' . $condition . '
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
            ' . implode($orderContent) . '
            </tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="' . $colspan . '" class="right">Articles :</th>
                    <th class="right">' . count($stocks) . '</th>
                </tr>
            ';
        if ($total_weight > 0) {
            $content .= '
                    <tr>
                        <th colspan="' . $colspan . '" class="right">Poids :</th>
                        <th class="right">' . round($total_weight / 1000, 2) . '&nbsp;kg</th>
                    </tr>
                ';
        }
        $content .= '
                <tr>
                    <th colspan="' . $colspan . '" class="right">Frais de port (' . $order->get('shipping_mode') . ') :</th>
                    <th class="right">' . currency($order->get('shipping') / 100) . '</th>
                </tr>
                    ' . $tva_line . '
                <tr>
                    <th colspan="' . $colspan . '" class="right">Total T.T.C.&nbsp;:</th>
                    <th class="right">' . currency(($order->get('amount') + $order->get('shipping')) / 100) . '</th>
                </tr>
            </tfoot>
        </table>

        ' . $notva . $payment;

        $notice = $currentSite->getOption("invoice_notice");
        if ($notice) {
            $content .= '<p class="text-center">' . str_replace('\n', '<br/>', $notice) . '</p>';
        }


    } else {
        $content .= '<p class="error">Facture inexistante</p>';
    }

    return new Response($content);
};

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
