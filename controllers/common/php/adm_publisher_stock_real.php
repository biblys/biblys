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

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Stock éditeur');

    $am = new ArticleManager();
    $sm = new StockManager();

    $articles = $am->getAll(array('publisher_id' => LegacyCodeHelper::getGlobalSite()['publisher_id']));
    
    $table = array();
    foreach ($articles as $article)
    {
        $article_stock = 0;
        
        $stock = $sm->getAll(array('article_id' => $article->get('id')));
        foreach ($stock as $s)
        {
            if ($s->isAvailable())
            {
                $article_stock++;
            }
        }
        
        $table[] = '<tr>
            <td sorttable_customkey="'.$article->get('title_alphabetic').'"><a href="/'.$article->get('url').'">'.$article->get('title').'</a></td>
            <td sorttable_customkey="'.$article->get('title_alphabetic').'">'.authors($article->get('authors'), 'url').'</td>
            <td class="right stock" data-title="'.$article->get('title').'" data-id="'.$article->get('id').'" data-stock="'.$article_stock.'">'
                . '<a href="/pages/adm_stocks?article_id='.$article->get('id').'">'.$article_stock.'</a></td>
        </tr>';
    }
    
    $_ECHO .= '
        <h1>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>
        <table class="sortable admin-table publisher_stock" cellpadding=0 cellspacing=0>
            <thead class="pointer">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                '.implode($table).'
            </tbody>
        </table>
    ';
	