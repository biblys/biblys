<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use CartManager;
use Exception;
use Framework\Controller;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use StockManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Usecase\UpdateStockItemPriceUsecase;

class StockController extends Controller
{

    public function searchAction($query): JsonResponse
    {
        $am = new ArticleManager();
        $articles = $am->search($query);

        $results = [];
        foreach ($articles as $article) {
            $new = $article->getAvailableItems('new');
            $used = $article->getAvailableItems('used');

            if (count($new) > 0) {
                $results[] = [
                    'title' => $article->get('title'),
                    'authors' => $article->get('authors'),
                    'publisher' => $article->get('publisher')->get('name'),
                    'count' => count($new),
                    'condition' => 'Neuf',
                    'price' => currency($new[0]->get('selling_price') / 100),
                    'id' => $new[0]->get('id')
                ];
            }

            if (count($used) > 0) {
                foreach ($used as $copy) {
                    $results[] = [
                        'title' => $article->get('title'),
                        'authors' => $article->get('authors'),
                        'publisher' => $article->get('publisher')->get('name'),
                        'count' => 1,
                        'condition' => $copy->get('condition'),
                        'price' => currency($copy->get('selling_price') / 100),
                        'id' => $copy->get('id')
                    ];
                }
            }
        }

        return new JsonResponse($results);
    }

    /**
     * Adding a stock item to cart
     * /stock/{stock_id}/add-to-cart
     * @throws PropelException
     */
    public function addToCartAction(Request $request, Session $session, $stock_id): RedirectResponse
    {
        // to be implemented: non-admin users adding stockitems to their own carts
        self::authAdmin($request);

        $sm = new StockManager();
        $cm = new CartManager();

        $stock = $sm->getById($stock_id);
        if (!$stock) {
            throw new NotFoundException("Stock $stock_id not found");
        }

        $cart_id = $request->request->get('cart_id'); // An admin can POST the cart_id
        $cart = $cm->getById($cart_id);
        if (!$cart) {
            throw new NotFoundException("Cart $cart_id not found");
        }

        $error = false;
        try {
            $cm->addStock($cart, $stock);
            $cm->updateFromStock($cart);
        } catch(Exception $e) {
            $error = true;
            $session->getFlashBag()->add('error', $e->getMessage());
        }

        if (!$error) {
            $article = $stock->getArticle();
            $session->getFlashBag()->add('success', $article->get('title').' a été ajouté au panier.');
        }

        return new RedirectResponse("/pages/cart?cart_id={$cart->get("id")}");
    }

    /**
     * Canceling return for a copy
     * GET /stock/{stockId}/cancel-return
     * @throws PropelException
     */
    public function cancelReturnAction(Request $request, Session $session, $stockId): RedirectResponse
    {
        self::authAdmin($request);

        $sm = new StockManager();

        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new NotFoundException("Stock $stockId not found");
        }

        try {
            $stock->cancelReturn();
            $sm->update($stock);
            $session->getFlashBag()->add('success', "L'exemplaire $stockId a bien été remis en vente.");

        } catch (Exception $e) {
            $session->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse('/pages/adm_stock?id='.$stock->get('id'));
    }

    /**
     * POST /stock/{stockId}/edit-free-price
     * @param Request $request
     * @param int $stockId
     * @return Response
     * @throws Exception
     */
    public function editFreePriceAction(
        Request     $request,
        CurrentUser $currentUser,
        CurrentSite $currentSite,
        int         $stockId
    ): Response
    {
        $stockItem = StockQuery::create()->filterBySite($currentSite->getSite())->findPk($stockId);
        if (!$stockItem) {
            throw new NotFoundException("Stock $stockId not found");
        }

        $newPrice = (int)$request->request->get("new_price", 0);
        $newPriceInCents = $newPrice * 100;

        $usecase = new UpdateStockItemPriceUsecase($currentUser);
        $usecase->execute($stockItem, $newPriceInCents);


        if ($request->headers->get("Accept") === "application/json") {
            return new JsonResponse();
        }

        return new RedirectResponse("/pages/cart");
    }
}
