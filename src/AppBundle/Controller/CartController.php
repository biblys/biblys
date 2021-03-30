<?php


namespace AppBundle\Controller;

use ArticleManager;
use CartManager;
use Framework\Controller;
use StockManager;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}