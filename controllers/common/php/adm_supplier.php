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


use Biblys\Legacy\LegacyCodeHelper;

$sm = new SupplierManager();

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Nouveau fournisseur');

if (isset($_GET['delete']))
{
	$supplier = $sm->getById($_GET['id']);
	if ($supplier)
	{
		$sm->delete($supplier);
        $suppliersUrl = LegacyCodeHelper::getGlobalUrlGenerator()->generate("supplier_index");
		redirect($suppliersUrl, array('deleted' => $supplier->get('name')));
	}
}

if ($request->getMethod() === 'POST') {


	if (empty($_POST["supplier_id"])) {
		$supplier = $sm->create();
		$_POST['supplier_id'] = $supplier->get('id');
		$params['created'] = 1;
	} else {
		$supplier = $sm->getById($_POST['supplier_id']);
		$params['updated'] = 1;
	}

	foreach ($_POST as $key => $val) {
		$params = array();

		$supplier->set($key, $val);
	}

	// Checkboxes
	$noTax = $request->request->get("supplier_notva") ? 1 : 0;
	$onOrder = $request->request->get("supplier_on_order") ? 1 : 0;

	$supplier->set('supplier_notva', $noTax);
	$supplier->set('supplier_on_order', $onOrder);

	$sm->update($supplier);

	// Updated linked articles
	$params['articles_to_update'] = 0;
	$articles = $_SQL->query("SELECT `articles`.`article_id` FROM `articles` JOIN `publishers` USING(`publisher_id`) JOIN `links` USING(`publisher_id`) WHERE `supplier_id` = '".$_POST["supplier_id"]."'");
	while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
		$_SQL->exec("UPDATE `articles` SET `article_keywords_generated` = NULL, `article_updated` = NOW() WHERE `article_id` = ".$a["article_id"]." LIMIT 1");
		$params['articles_to_update']++;
	}

	$params['id'] = $supplier->get('id');
	redirect('/pages/adm_supplier', $params);

}

$supplier = new Supplier(array());
if (isset($_GET['id'])) {
	$supplier = $sm->getById($_GET['id']);

	if (!$supplier) {
		trigger_error('Fournisseur inexistant.');
	}

	\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Modifier le fournisseur '.$supplier->get('name'));
}

$alerts = null;

if (isset($_GET["created"])) $alerts .= '<p class="success">Le nouveau fournisseur a été créé.</p>';
if (isset($_GET["updated"])) $alerts .= '<p class="success">Le fournisseur a été mis à jour.</p>';

if (isset($_GET["articles_to_update"])) {
	$searchTermsUrl = \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('article_search_terms');
	$alerts .= '<p class="warning">'.$_GET['articles_to_update'].' articles devront être <a href="'.$searchTermsUrl.'">mis à jour</a>.</p>';
}

$suppliersUrl = LegacyCodeHelper::getGlobalUrlGenerator()->generate("supplier_index");

$_ECHO .='
	<h1><span class="fa fa-truck"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>

	<div class="admin">
		<p><a href="'.$suppliersUrl.'">Fournisseurs</a></p>
	</div>

	'.$alerts.'

	<form action="/pages/adm_supplier" method="post" class="fieldset">
		<fieldset>
			<legend>Informations</legend>
			<p>
				<label for="supplier_id" class="disabled">Fournisseur n&deg;</label>
				<input type="text" name="supplier_id" id="supplier_name" value="'.$supplier->get('id').'" class="nano" readonly>
			</p>

			<p>
				<label for="supplier_name" class="required">Nom :</label>
				<input type="text" name="supplier_name" id="supplier_name" value="'.$supplier->get('name').'" required>
			</p>
		</fieldset>
		<fieldset>
			<legend>Options</legend>
			<p>
				<input type="checkbox" name="supplier_notva" id="supplier_notva" value="1" '.($supplier->has('notva') ? 'checked' : null).'>
				<label class="after" for="supplier_notva">Exempté de TVA</label>
			</p>
		</fieldset>
		<fieldset class="center">
			<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Enregistrer le fournisseur</button>
			<a href="/pages/adm_supplier?id='.$supplier->get('id').'&delete" class="btn btn-danger" data-confirm="Voulez-vous vraiment SUPPRIMER le fournisseur '.$supplier->get('name').' ?"><i class="fa fa-trash-can"></i> Supprimer</a>
		</fieldset>
	</form>
';
