<?php

namespace Biblys\Legacy;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
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

    /**
     * @throws PropelException
     * @throws Exception
     */
    public static function getSpecialOffersNotice(
        CurrentSite  $currentSite,
        UrlGenerator $urlGenerator,
        Cart         $cart,
    ): string
    {
        $specialOffers = SpecialOfferQuery::create()
            ->filterBySite($currentSite->getSite())
            ->find();

        if (!$specialOffers) {
            return "";
        }

        $notice = "";
        foreach ($specialOffers as $specialOffer) {
            $notice .= self::_buildSpecialOfferNotice(
                $specialOffer,
                $urlGenerator,
                $cart
            );
        }

        return $notice;
    }

    /**
     * @throws Exception
     */
    private static function _buildSpecialOfferNotice(
        SpecialOffer $specialOffer,
        UrlGenerator $urlGenerator,
        Cart         $cart
    ): string
    {
        $targetQuantity = $specialOffer->getTargetQuantity();
        $freeArticle = $specialOffer->getFreeArticle();
        $targetCollection = $specialOffer->getTargetCollection();

        if (!$targetCollection || !$freeArticle) {
            return "";
        }

        if ($specialOffer->getStartDate() > new DateTime()) {
            return "";
        }

        if ($specialOffer->getEndDate() < new DateTime()) {
            return "";
        }

        $am = new ArticleManager();

        $copies = StockQuery::create()->filterByCartId($cart->getId())->find();

        // Count copies in offer's collection
        $copiesInCollection = array_reduce($copies->getArrayCopy(), function ($total, $copy) use ($targetCollection) {
            /** @var Article $article */
            $article = $copy->getArticle();
            if ($article->getCollectionId() === $targetCollection->getId()) {
                $total++;
            }
            return $total;
        }, 0);

        $missing = $targetQuantity - $copiesInCollection;
        /** @var \Article $freeArticleEntity */
        $freeArticleEntity = $am->getById($freeArticle->getId());
        $sentence = 'Ajoutez encore ' . $missing . ' titre' . s($missing) . ' de la collection
            à votre panier pour en profiter.';
        $style = ' style="opacity: .5"';
        $cartButton = '<button class="btn btn-success" disabled>J‘en profite !</button>';

        if ($missing <= 0) {
            $style = null;
            $sentence = 'Si vous ne souhaitez pas bénéficier de l\'offre, vous pourrez
                le préciser dans le champ Commentaires de la page suivante.';
            $price = 'Offert';
            $cartButtonUrl = $urlGenerator->generate(
                "cart_add_article", ["articleId" => $freeArticle->getId()]
            );
            $cartButton = '<form method="post" action="'.$cartButtonUrl.'">';
            $cartButton .= '<button type="submit" class="btn btn-success">J‘en profite !</button>';
            $cartButton .= '</form>';
        }

        $cover = null;
        if ($freeArticleEntity->hasCover()) {
            $cover = $freeArticleEntity->getCoverTag(['size' => 'h60', 'rel' => 'lightbox', 'class' => 'cover']);
        }


        return '
            <tr' . $style . '>
                <td>Offre<br>spéciale</td>
                <td>' . $cover . '</td>
                <td>
                    <p>
                    
                        <a href="/' . $freeArticleEntity->get('url') . '">' . $freeArticleEntity->get('title') . '</a><br />
                        de ' . authors($freeArticleEntity->get('authors')) . '<br />
                        coll. ' . $freeArticleEntity->get('collection')->get('name') . ' ' . numero($freeArticleEntity->get('number')) . '<br />
                    </p>
                    <p>
                        <strong>
                            Offert pour ' . $targetQuantity . ' titres de la
                            collection ' . $targetCollection->getName() . ' achetés&nbsp;!<br />
                            <small>' . $sentence . '</small>
                        </strong>
                    </p>
                </td>
                <td class="right">
                    Offert
                </td>
                <td class="center">
                    ' . $cartButton . '
                </td>
            </tr>
        ';
    }
}