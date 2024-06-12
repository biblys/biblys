<?php

if (!$_V->isAdmin()) {
    trigger_error("Vous n'avez pas le droit d'accéder à cette page.");
}

$lm = new ListeManager();
$sm = new StockManager();
$linm = new LinkManager();

$_PAGE_TITLE = 'Listes';

$_ECHO .= '
        <h1><span class="fa fa-list"></span> '.$_PAGE_TITLE.'</h1>
    ';

$newList = $request->request->get('new_list');
if ($newList) {
    $list = $lm->create([
        'user_id' => $_V->get('user_id'),
    ]);

    $listUrl = $request->request->get('list_url', false);
    if (!$listUrl) {
        $listUrl = makeurl($newList);
    }

    // Append id to url if it already exist
    $listWithSameUrl = $lm->get(['list_url' => $listUrl]);
    if ($listWithSameUrl) {
        $listUrl = '_'.$list->get('id');
    }

    $list->set('list_title', $newList);
    $list->set('list_url', $listUrl);
    $lm->update($list);

    redirect('/list/'.$listUrl);
}

$_ECHO .= '
        <p>
        <label>Mes listes :</label>
        <select class="goto">
            <option />
    ';

$lm = new ListeManager();
$lists = $lm->getAll([], ['order' => 'list_title']);

foreach ($lists as $list) {
    $_ECHO .= '<option value="/list/'.$list->get('url').'">'.$list->get('title').'</option>';
}

$_ECHO .= '
        </select>
        </p>

        <br>
        <form method="post">
            <fieldset>
                <label for="new_list">Nouvelle liste :</label>
                <input type="text" name="new_list" id="new_list" autofocus>
                <button class="btn btn-primary" type="submit">Créer</button>
            </fieldset>
        </form>
    ';

if (isset($_GET['emptied'])) {
    $_ECHO .= '<p class="success">La liste a bien été vidée</p>';
}
if (isset($_GET['deleted'])) {
    $_ECHO .= '<p class="success">La liste a bien été supprimée</p>';
}
if (isset($_GET['returned'])) {
    $_ECHO .= '<p class="success">Les exemplaires de la liste ont bien été retournés</p>';
}

