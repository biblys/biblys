<?php

use Biblys\Legacy\LegacyCodeHelper;

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Stock détaillé');

// REQUETE DES STOCK

$_QUERY = null;

// Filtrer par date
if (!isset($_GET['date'])) {
    $_GET['date'] = date('Y-m-d');
}
$_QUERY .= ' AND `stock_purchase_date` <= :date
    AND (`stock_selling_date` IS null OR `stock_selling_date` > :date)
    AND (`stock_return_date` IS null OR `stock_return_date` > :date)
    AND (`stock_lost_date` IS null OR `stock_lost_date` > :date)
';
$params['date'] = $_GET['date'].' 23:59:59';

$rayonId = $request->query->get('rayon_id');
if ($rayonId) {
    $_QUERY .= "AND `article_links` LIKE '%[rayon:".$rayonId."]%'";
}

// Filtrer par dépôt
if (isset($_GET['stock_depot'])) {
    if ($_GET['stock_depot'] == 1) {
        $_QUERY .= ' AND `stock_depot` = 0';
    } elseif ($_GET['stock_depot'] == 2) {
        $_QUERY .= ' AND `stock_depot` = 1';
    }
} else {
    $_GET['stock_depot'] = null;
}

$stock = $_SQL->prepare(
    'SELECT
        `article_title`, `article_authors`, `article_url`, `article_tva`,
        `stock_id`, `stock_purchase_price`, `stock_selling_price`, 
        `stock_condition`, `stock_purchase_date`
    FROM `stock` AS `s`
    JOIN `articles` AS `a` USING(`article_id`)
    WHERE `s`.`site_id` = :site_id'.$_QUERY.'
    GROUP BY `s`.`stock_id`
    ORDER BY `stock_purchase_date`'
);
$params['site_id'] = LegacyCodeHelper::getGlobalSite()["site_id"];
$stock->execute($params) or error($orders->errorInfo());

// Export to CSV
$export = array();
$header = array('Ref.', 'Titre', 'État', 'Prix d\'achat HT', 'Prix de vente TTC');

$tbody = null;
$stock_total = 0;
$purchase_total = 0;
$selling_total = 0;
while ($s = $stock->fetch(PDO::FETCH_ASSOC)) {
    if ($s['stock_condition'] != 'Neuf') {
        $s['stock_condition'] = 'Occasion';
    }
    // Prix HT
    if (LegacyCodeHelper::getGlobalSite()['site_tva']) {
        $s['tva_rate'] = tva_rate(
            $s['article_tva'], $s["stock_purchase_date"]
        ) / 100;
        $s['ti'] = $s['tva_rate'] * 1000;
        $s['stock_purchase_price'] 
            = $s['stock_purchase_price'] / (1 + $s['tva_rate']);
    }

    $tbody .= '
        <tr>
            <td>
                <a href="/pages/adm_stock?id='.$s['stock_id'].'">
                    '.$s['stock_id'].'
                </a>
            </td>
            <td title="'.$s['article_authors'].'">
                <a href="/a/'.$s['article_url'].'">'.$s['article_title'].'</a>
            </td>
            <td>'.$s['stock_condition'].'</td>
            <td class="right">'.price($s['stock_purchase_price'], 'EUR').'</td>
            <td class="right">'.price($s['stock_selling_price'], 'EUR').'</td>
        </tr>
    ';

    $stock_total++;
    $selling_total += $s["stock_selling_price"];
    $purchase_total += $s['stock_purchase_price'];

    $export[] = [
        $s['stock_id'], 
        $s['article_title'], 
        $s['stock_condition'], 
        price(round($s['stock_purchase_price'], 2)), 
        price($s['stock_selling_price'])
    ];
}
$stock->closeCursor();

$_ECHO .= '
    <h1><span class="fa fa-line-chart"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>

    <form class="fieldset">
        <fieldset>
            <legend>Options</legend>

            <p>
                <label for="date">Au :</label>
                <input type="date" name="date" id="date" placeholder="AAAA-MM-JJ" 
                    value="'.$_GET["date"].'">
            </p>

            <p>
                <label for="stock_depot">Dépots :</label>
                <select name="stock_depot" id="stock_depot" required>
                    <option value="0">Tous</option>
                    <option value="1"'.(
                        $_GET['stock_depot'] == 1 ? ' selected' : null
                    ).'>Sans les dépots</option>
                    <option value="2"'.(
                        $_GET['stock_depot'] == 2 ? ' selected' : null
                    ).'>Dépots uniquement</option>
                    </select>
            </p>

            <p class="center">
                <button type="submit">Afficher le stock</button>
            </p>

        </fieldset>
    </form>

    <h3>'.$stock_total.' exemplaires en stock au '._date(
    $_GET['date'], 
    'j f Y'
).'</h3>

    <form action="/pages/export_to_csv" method="post">
        <fieldset class="right">
            <input type="hidden" name="filename" 
                value="stock_'. LegacyCodeHelper::getGlobalSite()['site_name'].'_au_'.$_GET["date"].'">
            <input type="hidden" name="header" 
                value="'.htmlentities(json_encode($header)).'">
            <input type="hidden" name="data" 
                value="'.htmlentities(json_encode($export)).'">
            <button type="submit">Télécharger au format CSV</button>
        </fieldset>
    </form>
    <br>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Ref.</th>
                <th>Titre</th>
                <th>État</th>
                <th title="Prix d\'achat HT">PdA HT</th>
                <th title="Prix de vente TTC">PdV TTC</th>
            </tr>
        </thead>
        <tbody>
            '.$tbody.'
        </tbody>
        <tfoot>
            <tr>
                <th class="right" colspan="3">Total&nbsp;:</th>
                <th class="right">'.price($purchase_total, 'EUR').'</th>
                <th class="right">'.price($selling_total, 'EUR').'</th>
            </tr>
        </tfoot>
    </table>
';
