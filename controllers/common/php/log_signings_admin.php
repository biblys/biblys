<?php
    
    // Check user rights
use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

if (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) $mode = 'admin';
	elseif (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $mode = 'publisher';
	else throw new AccessDeniedHttpException('Accès non autorisé');
    
    LegacyCodeHelper::setGlobalPageTitle('Gestion des dédicaces');
    
    $sm = new SigningManager();
    
    $export_header = array('Auteur', 'Date', 'Début', 'Fin', 'Exposant', 'Stand');
    
    $where = array();
    if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin())
    {
        if (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $where = array_merge($where, array('publisher_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id')));
    }
    
    $signings = $sm->getAll($where, array('order' => 'signing_date', 'sort' => 'desc'));
    
    $table = null; $export = array();
    foreach ($signings as $s)
    {
        if ($s->has('people') && $s->has('publisher'))
        {
            $table .= '<tr>'
                        . '<td><a href="/'.$s->get('people')->get('url').'/">'.$s->get('people')->get('name').'</a></td>'
                        . '<td>'._date($s->get('date'), 'd/m/Y').'</td>'
                        . '<td>'.$s->get('starts').'</td>'
                        . '<td>'.$s->get('ends').'</td>'
                        . '<td class="center"><a class="btn btn-primary btn-sm center" href="/pages/log_signing_edit?id='.$s->get('id').'"><i class="fa fa-edit"></i> Éditer</button></td>'
                    . '</tr>';
            $export[] = array($s->get('people')->get('name'), _date($s->get('date'), 'd/m/Y'), $s->get('starts'), $s->get('ends'), $s->get('publisher')->get('name'), $s->get('location'));
        }
    }
    
    $alert = null;
    if (isset($_GET['success'])) $alert = '<p class="success">'.$_GET['success'].'</p><br>';
    
    $_ECHO .= '
        <h1><i class="fa fa-pencil"></i> '. LegacyCodeHelper::getGlobalPageTitle().'</h1>

        <form action="/pages/export_to_csv" method="post">
			<fieldset>
                <p class="buttonset">
                    <a href="/pages/log_signing_edit" class="btn btn-primary"><i class="fa fa-pencil"></i> Ajouter une dédicace</a>
                    <input type="hidden" name="filename" value="dedicaces-'.date('Y').'">
                    <input type="hidden" name="header" value="'.htmlentities(json_encode($export_header)).'">
                    <input type="hidden" name="data" value="'.htmlentities(json_encode($export)).'">
                    <button class="btn btn-default" type="submit"><i class="fa fa-download"></i> Télécharger au format CSV</button>
                </p>
            </fieldset>
		</form>
        <br>

        '.$alert.'

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                '.$table.'
            </tbody>
        </table>

	';

