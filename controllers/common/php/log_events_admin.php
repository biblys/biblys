<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Check user rights

if (getLegacyVisitor()->isAdmin()) $mode = 'admin';
elseif (getLegacyVisitor()->isPublisher()) $mode = 'publisher';
elseif (getLegacyVisitor()->isBookshop()) $mode = 'bookshop';
elseif (getLegacyVisitor()->isLibrary()) $mode = 'library';
else trigger_error('Accès non autorisé pour '.getLegacyVisitor()->get('user_email'));

/** @var Request $request */
$request->attributes->set("page_title", "Gestion des évènements");

$em = new EventManager();

/** @var Site $site */
$where = array('events`.`site_id' => $site->get("id"));

if (!getLegacyVisitor()->isAdmin())
{
    if (getLegacyVisitor()->isPublisher()) $where = array_merge($where, array('events`.`publisher_id' => getLegacyVisitor()->getCurrentRight()->get('publisher_id')));
    if (getLegacyVisitor()->isBookshop()) $where = array_merge($where, array('events`.`bookshop_id' => getLegacyVisitor()->getCurrentRight()->get('bookshop_id')));
    if (getLegacyVisitor()->isLibrary()) $where = array_merge($where, array('events`.`library_id' => getLegacyVisitor()->getCurrentRight()->get('library_id')));
}

$events = $em->getAll(
        $where,
        array('order' => 'event_start', 'sort' => 'desc',
            'left-join' => array(
                array('table' => 'publishers', 'key' => 'publisher_id'),
                array('table' => 'bookshops', 'key' => 'bookshop_id'),
                array('table' => 'libraries', 'key' => 'library_id')
            )
        )
);

$table = null;
foreach ($events as $e)
{
    $author = null;
    if ($e->has('publisher_name')) $author = $e->get('publisher_name');
    $statusAlt = $e->get('event_status') ? "En ligne" : "Hors ligne";

    $table .= '<tr>'
                . '<td class="right"><img src="/common/img/square_'.($e->get('event_status') ? 'green' : 'red').'.png" alt="'.$statusAlt.'"></td>'
                . '<td><a href="/evenements/'.$e->get('url').'">'.$e->get('title').'</a></td>'
                . '<td>'.$author.'</td>'
                . '<td class="nowrap">'._date($e->get('start'), 'd/m/Y H:i').'</td>'
                . '<td class="nowrap">'
                    . '<a href="/pages/event_edit?id='.$e->get('id').'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Éditer</a>'
                . '</td>'
            . '</tr>';
}

$alert = null;
if (isset($_GET['success'])) $alert = '<p class="success">'.$_GET['success'].'</p><br>';

$content = '
    <h1><span class="fa fa-calendar"></span>Gestion des évènements</h1>
    <p class="buttonset">
        <a href="/pages/event_edit" class="btn btn-primary"><i class="fa fa-calendar-o"></i> Nouveau</a>
    </p>
    <br>

    '.$alert.'

    <table class="admin-table">
        <thead>
            <tr>
                <th></th>
                <th>Évènement</th>
                <th></th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>
';

return new Response($content);
