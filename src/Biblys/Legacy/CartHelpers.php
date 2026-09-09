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


namespace Biblys\Legacy;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\SpecialOffers\SpecialOfferEvaluator;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Model\Article;
use Model\ArticleCategoryQuery;
use Model\ArticleQuery;
use Model\Cart;
use Model\LinkQuery;
use Model\SpecialOffer;
use Model\SpecialOfferQuery;
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
                <h2>' . $articleCategory->getName() . '</h3>
                <div class="cart-suggestions">
            ';
        /** @var Article $article */
        foreach ($articles as $article) {
            $cartUrl = $urlGenerator->generate("cart_add_article", ["articleId" => $article->getId()]);
            $coverHtml = "";
            $articleCoverImageUrl = $imagesService->getImageUrlFor($article);
            if ($articleCoverImageUrl) {
                $coverHtml = '
                        <div class="cart-suggestions_article_cover">
                            <a href="' . $urlGenerator->generate("article_show", ["slug" => $article->getUrl()]) . '">
                                <img 
                                    src="' . $articleCoverImageUrl . '" 
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
                                    aria-label="Ajouter ' . $article->getTitle() . ' au panier"
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
                        <h2>
                            <span class="fa fa-gift"></span> 
                            ' . $freeShippingInviteText . '
                        </h2>
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

    /**
     * @throws PropelException
     * @throws Exception
     */
    public static function getSpecialOffersNotice(
        CurrentSite     $currentSite,
        UrlGenerator    $urlGenerator,
        ImagesService   $imagesService,
        TemplateService $templateService,
        Cart            $cart,
    ): string
    {
        $specialOffers = SpecialOfferQuery::create()
            ->find();

        if (!$specialOffers) {
            return "";
        }

        $notice = "";
        foreach ($specialOffers as $specialOffer) {
            $notice .= self::_buildSpecialOfferNotice(
                $specialOffer,
                $urlGenerator,
                $imagesService,
                $templateService,
                $cart,
            );
        }

        return $notice;
    }

    /**
     * @throws Exception
     */
    private static function _buildSpecialOfferNotice(
        SpecialOffer    $specialOffer,
        UrlGenerator    $urlGenerator,
        ImagesService   $imagesService,
        TemplateService $templateService,
        Cart            $cart,
    ): string
    {
        $freeArticle = $specialOffer->getFreeArticle();

        if (!$freeArticle) {
            return "";
        }

        if ($specialOffer->getStartDate() > new DateTime()) {
            return "";
        }

        if ($specialOffer->getEndDate() < new DateTime()) {
            return "";
        }

        $evaluation = SpecialOfferEvaluator::evaluate($specialOffer, $cart);
        $conditionItems = [];

        if ($evaluation->quantity !== null) {
            $targetCollection = $specialOffer->getTargetCollection();
            $collectionUrl = $urlGenerator->generate(
                "collection_show", ["slug" => $targetCollection->getUrl()]
            );
            $collectionLink = '<a href="' . $collectionUrl . '">' . $targetCollection->getName() . '</a>';
            $target = $evaluation->quantity->target;

            if ($evaluation->quantity->isMet) {
                $conditionItems[] = [
                    "met" => true,
                    "label" => $target . ' titre' . s($target) . ' de la collection ' .
                        $collectionLink . ' acheté' . s($target),
                ];
            } else {
                $missingItems = $target - $evaluation->quantity->current;
                $conditionItems[] = [
                    "met" => false,
                    "label" => 'Ajoutez encore ' . $missingItems . ' titre' . s($missingItems) .
                        ' de la collection ' . $collectionLink,
                ];
            }
        }

        if ($evaluation->amount !== null) {
            $target = $evaluation->amount->target;

            if ($evaluation->amount->isMet) {
                $conditionItems[] = [
                    "met" => true,
                    "label" => currency($target / 100) . ' d’achat atteints',
                ];
            } else {
                $missingAmount = $target - $evaluation->amount->current;
                $conditionItems[] = [
                    "met" => false,
                    "label" => 'Ajoutez encore ' . currency($missingAmount / 100) .
                        ' à votre panier <small>(minimum ' . currency($target / 100) . ')</small>',
                ];
            }
        }

        if (!$conditionItems) {
            return "";
        }

        $amountConditionNote = "";
        if ($evaluation->amount !== null) {
            $amountConditionNote = '<p class="SpecialOfferNotice-note"><small>' .
                'Seuls les articles nécessitant une expédition comptent dans ce montant.' .
                '</small></p>';
        }

        /** @var \Article $freeArticleEntity */
        $am = new ArticleManager();
        $freeArticleEntity = $am->getById($freeArticle->getId());

        $freeArticleIsInCart = StockQuery::create()
            ->filterByCart($cart)->findOneByArticleId($freeArticle->getId());

        if ($freeArticleIsInCart) {
            $statusLine = '<span class="text-success"><span class="fa fa-check-circle"></span> Vous bénéficiez de l’offre.</span>';
            $cartButtonUrl = null;
        } elseif ($evaluation->isMet()) {
            $statusLine = '<span class="text-success"><span class="fa fa-check-circle"></span> Vous pouvez bénéficier de l’offre.</span>';
            $cartButtonUrl = $urlGenerator->generate(
                "cart_add_article", ["articleId" => $freeArticle->getId()]
            );
        } else {
            $statusLine = '';
            $cartButtonUrl = null;
        }

        $cover = null;
        if ($imagesService->imageExistsFor($freeArticle)) {
            $cover = $templateService->render("AppBundle:Article:_cover.html.twig", [
                    "article" => $freeArticle,
                    "width" => 256,
                    "class" => "cover",
                    "rel" => "lightbox",
                ]
            );
        }

        return $templateService->render("AppBundle:Cart:_special-offer-notice.html.twig", [
            "specialOffer" => $specialOffer,
            "freeArticleEntity" => $freeArticleEntity,
            "freeArticleAuthors" => authors($freeArticleEntity->get('authors')),
            "freeArticleNumero" => numero($freeArticleEntity->get('number')),
            "conditionItems" => $conditionItems,
            "amountConditionNote" => $amountConditionNote,
            "cover" => $cover,
            "statusLine" => $statusLine,
            "cartButtonUrl" => $cartButtonUrl,
            "alreadyInCart" => (bool) $freeArticleIsInCart,
        ]);
    }
}