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


namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\TemplateService;
use Cart;
use CartManager;
use Exception;
use Framework\Controller;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Stock;
use StockManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Usecase\UpdateStockItemPriceUsecase;

class StockItemController extends Controller
{

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function newAction(CurrentUser $currentUser, TemplateService $templateService): Response
    {
        $currentUser->authAdmin();

        return $templateService->renderResponse("AppBundle:StockItem:new.html.twig");
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function searchAction($query): JsonResponse
    {
        $am = new ArticleManager();
        $articles = $am->search($query, ["limit" => 100]);

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
     * @route /stock/{stock_id}/add-to-cart
     * @throws PropelException
     * @throws Exception
     */
    public function addToCartAction(
        Request     $request,
        CurrentUser $currentUser,
        Session     $session,
                    $stock_id
    ): RedirectResponse
    {
        // to be implemented: non-admin users adding stockitems to their own carts
        $currentUser->authAdmin();

        $sm = new StockManager();
        $cm = new CartManager();

        /** @var Stock $stock */
        $stock = $sm->getById($stock_id);
        if (!$stock) {
            throw new NotFoundException("Stock $stock_id not found");
        }

        $cart_id = $request->request->get('cart_id'); // An admin can POST the cart_id
        /** @var Cart $cart */
        $cart = $cm->getById($cart_id);
        if (!$cart) {
            throw new NotFoundException("Cart $cart_id not found");
        }

        $error = false;
        try {
            $cm->addStock($cart, $stock);
            $cm->updateFromStock($cart);
        } catch (Exception $e) {
            $error = true;
            $session->getFlashBag()->add('error', $e->getMessage());
        }

        if (!$error) {
            $article = $stock->getArticle();
            $session->getFlashBag()->add('success', $article->get('title') . ' a été ajouté au panier.');
        }

        return new RedirectResponse("/pages/cart?cart_id={$cart->get("id")}");
    }

    /**
     * Canceling return for a copy
     * GET /stock/{stockId}/cancel-return
     * @throws Exception
     */
    public function cancelReturnAction(
        CurrentUser $currentUser,
        Session     $session,
                    $stockId
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $sm = new StockManager();

        /** @var Stock $stock */
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

        return new RedirectResponse('/pages/adm_stock?id=' . $stock->get('id'));
    }

    /**
     * @throws PropelException
     */
    public function cancelLostAction(
        CurrentUser          $currentUser,
        CurrentSite          $currentSite,
        FlashMessagesService $flashMessages,
        int                  $stockId
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $stockItem = StockQuery::create()->filterBySite($currentSite->getSite())->findPk($stockId);
        if (!$stockItem) {
            throw new NotFoundException("Stock $stockId not found");
        }

        $stockItem->setLostDate(null);
        $stockItem->save();

        $flashMessages->add(
            "success",
            "L'exemplaire n° $stockId a été marqué comme retrouvé."
        );

        return new RedirectResponse("/pages/adm_stock?id=$stockId");
    }

    /**
     * POST /stock/{stockId}/edit-free-price
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

    /**
     * @throws PropelException
     */
    public function deleteAction(
        CurrentSite          $currentSite,
        ImagesService        $imagesService,
        FlashMessagesService $flashMessages,
        int                  $stockId
    ): RedirectResponse
    {
        $stockItem = StockQuery::create()->filterBySite($currentSite->getSite())->findPk($stockId);
        if (!$stockItem) {
            throw new NotFoundHttpException("Stock item $stockId not found");
        }

        if ($imagesService->imageExistsFor($stockItem)) {
            $imagesService->deleteImageFor($stockItem);
        }
        $stockItem->delete();

        $flashMessages->add(
            "success",
            "L'exemplaire n° $stockId ({$stockItem->getArticle()->getTitle()}) a été supprimé."
        );

        return new RedirectResponse('/pages/adm_stocks', 301);
    }
}
