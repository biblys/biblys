<?php /** @noinspection SqlCheckUsingColumns */

namespace AppBundle\Controller;

use ArticleManager;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use InventoryItemManager;
use InventoryManager;
use PDO;
use Propel\Runtime\Exception\PropelException;
use StockManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InventoryController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws AuthException
     */
    public function indexAction(Request $request): Response
    {
        self::authAdmin($request);

        $im = new InventoryManager();
        $inventories = $im->getAll();

        return $this->render('AppBundle:Inventory:index.html.twig', [
            "inventories" => $inventories
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws AuthException
     * @throws Exception
     */
    public function showAction(Request $request, UrlGenerator $urlGenerator, $id, $mode): RedirectResponse|Response
    {
        self::authAdmin($request);

        $im = new InventoryManager();
        $inventory = $im->getById($id);
        if (!$inventory) {
            throw new Exception("Inventory $id not found.");
        }

        // Add an item to the inventory
        if ($request->getMethod() == "POST") {

            $ean = $request->request->get('ean');

            // If there already is an item with this ean in this inventory, update quantity
            $iim = new InventoryItemManager();
            $item = $iim->get(['inventory_id' => $inventory->get('id'), 'ii_ean' => $ean]);
            if ($item) {
                $quantity = $item->get('quantity') + 1;
                $item->set('ii_quantity', $quantity);
            }

            // Else create a new item in this inventory with this ean and quantity 1
            else {
                $item = $iim->create();
                $item->set('inventory_id', $inventory->get('id'));
                $item->set('ii_ean', $ean);
                $item->set('ii_quantity', 1);
            }

            // Check related article stock
            $stock_count = 0;
            $am = new ArticleManager();
            $article = $am->get(['article_ean' => $ean]);
            if ($article) {
                $stm = new StockManager();
                $stocks = $stm->getAll(['article_id' => $article->get('id')]);
                foreach ($stocks as $stock) {
                    if ($stock->isAvailable() && $stock->get('condition') == 'Neuf') {
                        $stock_count++;
                    }
                }
            }

            // Update item
            $item->set('ii_stock', $stock_count);
            $iim->update($item);

            return new RedirectResponse(
                $urlGenerator->generate('inventory_show', ["id" => $inventory->get('id')])
            );
        }

        $items_options = ['order' => 'ii_updated', 'sort' => 'desc'];

        if (!$mode) {
            $items_options["limit"] = 10;
        }

        $iim = new InventoryItemManager();
        $items = $iim->getAll(['inventory_id' => $inventory->get('id')], $items_options);

        return $this->render('AppBundle:Inventory:show.html.twig', [
            "inventory" => $inventory,
            "items" => $items,
        ]);
    }

    // Import actual stock into inventory

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function importAction(Request $request, UrlGenerator $urlGenerator, $id): Response
    {
        global $_SQL;

        $im = new InventoryManager();
        $inventory = $im->getById($id);
        if (!$inventory) {
            throw new Exception("Inventory $id not found.");
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
            JOIN articles USING(`article_id`)
            WHERE article_ean IS NOT NULL AND site_id = :site_id AND stock_condition = 'Neuf'
                AND stock_purchase_date IS NOT NULL AND stock_purchase_date <= :date
                AND (stock_selling_date IS NULL OR stock_selling_date > :date)
                AND (stock_return_date IS NULL OR stock_return_date > :date)
                AND (stock_lost_date IS NULL OR stock_lost_date > :date)
            GROUP BY `article_ean`, `stock_purchase_date`
            ORDER BY stock_purchase_date DESC
        ");
        $stocks->execute(['site_id' => \AppBundle\Controller\getLegacyCurrentSite()["site_id"], 'date' => $date." ".$time]);
        $total = count($stocks->fetchAll(PDO::FETCH_ASSOC));

        // Process 100 more copies
        $stocks = $_SQL->prepare("SELECT COUNT(stock_id) AS stocks, article_ean
            FROM stock
            JOIN articles USING(`article_id`)
            WHERE article_ean IS NOT NULL AND site_id = :site_id AND stock_condition = 'Neuf'
                AND stock_purchase_date IS NOT NULL AND stock_purchase_date <= :date
                AND (stock_selling_date IS NULL OR stock_selling_date > :date)
                AND (stock_return_date IS NULL OR stock_return_date > :date)
                AND (stock_lost_date IS NULL OR stock_lost_date > :date)
            GROUP BY article_ean
            LIMIT $offset, $limit
        ");
        $stocks->execute(['site_id' => \AppBundle\Controller\getLegacyCurrentSite()["site_id"], 'date' => $date." ".$time]);
        $stocks = $stocks->fetchAll(PDO::FETCH_ASSOC);

        foreach ($stocks as $stock) {

            // If there is no item with this ean in this inventory, create
            $iim = new InventoryItemManager();
            $item = $iim->get(['inventory_id' => $id, 'ii_ean' => $stock["article_ean"]]);
            if (!$item) {
                $item = $iim->create();
                $item->set('inventory_id', $id);
                $item->set('ii_ean', $stock["article_ean"]);
            }
            $item->set('ii_stock', $stock["stocks"]);
            $iim->update($item);
        }

        $dateIsMissing = $date === false;
        $importInProgress = $offset < $total;
        $nextStepUrl = $urlGenerator->generate('inventory_import', ['id' => $inventory->get('id'), 'offset'
        => $offset + $limit, 'date' => $date, 'time' => $time]);

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

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function itemDeleteAction(
        Request $request,
        UrlGenerator $urlGenerator,
        $inventory_id,
        $id
    ): RedirectResponse|Response
    {
        self::authAdmin($request);

        list($inventory, $item) = $this->_getInventoryAndItem($inventory_id, $id);

        $iim = new InventoryItemManager();
        $iim->delete($item);
        return new RedirectResponse($urlGenerator->generate('inventory_show', ["id" => $inventory->get('id')]));
    }

    // Remove this item and all copies completely

    /**
     * @throws Exception
     */
    public function itemRemoveAction(
        Request $request,
        UrlGenerator $urlGenerator,
        $inventory_id,
        $id
    ): RedirectResponse|Response
    {
        self::authAdmin($request);

        list($inventory, $item) = $this->_getInventoryAndItem($inventory_id, $id);

        // Only remove if there is quantity
        if ($item->get('quantity') > 0) {
            $quantity = $item->get('quantity') - 1;
            $item->set('ii_quantity', $quantity);
            $iim = new InventoryItemManager();
            $iim->update($item);
        }

        return new RedirectResponse($urlGenerator->generate('inventory_show', ["id" => $inventory->get('id')]));
    }

    /**
     * @param $inventory_id
     * @param $id
     * @return array
     * @throws Exception
     */
    public function _getInventoryAndItem($inventory_id, $id): array
    {
        $im = new InventoryManager();
        $inventory = $im->getById($inventory_id);
        if (!$inventory) {
            throw new Exception("Inventory $inventory_id not found.");
        }

        $iim = new InventoryItemManager();
        $item = $iim->get(['ii_id' => $id, 'inventory_id' => $inventory->get('id')]);
        if (!$item) {
            throw new Exception("Item $id not found.");
        }
        return array($inventory, $item);
    }

}