$url = $request->query->get('url');
$list = $lm->get(['list_url' => $url]);
if ($list) {
    $l = $list;

    if ($_V->isAdmin()) {
        $_ECHO .= '
                    <div class="admin">
                        Liste n° '.$list->get('id').'
                    </div>
                ';
    }

    $action = $request->query->get('action');
    if ($action == 'delete') {
        $links = $linm->getAll(['list_id' => $list->get('id')]);
        foreach ($links as $link) {
            $linm->delete($link);
        }
        $lm->delete($list);
        redirect('/pages/list?deleted=1');
    }

    $group_by = 'GROUP BY `stock_id`';
    if ($request->query->get('regroup', false)) {
        $group_by = 'GROUP BY `article_id`';
    }

    $Total = 0;
    $TotalPrice = 0;
    $export = [];
    $articles_in_list = null;
    $articles = $_SQL->query('
                SELECT `link_id`, `stock_id`, COUNT(`stock_id`) AS `count`, COUNT(`stock_id`) * `stock_selling_price` AS `total`, 
                        `stock`.`article_id`, `article_title`, `article_ean`, `article_url`, `article_collection`, `article_publisher`, 
                        `stock_selling_price`, `stock_return_date`, `stock_selling_date`, `stock_cart_date`, `stock_lost_date`, `stock_stockage`
                    FROM `links`
                    JOIN `stock` USING(`stock_id`)
                    JOIN `articles` ON `stock`.`article_id` = `articles`.`article_id`
                    WHERE `list_id` = '.$list->get('id').' AND `link_deleted` IS NULL
                    '.$group_by.'
                    ORDER BY `link_id` DESC
                ');

    while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
        if (isset($_GET['action']) && $_GET['action'] == 'return' && $_V->isAdmin()) {
            $stock = $sm->getById($a['stock_id']);
            $stock->setReturned();
            $sm->update($stock);
            $a['led'] = 'square_orange';
        }

        $copy = new Stock($a);

        $articles_in_list .= '
                    <tr id="link_'.$a['link_id'].'">
                        <td class="right">
                            '.$copy->getAvailabilityDot().'
                        </td>
                        <td><a href="/'.$a['article_url'].'">'.$a['article_title'].'</a></td>
                        <td>'.$a['article_collection'].'</td>
                        <td data-price='.$a['stock_selling_price'].' data-stock_id='.$a['stock_id'].' data-article_id='.$a['article_id'].' data-article_title="'.$a['article_title'].'" class="right pointer changePriceInList e">'.price($a['stock_selling_price'], 'EUR').'</td>
                        <td class="text-right">'.$a['count'].'</td>
                        <td class="text-right">'.price($a['total'], 'EUR').'</td>
                        <td class="center" style="width: 75px;">
                            <a href="/pages/adm_stock?id='.$a['stock_id'].'" title="Modifier l\'exemplaire"><i class="fa fa-lg fa-edit black"></i></a>
                            <i class="fa fa-trash-o fa-lg" class="pointer" title="Retirer de la liste" onClick="delFromList('.$a['link_id'].')"></i>
                        </td>
                    </tr>
                ';

        $export[] = [$a['article_publisher'], $a['article_title'], $a['article_ean'], price($a['stock_selling_price']), $a['count'], price($a['total'])];

        ++$Total;
        $TotalPrice += $a['stock_selling_price'];
    }

    if (isset($_GET['action']) && $_GET['action'] == 'return' and auth('admin')) {
        redirect('/list/'.$l['list_url'].'?returned=1');
    }

    $_PAGE_TITLE = $list->get('title');
    $_ECHO .= '
                <h2><a href="/list/'.$l['list_url'].'">'.$_PAGE_TITLE.'</a></h2>

                <label for="list">Ajouter &agrave; la liste :</label>
                <input type="text" name="list" id="list" class="autocomplete verylong" placeholder="Titre, auteur, &eacute;diteur, ISBN..." data-list_id="'.$l['list_id'].'" autofocus />
                <input type="hidden" id="var1" value="'.$l['list_id'].'" />
                <input type="hidden" id="list_id" value="'.$l['list_id'].'" />
                <br><br>

                <form action="/pages/export_to_csv" method="post">
                    <fieldset class="center">
                        <input type="hidden" name="filename" value="'.$l['list_url'].'">
                        <input type="hidden" name="data" value="'.htmlentities(json_encode($export)).'">

                        <button class="btn btn-primary" type="submit" title="Télécharger la liste au format CSV"><i class="fa fa-cloud-download"></i> télécharger</button>
                        <a href="/list/'.$list->get('url').'?regroup=1" class="btn btn-info" title="Regrouper les exemplaire par article"><i class="fa fa-compress"></i> regrouper</a>
                        <a href="/pages/adm_stock_bulk?list_id='.$list->get('id').'" class="btn btn-success" title="Éditer chaque exemplaire de la liste"><i class="fa fa-edit"></i> éditer tout</a>
                        <a href="/list/'.$list->get('url').'?action=return" class="btn btn-warning" data-confirm="Voulez-vous vraiment RETOURNER tous les exemplaires de la liste ?" title="Retourner tous les exemplaires de la liste"><i class="fa fa-hand-o-left"></i> retourner</a>
                        <a href="/list/'.$list->get('url').'?action=delete" class="btn btn-danger" data-confirm="Voulez-vous vraiment VIDER et SUPPRIMER cette liste ?" title="Supprimer la liste"><i class="fa fa-trash-o"></i> supprimer</a>
                    </fieldset>
                </form>
                <br>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Titre</th>
                            <th>Collection</th>
                            <th>Prix</th>
                            <th>Qté</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$articles_in_list.'
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-center">Total : '.$Total.' ex. | '.price($TotalPrice, 'EUR').'</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                <br>
                <form action="/pages/export_to_csv" method="post">
                    <fieldset class="center">
                        <input type="hidden" name="filename" value="'.$l['list_url'].'">
                        <input type="hidden" name="data" value="'.htmlentities(json_encode($export)).'">

                        <button class="btn btn-primary" type="submit" title="Télécharger la liste au format CSV"><i class="fa fa-cloud-download"></i> télécharger</button>
                        <a href="/list/'.$list->get('url').'?regroup=1" class="btn btn-info" title="Regrouper les exemplaire par article"><i class="fa fa-compress"></i> regrouper</a>
                        <a href="/pages/adm_stock_bulk?list_id='.$list->get('id').'" class="btn btn-success" title="Éditer chaque exemplaire de la liste"><i class="fa fa-edit"></i> éditer tout</a>
                        <a href="/list/'.$list->get('url').'?action=return" class="btn btn-warning" data-confirm="Voulez-vous vraiment RETOURNER tous les exemplaires de la liste ?" title="Retourner tous les exemplaires de la liste"><i class="fa fa-hand-o-left"></i> retourner</a>
                        <a href="/list/'.$list->get('url').'?action=delete" class="btn btn-danger" data-confirm="Voulez-vous vraiment VIDER et SUPPRIMER cette liste ?" title="Supprimer la liste"><i class="fa fa-trash-o"></i> supprimer</a>
                    </fieldset>
                </form>
            ';
}
