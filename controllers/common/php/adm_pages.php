<?php

    $_PAGE_TITLE = 'Gestion des pages';
    $_ECHO .= '
        <h1><span class="fa fa-file"></span> Gestion des pages</h1>

        <p class="buttonset"><a href="/pages/adm_page" class="btn btn-primary"><i class="fa fa-file-o"></i> Nouvelle page</a></p>

        <table class="table">

            <tr>
            <th></th>
            <th>Titre</th>
            <th></th>
            </tr>
    ';

    $pm = new PageManager();
    $pages = $pm->getAll();
    foreach ($pages as $page) {
        $p = $page;
        if($p["page_status"] == 1) $p["status"] = '<img src="/common/img/square_green.png" />';
        else $p["status"] = '<img src="/common/img/square_red.png" />';
        $_ECHO .= '
            <tr>
                <td>'.$p["status"].'</td>
                <td width="99%"><a href="/pages/'.$p["page_url"].'">'.$p["page_title"].'</a></td>
                <td class="right nowrap">
                    <a href="/pages/adm_page?id='.$p["page_id"].'" class="button" title="Éditer"><i class="fa fa-edit fa-lg"></i></a>
                    <a href="/pages/adm_page?del='.$p["page_id"].'" class="button" title="Supprimer" data-confirm="Êtes-vous sûr de vouloir supprimer la page '.$p["page_title"].' ?"><i class="fa fa-trash-o fa-lg"></i></a>
                </td>
            </tr>
        ';
    }

    $_ECHO .= '</table>';

