<?php

namespace AppBundle\Controller;

use CartManager;
use Exception;
use Framework\Controller;

use StockManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class StockController extends Controller
{

    public function searchAction($query)
    {
        $am = $this->entityManager('Article');

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
     */
    public function addToCartAction(Request $request, Session $session, $stock_id)
    {
        $this->auth('admin'); // to be implemented: non-admin users adding stock
                              // items to their own carts

        $sm = $this->entityManager('Stock');
        $cm = $this->entityManager('Cart');

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

        if ($request->isXmlHttpRequest()) {
            // to be implemented
        }

        return $this->redirect('/pages/cart?cart_id='.$cart->get('id'));
    }

    /**
     * Canceling return for a copy
     * GET /stock/{stockId}/cancel-return
    */
    public function cancelReturnAction(Session $session, $stockId) {
        $this->auth('admin');

        $sm = $this->entityManager('Stock');

        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new NotFoundException("Stock $stockId not found");
        }

        $error = false;
        try {
            $stock->cancelReturn();
            $sm->update($stock);
            $session->getFlashBag()->add('success', "L'exemplaire $stockId a bien été remis en vente.");

        } catch (Exception $e) {
            $error = true;
            $session->getFlashBag()->add('error', $e->getMessage());
        }

        return $this->redirect('/pages/adm_stock?id='.$stock->get('id'));
    }

    /**
     * POST /stock/{stockId}/edit-free-price
     * @param Request $request
     * @param int $stockId
     * @return Response
     * @throws Exception
     */
    public function editFreePriceAction(Request $request, int $stockId): Response
    {
        $sm = new StockManager();
        $cm = new CartManager();

        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new NotFoundException("Stock $stockId not found");
        }

        if (!$this->user->hasInCart("stock", $stock->get("id"))) {
            throw new BadRequestHttpException(
                "Impossible de modifier un exemplaire qui n'est pas dans votre panier."
            );
        }

        $newPrice = (int) $request->request->get("new_price", 0);
        $newPriceInCents = $newPrice * 100;
        $stock->editFreePrice($newPriceInCents);
        $sm->update($stock);
        $cm->updateFromStock($this->user->getCart());

        if ($request->headers->get("Accept") === "application/json") {
            return new JsonResponse();
        }

        return new RedirectResponse("/pages/cart");
    }
}
