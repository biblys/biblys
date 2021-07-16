<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class InventoryController
{

    public function __construct()
    {
        global $urlgenerator, $_V, $_SITE, $session;

        // $this->sm = new \SiteManager();
        $this->im = new \InventoryManager();
        $this->iim = new \InventoryItemManager();
        $this->am = new \ArticleManager();
        $this->stm = new \StockManager();

        // $this->site = $this->sm->getById($_SITE['site_id']);
        $this->user = $_V;
        $this->url = $urlgenerator;
        // $this->session = $session;
    }

    public function indexAction()
    {
        if (!$this->user->isAdmin()) {
            return new Response('<p>Accès réservé aux administrateurs.</p>');
        }

        $inventories = $this->im->getAll();
        $inventories = array_map( function($inventory) {
            $url = $this->url->generate('inventory_show', ['id' => $inventory->get('id')]);
            return '<tr>
                <td><a href="'.$url.'">'.$inventory->get('title').'</a></td>
                <td>'.$inventory->get('created').'</td>
                <td>'.$inventory->get('updated').'</td>
            </tr>';
        }, $inventories);

        return new Response('
            <h1><span class="fa fa-check"></span> Inventaires</h1>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Créé le</th>
                    </tr>
                </thead>
                <tbody>
                    '.implode($inventories).'
                </tbody>
            </table>

        ');

    }

    public function showAction(REQUEST $request, $id, $mode)
    {
        if (!$this->user->isAdmin()) {
            return new Response('<p>Accès réservé aux administrateurs.</p>');
        }

        $inventory = $this->im->getById($id);
        if (!$inventory) {
            throw new \Exception("Inventory $id not found.");
        }

        // Add an item to the inventory
        if ($request->getMethod() == "POST") {

            $ean = $request->request->get('ean');

            // If there already is an item with this ean in this inventory, update quantity
            $item = $this->iim->get(['inventory_id' => $inventory->get('id'), 'ii_ean' => $ean]);
            if ($item) {
                $quantity = $item->get('quantity') + 1;
                $item->set('ii_quantity', $quantity);
            }

            // Else create a new item in this inventory with this ean and quantity 1
            else {
                $item = $this->iim->create();
                $item->set('inventory_id', $inventory->get('id'));
                $item->set('ii_ean', $ean);
                $item->set('ii_quantity', 1);
            }

            // Check related article stock
            $stock_count = 0;
            $article = $this->am->get(['article_ean' => $ean]);
            if ($article) {
                $stocks = $this->stm->getAll(['article_id' => $article->get('id')]);
                foreach ($stocks as $stock) {
                    if ($stock->isAvailable() && $stock->get('condition') == 'Neuf') {
                        $stock_count++;
                    }
                }
            }

            // Update item
            $item->set('ii_stock', $stock_count);
            $this->iim->update($item);

            redirect($this->url->generate('inventory_show', ["id" => $inventory->get('id')]));
        }

        $items_options = ['order' => 'ii_updated', 'sort' => 'desc'];

        if (!$mode) {
            $items_options["limit"] = 10;
        }

        $items = $this->iim->getAll(['inventory_id' => $inventory->get('id')], $items_options);

        $items_tr = array();
        foreach ($items as $item) {

            // If no error, skip in error mode
            $class = null;
            if ($item->get('quantity') == $item->get('stock')) {
                if ($mode == "errors") continue;
            } else {
                $class = ' class="danger"';
            }

            $items_tr[] = '<tr'.$class.'>
                <td>'.$item->get('ean').'</td>
                <td class="right">'.$item->get('quantity').'</td>
                <td class="right">'.$item->get('stock').'</td>
                <td class="center">
                    <a href="'.$this->url->generate('inventory_item_remove', ["inventory_id" => $item->get('inventory_id'), "id" => $item->get('id')]).'" title="Retirer un exemplaire"><i class="fa fa-minus-square"></i></a>
                    <a href="'.$this->url->generate('inventory_item_delete', ["inventory_id" => $item->get('inventory_id'), "id" => $item->get('id')]).'" title="Supprimer la ligne" data-confirm="Voulez-vous vraiment supprimer cette ligne ?"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>';
        }

        if ($mode == "errors") {
            $errorButton = '';
        } else {
            $errorButton = '';
        }

        return new Response('
            <h1><span class="fa fa-check"></span> <a href="'.$this->url->generate('inventory_show', ["id" => $inventory->get('id')]).'">'.$inventory->get('title').'</a></h1>

            <form action="'.$this->url->generate('inventory_show', ["id" => $inventory->get('id')]).'" method="post">
                <fieldset>
                    <input type="text" name="ean" id="ean" class="form-control" autocomplete="off" autofocus placeholder="Ajouter un exemplaire...">
                </fieldset>
            </form>
            <br>

            <p class="text-center">
                <a href="'.$this->url->generate('inventory_show', ['id' => $inventory->get('id'), 'mode' => 'all']).'" class="btn btn-default">Afficher tout</a>
                <a href="'.$this->url->generate('inventory_show', ['id' => $inventory->get('id'), 'mode' => 'errors']).'" class="btn btn-warning">Afficher les erreurs</a>
                <a href="'.$this->url->generate('inventory_import', ["id" => $inventory->get('id')]).'" class="btn btn-primary">Importer le stock</a>
            </p>

            <table class="table">
                <thead>
                    <tr>
                        <th>EAN</th>
                        <th>Qté réelle</th>
                        <th>Qté en base</th>
                    </tr>
                </thead>
                <tbody>
                    '.implode($items_tr).'
                </tbody>
            </table>

        ');

    }

    // Import actual stock into inventory
    public function importAction(Request $request, $id)
    {
        global $_SQL, $_SITE;

        $inventory = $this->im->getById($id);
        if (!$inventory) {
            throw new \Exception("Inventory $id not found.");
        }

        $date = $request->query->get('date', false);
        $time = $request->query->get('time', false);
        $offset = $request->query->get('offset', 0);
        $limit = 100;

        // Reset all inventory items stocks & delete items with no quantity
        if ($offset === 0) {
            $_SQL->exec("UPDATE inventory_item SET ii_stock = 0 WHERE inventory_id = ".$id);
            $_SQL->exec("DELETE FROM inventory_item WHERE inventory_id = ".$id." AND (ii_quantity = 0 OR ii_quantity IS NULL)");
        }

        // Count all available copies
        $stocks = $_SQL->prepare("SELECT COUNT(stock_id) AS stocks, article_ean, stock_purchase_date
            FROM stock
            JOIN articles USING(article_id)
            WHERE article_ean IS NOT NULL AND site_id = :site_id AND stock_condition = 'Neuf'
                AND stock_purchase_date IS NOT NULL AND stock_purchase_date <= :date
                AND (stock_selling_date IS NULL OR stock_selling_date > :date)
                AND (stock_return_date IS NULL OR stock_return_date > :date)
                AND (stock_lost_date IS NULL OR stock_lost_date > :date)
            GROUP BY article_ean
            ORDER BY stock_purchase_date DESC
        ");
        $stocks->execute(['site_id' => $_SITE["site_id"], 'date' => $date." ".$time]);
        $total = count($stocks->fetchAll(\PDO::FETCH_ASSOC));

        // Process 100 more copies
        $stocks = $_SQL->prepare("SELECT COUNT(stock_id) AS stocks, article_ean
            FROM stock
            JOIN articles USING(article_id)
            WHERE article_ean IS NOT NULL AND site_id = :site_id AND stock_condition = 'Neuf'
                AND stock_purchase_date IS NOT NULL AND stock_purchase_date <= :date
                AND (stock_selling_date IS NULL OR stock_selling_date > :date)
                AND (stock_return_date IS NULL OR stock_return_date > :date)
                AND (stock_lost_date IS NULL OR stock_lost_date > :date)
            GROUP BY article_ean
            LIMIT $offset, $limit
        ");
        $stocks->execute(['site_id' => $_SITE["site_id"], 'date' => $date." ".$time]);
        $stocks = $stocks->fetchAll(\PDO::FETCH_ASSOC);

        $table = array();
        foreach ($stocks as $stock) {

            // If there is no item with this ean in this inventory, create
            $item = $this->iim->get(['inventory_id' => $id, 'ii_ean' => $stock["article_ean"]]);
            if (!$item) {
                $item = $this->iim->create();
                $item->set('inventory_id', $id);
                $item->set('ii_ean', $stock["article_ean"]);
            }
            $item->set('ii_stock', $stock["stocks"]);
            $this->iim->update($item);
        }

        // If date is not set, ask for it
        if (!$date) {
           $response = '
                <h1>Importation du stock</h1>

                <form>
                    Importer le stock à date du
                        <input name="date" type="date" value="'.date("Y-m-d").'">
                        <input name="time" type="time" value="'.date("H:i:s").'">
                    <button type="submit" class="btn btn-primary">Commencer</button>
                </form>

           ';
        }

       // If date is set, process import
        elseif ($offset < $total) {
            $progress = round($offset / $total * 100);
            $response = '
                <h1>Importation du stock en cours...</h1>
                <p>'.$total.' références à traiter.</p>
                <div class="progress">
                  <div class="progress-bar" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;">
                    '.$progress.' %
                  </div>
                </div>
                <p class="text-center"><a class="btn btn-primary" href="'.$this->url->generate('inventory_show', ['id' => $inventory->get('id')]).'">Revenir à l\'inventaire</a></p>
                <script>
                    setTimeout( function() {
                        window.location.href = "'.$this->url->generate('inventory_import', ['id' => $inventory->get('id'), 'offset' => $offset + $limit, 'date' => $date, 'time' => $time]).'";
                    }, 1000)
                </script>
            ';
        } else {
            $response = '
                <h1>Importation du stock en cours...</h1>
                <p class="alert alert-success">Le stock a été importé.</p>
                <p class="text-center"><a class="btn btn-primary" href="'.$this->url->generate('inventory_show', ['id' => $inventory->get('id')]).'">Revenir à l\'inventaire</a></p>
            ';
        }

        return new Response($response);
    }

    // Remove this item and all copies completely
    public function itemDeleteAction($inventory_id, $id)
    {
        if (!$this->user->isAdmin()) {
            return new Response('<p>Accès réservé aux administrateurs.</p>');
        }

        $inventory = $this->im->getById($inventory_id);
        if (!$inventory) {
            throw new \Exception("Inventory $inventory_id not found.");
        }

        $item = $this->iim->get(['ii_id' => $id, 'inventory_id' => $inventory->get('id')]);
        if (!$item) {
            throw new \Exception("Item $id not found.");
        }

        $this->iim->delete($item);
        redirect($this->url->generate('inventory_show', ["id" => $inventory->get('id')]));
    }

    // Remove this item and all copies completely
    public function itemRemoveAction($inventory_id, $id)
    {
        if (!$this->user->isAdmin()) {
            return new Response('<p>Accès réservé aux administrateurs.</p>');
        }

        $inventory = $this->im->getById($inventory_id);
        if (!$inventory) {
            throw new \Exception("Inventory $inventory_id not found.");
        }

        $item = $this->iim->get(['ii_id' => $id, 'inventory_id' => $inventory->get('id')]);
        if (!$item) {
            throw new \Exception("Item $id not found.");
        }

        // Only remove if there is quantity
        if ($item->get('quantity') > 0) {
            $quantity = $item->get('quantity') - 1;
            $item->set('ii_quantity', $quantity);
            $this->iim->update($item);
        }

        redirect($this->url->generate('inventory_show', ["id" => $inventory->get('id')]));
    }

}
