<?php

    $_PAGE_TITLE = 'Stock éditeur';

    $am = new ArticleManager();
    $sm = new StockManager();

    $articles = $am->getAll(array('publisher_id' => $_SITE['publisher_id']));
    
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
        <h1>'.$_PAGE_TITLE.'</h1>
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
	