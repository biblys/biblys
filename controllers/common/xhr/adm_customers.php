<?php

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