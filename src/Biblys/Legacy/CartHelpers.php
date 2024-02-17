<?php

namespace Biblys\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Model\Article;
use Model\ArticleCategoryQuery;
use Model\ArticleQuery;
use Model\Cart;
use Model\LinkQuery;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class CartHelpers
{

    /**
     * @throws PropelException
     */
    public static function getCartSuggestions(CurrentSite $currentSite, UrlGenerator $urlGenerator, ImagesService $imagesService): string
    {
        $cartSuggestions = "";

        $cartSuggestionsRayonId = $currentSite->getOption("cart_suggestions_rayon_id");
        if (!$cartSuggestionsRayonId) {
            return "";
        }

        $articleCategory = ArticleCategoryQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findPk($cartSuggestionsRayonId);
        $articleCategoryLinks = LinkQuery::create()
            ->filterByArticleCategory($articleCategory)
            ->find();
        $articleIds = array_map(function ($link) {
            return $link->getArticleId();
        }, $articleCategoryLinks->getData());
        $articles = ArticleQuery::create()
            ->filterById($articleIds)
            ->find();
        if (!$articles) {
            return "";
        }

        $cartSuggestions .= '
                <h3>' . $articleCategory->getName() . '</h3>
                <div class="cart-suggestions">
            ';
        /** @var Article $article */
        foreach ($articles as $article) {
            $cartUrl = $urlGenerator->generate("cart_add_article", ["articleId" => $article->getId()]);
            $coverHtml = "";
            if ($imagesService->articleHasCoverImage($article)) {
                $articleCover = $imagesService->getCoverImageForArticle($article);
                $coverHtml = '
                        <div class="cart-suggestions_article_cover">
                            <a href="' . $urlGenerator->generate("article_show", ["slug" => $article->getUrl()]) . '">
                                <img 
                                    src="' . $articleCover->getUrl() . '" 
                                    alt="' . $article->getTitle() . '"
                                    title="' . $article->getTitle() . '" 
                                />
                            </a>
                        </div>';
            }
            $cartSuggestions .= '
                    <article class="cart-suggestions_article">
                        ' . $coverHtml . '
                        <div class="cart_suggestions_article_infos">
                            <strong>' . currency($article->getPrice(), cents: true) . '</strong>
                            <form class="form-inline" action="' . $cartUrl . '" method="post"> 
                                <button type="submit"
                                    class="btn btn-primary btn-sm"
                                    aria-label="Ajouter '.$article->getTitle().' au panier"
                                >
                                    <span class="fa fa-shopping-cart"></span>
                                </button>
                            </form>
                        </div>
                    </article>
                ';
        }
        $cartSuggestions .= '</div><br />';

        return $cartSuggestions;
    }

    /**
     * @throws PropelException
     */
    public static function getFreeShippingNotice(CurrentSite $currentSite, Cart $cart, mixed $cartTotal): string
    {
        if (!CartHelpers::cartNeedsShipping($cart)) {
            return "";
        }

        $freeShippingTargetAmount = $currentSite->getOption("free_shipping_target_amount");
        if (!$freeShippingTargetAmount) {
            return "";
        }

        $missingAmount = $freeShippingTargetAmount - $cartTotal;
        $formattedTargetAmount = currency($freeShippingTargetAmount / 100);
        if ($missingAmount <= 0) {
            $freeShippingSuccessText = $currentSite->getOption(
                "free_shipping_success_text",
                "Vous bénéficiez de la livraison offerte !"
            );
            return '
                    <p class="alert alert-success">
                        <span class="fa fa-check-circle"></span> 
                        ' . $freeShippingSuccessText . '
                    </p>
                ';
        } else {
            $freeShippingInviteText = $currentSite->getOption(
                "free_shipping_invite_text",
                "Livraison offerte à partir de $formattedTargetAmount d'achat"
            );
            return '
                    <div class="alert alert-info">
                        <h3>
                            <span class="fa fa-gift"></span> 
                            ' . $freeShippingInviteText . '
                        </h3>
                        <progress value="' . $cartTotal . '" max="' . $freeShippingTargetAmount . '"></progress>
                        <p>
                            Ajoutez encore <strong>' . currency($missingAmount / 100) . '</strong> à votre panier pour en bénéficier !
                        </p>
                    </div>
                ';
        }
    }

    /**
     * @throws PropelException
     */
    public static function cartNeedsShipping(Cart $cart): bool
    {
        $stockItems = StockQuery::create()->findByCartId($cart->getId());
        /** @var Stock $stockItem */
        foreach ($stockItems as $stockItem) {
            $type = $stockItem->getArticle()->getType();
            if ($type->isPhysical()) {
                return true;
            }
        }
        return false;
    }
}