<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InventoryController extends Controller
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
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function indexAction()
    {
        if (!$this->user->isAdmin()) {
            return new Response('<p>Accès réservé aux administrateurs.</p>');
        }

        $inventories = $this->im->getAll();

        return $this->render('AppBundle:Inventory:index.html.twig', [
            "inventories" => $inventories
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
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

        if ($mode == "errors") {
            $errorButton = '';
        } else {
            $errorButton = '';
        }

        return $this->render('AppBundle:Inventory:show.html.twig', [
            "inventory" => $inventory,
            "items" => $items,
        ]);
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
        $stocks = $_SQL->prepare("SELECT 
                COUNT(`stock_id`) AS `stocks`, 
                `article_ean`, 
                `stock_purchase_date`
            FROM stock
            JOIN articles USING(article_id)
            WHERE article_ean IS NOT NULL AND site_id = :site_id AND stock_condition = 'Neuf'
                AND stock_purchase_date IS NOT NULL AND stock_purchase_date <= :date
                AND (stock_selling_date IS NULL OR stock_selling_date > :date)
                AND (stock_return_date IS NULL OR stock_return_date > :date)
                AND (stock_lost_date IS NULL OR stock_lost_date > :date)
            GROUP BY `article_ean`, `stock_purchase_date`
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

        $dateIsMissing = $date === false;
        $importInProgress = $offset < $total;
        $nextStepUrl = $this->url->generate('inventory_import', ['id' => $inventory->get('id'), 'offset' => $offset + $limit, 'date' => $date, 'time' => $time]);

        $progress = 0;
        if ($total > 0) {
            $progress = round($offset / $total * 100);
        }

        return $this->render('AppBundle:Inventory:import.html.twig', [
            "inventory" => $inventory,
            "dateIsMissing" => $dateIsMissing,
            "importInProgress" => $importInProgress,
            "total" => $total,
            "progress" => $progress,
            "nextStepUrl" => $nextStepUrl,
        ]);
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
