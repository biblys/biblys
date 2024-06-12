<?php

	$_PAGE_TITLE = 'Clients';

	if (!isset($_GET['q'])) $_GET['q'] = null;

	$years = null;
	for ($y = date('Y'); $y >= 2010; $y--)
	{
        if (isset($_GET['year']) && $y == $_GET["year"]) $sel = 'selected';
        else $sel = NULL;
        $years .= '<option value="?year='.$y.'" '.$sel.'>'.$y.'</option>';
    }

	$params = array();
	$query = NULL;
	if (!empty($_GET['year']))
	{
		$query = " AND `stock_selling_date` LIKE :year ";
		$params['year'] = (int) $_GET['year'].'%';
	}
	elseif (!empty($_GET['q']))
	{
		$query .= " AND (`customer_first_name` LIKE :q OR `customer_last_name` LIKE :q OR `customer_email` LIKE :q)";
		$params['q'] = '%'.$_GET['q'].'%';
	}
	else $_GET['q'] = NULL;

    $customers = $_SQL->prepare('
        SELECT *, `customers`.`customer_id`, COUNT(`stock_id`) AS `num`, SUM(`stock_selling_price`) AS `CA`, MAX(`stock_selling_date`) AS `DateVente`
            FROM `customers`
            LEFT JOIN `stock` ON `customers`.`customer_id` = `stock`.`customer_id`
        WHERE `customers`.`site_id` = :site_id '.$query.'
        GROUP BY `customers`.`customer_id`
        ORDER BY `CA` DESC
    ');
	$params['site_id'] = $_SITE['site_id'];
	$customers->execute($params);


    $_ECHO = '
		<h1><span class="fa fa-address-card"></span> '.$_PAGE_TITLE.'</h1>

		<p class="buttonset">
            <a class="btn btn-primary" href="/pages/adm_customer"><i class="fa fa-user"></i> Nouveau client</a>
        </p>

		<form class="fieldset">
			<fieldset>
                <legend>Filtres</legend>
                <p>
                    <label for="year">Ann&eacute;e :</label>
                    <select id="year" class="goto">
                        <option value="/pages/adm_customers">Cumul</option>
                        '.$years.'
                    </select>
                    <br />
                </p>
				<p>
                    <label for"query">Rechercher :</label>
                    <input type="text" name="q" id="query" class="long" value="'.$_GET["q"].'" placeholder="Nom, adresse-email, code postal..." /> <input type="submit" value="Rechercher" />
                </p>
            </fieldset>
		</form>

		<br />
        <table class="sortable admin-table">
            <thead>
                <tr class="cliquable">
					<th></th>
                    <th>Client</th>
                    <th>Achats</th>
                    <th>C.A.</th>
                    <th>Dernier</th>
					<th></th>
                </tr>
            </thead>
            <tbody>
    ';

	$Total = 0; $Ventes = 0; $Clients = 0;

    while ($s = $customers->fetch(PDO::FETCH_ASSOC))
	{

        $Total += $s["CA"];
        $Ventes += $s["num"];
        $Clients++;

		if(!empty($s["customer_last_name"])) { $user = trim($s["customer_first_name"].' '.$s["customer_last_name"]); $key = $s["customer_last_name"].' '.$s["customer_first_name"]; }
		else { $user = 'Anonyme'; $key = ' '; }

        $_ECHO .= '
            <tr>
				<td class="right">'.$Clients.'.</td>
                <td sorttable_customkey='.$key.'><a href="/pages/adm_customer?id='.$s['customer_id'].'">'.$user.'</a></td>
                <td class="right"><a href="/pages/adm_orders_shop?customer_id='.$s["customer_id"].'&date1=2001-01-01&date2='.date('Y-m-d').'">'.$s["num"].'</a></td>
                <td class="right" sorttable_customkey="'.$s["CA"].'" style="width: 100px;">'.price($s["CA"],'EUR').'</td>
				<td class="center"><a href="/pages/adm_customer?id='.$s["customer_id"].'"><i class="fa fa-edit fa-lg black"></i></td>
			</tr>
        ';
    }

    $_ECHO .= '
            </tbody>
       </table>
    ';

    $Total = 0;
    $Ventes = 0;
