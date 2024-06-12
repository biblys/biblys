<?php


namespace AppBundle\Controller;

use ArticleManager;
use Cart;
use CartManager;
use CFRewardManager;
use Framework\Controller;
use StockManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartController extends Controller
{
    public function addArticleAction(int $articleId): Response
    {
        $am = new ArticleManager();
        $article = $am->getById($articleId);
        if (!$article) {
            throw new BadRequestHttpException(
                "Cannot find article with id $articleId"
            );
        }

        $cm = new CartManager();
        $cart = $this->user->getCart("create");
        $cm->addArticle($cart, $article);
        $cm->updateFromStock($cart);

        return new JsonResponse();
    }

    public function addStockAction(int $stockId): Response
    {
        $sm = new StockManager();
        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new BadRequestHttpException(
                "Cannot find stock with id $stockId"
            );
        }

        $cm = new CartManager();
        $cart = $this->user->getCart("create");
        $cm->addStock($cart, $stock);
        $cm->updateFromStock($cart);

        return new JsonResponse();
    }

    public function addCrowdfundingRewardAction(int $rewardId): Response
    {
        $cfrm = new CFRewardManager();
        $reward = $cfrm->getById($rewardId);
        if (!$reward) {
            throw new BadRequestHttpException(
                "Cannot find reward with id $rewardId"
            );
        }

        $cm = new CartManager();
        $cart = $this->user->getCart("create");
        $cm->addCFReward($cart, $reward);
        $cm->updateFromStock($cart);

        return new JsonResponse();
    }

    public function removeStockAction(Request $request, int $stockId): Response
    {
        $sm = new StockManager();
        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new BadRequestHttpException(
                "Cannot find stock with id $stockId"
            );
        }

        $cm = new CartManager();
        $cart = $this->user->getCart("create");
        $cm->removeStock($cart, $stock);
        $cm->updateFromStock($cart);

        if ($request->headers->get("Accept") === "application/json") {
            return new JsonResponse();
        }

        return new RedirectResponse("/pages/cart?removed=1");
    }

    /**
     * GET /cart/summary
     * @return JsonResponse
     */
    public function summaryAction(): JsonResponse
    {
        $cart = $this->user->getCart();
        if (!$cart) {
            $cartSummary = Cart::getOneLineEmpty();
            return new JsonResponse($cartSummary);
        }

        $cartSummary = $cart->getOneLine();
        return new JsonResponse(["summary" => $cartSummary]);
    }
}