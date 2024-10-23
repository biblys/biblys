<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


	$_CUSTOMERS = new CustomerManager();

	if (isset($_GET['term']))
	{
		
		$customers = $_CUSTOMERS->search($_GET['term']);
		
		$j = array();
		foreach ($customers as $c)
		{
			$ci['label'] = $c->get('customer_last_name').', '.$c->get('customer_first_name').' ('.$c->get('customer_email').')';
			$ci['value'] = '';
			$ci['id'] = $c->get('customer_id');
			$ci['newsletter'] = $c->get('customer_newsletter');
			$j[] = $ci;
		}
		
		// Créer un nouveau client
		$ci['label'] = '=> Créer un nouveau client';
		$ci['value'] = '';
		$ci['create'] = 1;
		$j[] = $ci;
		
		echo json_encode($j);
		
	}