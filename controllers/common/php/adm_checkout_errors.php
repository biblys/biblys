<?php

	$_PAGE_TITLE = 'Erreurs de caisse';

	$orders = $_SQL->prepare("SELECT `order_id`, `order_insert`, `order_amount`, SUM(`stock_selling_price`) AS `order_total`, `order_payment_cash`+`order_payment_card`+`order_payment_cheque`-`order_payment_left` AS `order_payment_total`, `user_screen_name`
		FROM `orders`
		JOIN `stock` USING(`order_id`)
		JOIN `Users` ON `Users`.`id` = `seller_id`
		WHERE `orders`.`site_id` = :site_id AND `order_type` = 'shop'
		GROUP BY `order_id`
		ORDER BY `order_insert` DESC");
    $orders->execute(['site_id' => $site->get('id')]);
    $errors = null;
	while ($o = $orders->fetch(PDO::FETCH_ASSOC)) {
		if($o["order_total"] != $o["order_payment_total"] || $o["order_total"] != $o["order_amount"] || $o["order_amount"] != $o["order_payment_total"])
		$errors .= '
			<tr>
				<td class="right"><a href="/pages/adm_orders_shop?d='._date($o["order_insert"],'Y-m-d').'#order_'.$o["order_id"].'">'.$o["order_id"].'</a></td>
				<td class="center">'._date($o["order_insert"],'d/m/Y').'</td>
				<td class="right">'.price($o["order_amount"],'EUR').'</td>
				<td class="right">'.price($o["order_total"],'EUR').'</td>
				<td class="right">'.price($o["order_payment_total"],'EUR').'</td>
			</tr>
		';
	}

	$_ECHO .= '
		<h2>Erreurs de caisse</h2>

		<table class="table">
			<thead>
				<tr>
					<th>Achat n&deg;</th>
					<th>Date</th>
					<th>Montant</th>
					<th>Total du panier</th>
					<th>Montant encaiss&eacute;</th>
				</tr>
			</thead>
			<tbody>
				'.$errors.'
			</tbody>
		</table>
	';

?>
