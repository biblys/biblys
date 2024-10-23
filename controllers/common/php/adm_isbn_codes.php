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


    use Biblys\Isbn\Isbn as Isbn;

    \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Codes ISBN');

    $nexts = null;
    $tbody = null;
    $eans = [];

    $fm = new FileManager();
    $am = new ArticleManager();

    // Article type
    $type_req = [];
    $selected_type = $request->query->get('type');
    if ($selected_type) {
        $type_req = ['type_id' => $selected_type];
    }

    $articles = $am->getAll($type_req, ['order' => 'article_pubdate', 'sort' => 'desc']);
    foreach ($articles as $article) {
        if ($article->has('ean')) {
            $c[$article->get('ean')] = '
                <tr>
                    <td>'.$article->get('ean').'</td>
                    <td class="nowrap">
                        '.Isbn::convertToIsbn13($article->get('ean')).'
                    </td>
                    <td><a href="/'.$article->get('url').'">'.$article->get('title').'</a></td>
                </tr>
            ';
            $eans[] = substr($article->get('ean'), 0, -1);
        }

        $files = $fm->getAll(['article_id' => $article->get('id')]);
        if ($files) {
            foreach ($files as $file) {
                if ($file->has('ean')) {
                    $c[$file->get('ean')] = '
                        <tr>
                            <td>'.$file->get('ean').'</td>
                            <td>
                                '.Isbn::convertToIsbn13($file->get('ean')).'
                            </td>
                            <td><a href="/'.$article->get('url').'">'.$article->get('title').'</a> ('.$file->getType('name').')</td>
                        </tr>
                    ';
                    $eans[] = substr($file->get('ean'), 0, -1);
                }
            }
        }
    }

    // Get most recent EAN to start from
    $ean = $eans[0];

    $isbn_checker_start = $globalSite->getOpt('isbn_checker_start');
    if ($isbn_checker_start) {
        $ean_start = Isbn::convertToEan13($isbn_checker_start);

        $ean = substr($ean_start, 0, -1);

        $found = false;
        while ($found != true) {
            if (!in_array($ean, $eans)) {
                $found = true;
            } else {
                $ean++;
            }
        }
    }

    // Classer par ordre decroissant
    krsort($c);

    // 10 prochains ISBN
    for ($i = 0; $i < 10; $i++) {
        $nexts .= '
            <tr>
                <td>'.Isbn::convertToEan13($ean).'</td>
                <td>'.Isbn::convertToEan13($ean).'</td>
            </tr>
        ';
        $ean++;
    }

    // Article types
    $types = \Biblys\Data\ArticleType::getAll();
    $types_options = [];
    foreach ($types as $type) {
        $types_options[] = '
            <option '.($selected_type == $type->getId() ? 'selected' : '').' value="?type='.$type->getId().'">
                '.$type->getName().'
            </option>';
    }


    $_ECHO .= '
        <h1><span class="fa fa-barcode"> Codes ISBN</h1>

        <p>
            <select class="form-control goto">
                <option>Filtrer par type...</option>
                '.join($types_options).'
            </select>
        </p>

        <h3>Prochains ISBN libres</h3><br />
        <table class="sortable admin-table">
            <thead>
                <th>EAN</th>
                <th>ISBN</th>
            </thead>
            '.$nexts.'
        </table>
    ';

    // Afficher tout
    foreach ($c as $ean => $line) {
        if (!isset($last)) {
            $last = $ean;
        }
        $tbody .= $line;
    }

    $_ECHO .= '
        <h3>Tous les ISBNs utilisés</h3><br />
        <table class="sortable admin-table">
            <thead>
                <th>EAN</th>
                <th>ISBN</th>
                <th>Titre</th>
            </thead>
            '.$tbody.'
        </table>
    ';
