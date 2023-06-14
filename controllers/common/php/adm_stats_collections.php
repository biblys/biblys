<?php

use Biblys\Legacy\LegacyCodeHelper;

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Chiffre d\'affaires par collections');

    $list = NULL;
    
    if(isset($_GET["year"])) $req_y = " AND `stock_selling_date` LIKE :year";
    else $req_y = " AND `stock_selling_date` IS NOT NULL ";
    
    $query = $_SQL->prepare("
        SELECT `article_collection`, COUNT(`stock_id`) AS `Ventes`, SUM(`stock_selling_price`) AS `CA`
        FROM `stock`
        JOIN `articles` USING(`article_id`)
		JOIN `orders` USING(`order_id`)
        WHERE `stock`.`site_id` = :site_id AND `stock_selling_price` != 0 AND `order_payment_date` IS NOT NULL ".$req_y."
        GROUP BY `collection_id`
        HAVING COUNT(`stock_id`) >= 3
        ORDER BY `CA` DESC, `Ventes`
    ");
	$query->bindValue(':site_id', LegacyCodeHelper::getLegacyCurrentSite()["site_id"], PDO::PARAM_INT);
	if(isset($_GET["year"])) $query->bindValue(':year', $_GET["year"].'%', PDO::PARAM_INT);
	$query->execute() or error(pdo_error());
    
    $i = 0;
    while($x = $query->fetch()) {
        $i++;
        //$x["CA"] = $x["CA"] / 1.055;
        $list .= '
            <tr>
                <td class="right">'.$i.'.</td>
                <td>'.$x["article_collection"].'</td>
                <td class="right">'.$x["Ventes"].'</td>
                <td sorttable_customkey="'.$x["CA"].'" class="right">'.price($x["CA"],'EUR').'</td>
            </tr>
        ';
		$TotalVentes += $x["Ventes"];
		$TotalCA += $x["CA"];
    }
    
    for($y = date('Y'); $y >= 2010; $y--) {
        if($y == $_GET["year"]) $sel = 'selected';
        else $sel = NULL;
        $years .= '<option value="?year='.$y.'" '.$sel.'>'.$y.'</option>';
    }
    
    $_ECHO .= '
        
        <h2><img src="/common/icons/adm_stats_collections.svg" width=32 alt="'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'"> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
        
        <form>
			<fieldset>
				<label for="year">Année :</label>
				<select name="year" id="year" class="goto">
					<option value="?" />Cumul</option>
                    '.$years.'
				</select>
			</fieldset>
		</form>
		<br>
        
        <table class="sortable admin-table">
            <thead class="pointer">
                <th></th>
                <th>Collection</th>
                <th>Ventes</th>
                <th>CA</th>
            </thead>
            <tbody>
                '.$list.'
            </tbody>
			<tfoot>
				<th></th>
				<th class="right">Total :</th>
				<th class="right">'.$TotalVentes.'</th>
				<th class="right">'.price($TotalCA,'EUR').'</th>
			</tfoot>
        </table>
    ';

?>