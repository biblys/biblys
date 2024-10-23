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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

// Check user rights

if (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) $mode = 'admin';
elseif (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $mode = 'publisher';
elseif (LegacyCodeHelper::getGlobalVisitor()->isBookshop()) $mode = 'bookshop';
elseif (LegacyCodeHelper::getGlobalVisitor()->isLibrary()) $mode = 'library';
else throw new AccessDeniedHttpException('Accès non autorisé');

/** @var Request $request */
$request->attributes->set("page_title", "Gestion des évènements");

$em = new EventManager();

$where = array('events`.`site_id' => $globalSite->get("id"));

if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin())
{
    if (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $where = array_merge($where, array('events`.`publisher_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id')));
    if (LegacyCodeHelper::getGlobalVisitor()->isBookshop()) $where = array_merge($where, array('events`.`bookshop_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('bookshop_id')));
    if (LegacyCodeHelper::getGlobalVisitor()->isLibrary()) $where = array_merge($where, array('events`.`library_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('library_id')));
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
