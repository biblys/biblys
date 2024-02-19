<?php

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Fournisseurs');

$alerts = null;
if (isset($_GET['deleted'])) {
    $alerts = '<p class="success">Le fournisseur <em>'.$_GET['deleted'].'</em> a bien été supprimé.</p>';
}

$_ECHO .= '
    <h1><span class="fa fa-truck"></span> Fournisseurs</h1>

    '.$alerts.'

    <div class="admin">
      <p><a href="/pages/adm_supplier">Nouveau fournisseur</a></p>
      <p><a href="/pages/adm_suppliers_without">Sans fournisseur</a></p>
    </div>

    <table class="sortable admin-table">
      <thead>
        <tr class="pointer">
          <th>Editeur</th>
          <th>Fournisseur</th>
        </tr>
      </thead>
      <tbody>
  ';

$collections = $_SQL->prepare("SELECT `publisher_name`, `supplier_id`, `supplier_name`
    FROM `suppliers`
    LEFT JOIN `links` USING(`supplier_id`)
    LEFT JOIN `publishers` USING(`publisher_id`)
    WHERE `suppliers`.`site_id` = :site_id
    ORDER BY `publisher_name`");
$collections->execute(['site_id' => $globalSite->get('id')]);
while ($c = $collections->fetch(PDO::FETCH_ASSOC)) {
    $_ECHO .= '
        <tr>
          <td>'.$c["publisher_name"].'</td>
          <td><a href="/pages/adm_supplier?id='.$c["supplier_id"].'">'.$c["supplier_name"].'</a></td>
        </tr>
    ';
}

$_ECHO .= '
      </tbody>
    </table>
  ';

?>
