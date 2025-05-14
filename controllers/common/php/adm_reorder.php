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

$am = new ArticleManager();
$sm = new SupplierManager();
$cm = new CollectionManager();

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Réassort');

$suppliers = $sm->getAll(array(), array('order' => 'supplier_name'));
$suppliers = array_map(function($supplier) {
    return '<option value="/pages/adm_reorder?f='.$supplier->get('id').'">'.$supplier->get('name').'</option>';
}, $suppliers);

$suppliersUrl = LegacyCodeHelper::getGlobalUrlGenerator()->generate("supplier_index");

$_ECHO .= '
        <h1><span class="fa fa-refresh"></span> Réassort</h1>

        <div class="admin">
            <p><a href="'.$suppliersUrl.'">Fournisseurs</a></p>
        </div>

    <form>
        <fieldset>
        <label for="f">Fournisseur :</label>
        <select id="f" class="goto">
        <option value=""> </option>
        '.implode($suppliers).'
        </select>
        </fieldset>
    </form>
    ';

$supplierId = $request->query->get('f');
$supplier = $sm->getById($supplierId);

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    // Change article reorder status
    if (isset($_POST['changeStatus']))
    {
        $article = $am->getById($_POST['article_id']);

        if (!$article)
        {
            trigger_error("Article inexistant");
        }

        $lm = new LinkManager();

        $link = $lm->get(array('site_id' => LegacyCodeHelper::getGlobalSite()['site_id'], 'article_id' => $article->get('id'), 'link_do_not_reorder' => 1));

        if ($link)
        {
            $lm->delete($link);
            $dnr = '0';
        }
        else
        {
            $link = $lm->create(array('site_id' => LegacyCodeHelper::getGlobalSite()['site_id'], 'article_id' => $article->get('id'), 'link_do_not_reorder' => 1));
            $dnr = '1';
        }



        die(json_encode(array(
            'id' => $article->get('id'),
            'ean' => $article->get('ean'),
            'title' => $article->get('title'),
            'url' => $article->get('url'),
            'publisher' => $article->get('publisher'),
            'dnr' => $dnr,
        )));

    }

    // Generate reorder table
    else
    {
        $table = null;
        foreach ($_POST["article"] as $id => $qty)
        {
            if ($qty)
            {
                $article = $am->getById($id);
                $table .= '<tr>
                    <td>'.$article->get('publisher')->get('name').'</td>
                    <td>'.$article->get('title').'</td>
                    <td>'.$article->get('ean').'</td>
                    <td class="right">'.$qty.'</td>
                    </tr> ';
            }
        }

        $_ECHO .= '
    <br>
    <table class="reassort admin-table">
    <thead>
    <th>Éditeur</th>
    <th>Titre</th>
    <th>EAN</th>
    <th>Qté</th>
    </thead>
    <tbody>
    '.$table.'
    </tbody>
    </table>
    ';
    }

}

elseif ($supplier && $_SERVER["REQUEST_METHOD"] == "GET")
{

    $publishers = $supplier->getPublishers();
    $publisherIds = array_map(function ($publisher) {
        return $publisher->get('id');
    }, $publishers);

    if (count($publisherIds) === 0) {
        throw new Exception("Aucun éditeur associé à ce fournisseur.");
    }

    $collections = $cm->getAll(['publisher_id' => $publisherIds]);
    $collectionIds = array_map(function ($collection) {
        return $collection->get('id');
    }, $collections);
    $total = count($collectionIds);

    $_ECHO .= '
    <div id="collections" data-ids="'.implode(',', $collectionIds).'"></div>

    <br>
    <p id="loadingText" class="center">Chargement : <span id="progressValue">0</span> / '.$total.'</p>
    <div id="loadingBar" class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 0%;">
    0%
    </div>
    </div>

            <form method="post" action="/pages/adm_reorder?f='.$_GET["f"].'">
                <fieldset>
                    <table class="reorder admin-table hidden" id="reorder">
                        <thead>
                            <tr class="pointer">
                                <th>Titre</th>
                                <th>Dernière vente</th>
                                <th>Stock</th>
                                <th>Ventes</th>
                                <th>Réa</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="doReorder">
                        </tbody>
                    </table>
    </fieldset>

                <fieldset class="center">
                    <br>
    <button class="btn btn-primary" type="submit">Générer le tableau de réassort</button>
                </fieldset>
    </form>

            <h2>&Agrave; ne pas réassortir</h2>

            <table class="reorder admin-table hidden">
                <thead>
                    <tr class="pointer">
                        <th>Titre</th>
                        <th>Dernière vente</th>
                        <th>Stock</th>
                        <th>Ventes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="doNotReorder">
                </tbody>
            </table>
        ';
}
