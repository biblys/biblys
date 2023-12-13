<?php


namespace AppBundle\Controller;

use Article;
use ArticleManager;
use Biblys\Legacy\LegacyCodeHelper;
use Cart;
use CartManager;
use CFReward;
use CFRewardManager;
use Entity\Exception\CartException;
use Exception;
use Framework\Controller;
use Stock;
use StockManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class CartController extends Controller
{
    /**
     * @throws Exception
     */
    public function addArticleAction(
        Request $request,
        int $articleId
    ): JsonResponse|RedirectResponse
    {
        $am = new ArticleManager();
        /** @var Article $article */
        $article = $am->getById($articleId);
        if (!$article) {
            throw new BadRequestHttpException(
                "Cannot find article with id $articleId"
            );
        }

        try {
            $cm = new CartManager();
            $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
            $cm->addArticle($cart, $article);
            $cm->updateFromStock($cart);
        } catch(CartException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        if (in_array("application/json", $request->getAcceptableContentTypes())) {
            return new JsonResponse();
        }

        return new RedirectResponse("/pages/cart");
    }

    /**
     * @throws Exception
     */
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
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->addStock($cart, $stock);
        $cm->updateFromStock($cart);

        return new JsonResponse();
    }

    public function addCrowdfundingRewardAction(int $rewardId): Response
    {
        $cfrm = new CFRewardManager();
        /** @var CFReward $reward */
        $reward = $cfrm->getById($rewardId);
        if (!$reward) {
            throw new BadRequestHttpException(
                "Cannot find reward with id $rewardId"
            );
        }

        $cm = new CartManager();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $cm->addCFReward($cart, $reward);
        $cm->updateFromStock($cart);

        return new JsonResponse();
    }

    /**
     * @throws Exception
     */
    public function removeStockAction(Request $request, int $stockId): Response
    {
        $sm = new StockManager();
        /** @var Stock $stock */
        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new BadRequestHttpException(
                "Cannot find stock with id $stockId"
            );
        }

        $cm = new CartManager();
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
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
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart();
        if (!$cart) {
            $cartSummary = Cart::getOneLineEmpty();
            return new JsonResponse($cartSummary);
        }

        $cartSummary = $cart->getOneLine();
        return new JsonResponse(["summary" => $cartSummary]);
    }
}