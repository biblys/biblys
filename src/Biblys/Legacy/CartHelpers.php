<?php

namespace Biblys\Legacy;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use CollectionManager;
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

    /**
     * @param CurrentSite $currentSite
     * @param Cart $cart
     * @param ArticleManager $am
     * @param CollectionManager $com
     * @param int $physicalTotalPrice
     * @return string
     * @throws PropelException
     */
    public static function getSpecialOfferNotice(
        CurrentSite       $currentSite,
        Cart              $cart,
    ): string
    {
        $special_offer_amount = $currentSite->getOption('special_offer_amount');
        $special_offer_article = $currentSite->getOption('special_offer_article');
        $special_offer_collection = $currentSite->getOption('special_offer_collection');

        // Special offer: article for amount of articles in collection
        if (!$special_offer_collection || !$special_offer_amount || !$special_offer_article) {
            return "";
        }

        $am = new ArticleManager();
        $com = new CollectionManager();

        $copies = StockQuery::create()->filterByCartId($cart->getId())->find();

        // Count copies in offer's collection
        $copiesInCollection = array_reduce($copies->getArrayCopy(), function ($total, $copy) use ($special_offer_collection) {
            /** @var Article $article */
            $article = $copy->getArticle();
            if ($article->getCollectionId() === (int) $special_offer_collection) {
                $total++;
            }
            return $total;
        }, 0);

        $price = null;
        $missing = $special_offer_amount - $copiesInCollection;
        /** @var \Article $fa */
        $fa = $am->getById($special_offer_article);
        $sentence = 'Ajoutez encore ' . $missing . ' titre' . s($missing) . ' de la collection<br/>
            à votre panier pour en profiter&nbsp;!';
        $style = ' style="opacity: .5"';
        $offerCollection = $com->getById($special_offer_collection);

        if ($missing <= 0) {
            $style = null;
            $sentence = 'Si vous ne souhaitez pas bénéficier de l\'offre, vous pourrez
                le préciser dans le champ Commentaires de la page suivante.';
            $price = 'Offert';
        }

        $cover = null;
        if ($fa->hasCover()) {
            $cover = $fa->getCoverTag(['size' => 'h60', 'rel' => 'lightbox', 'class' => 'cover']);
        }

        return '
            <tr' . $style . '>
                <td>Offre<br>spéciale</td>
                <td>' . $cover . '</td>
                <td>
                    <a href="/' . $fa->get('url') . '">' . $fa->get('title') . '</a><br />
                    de ' . authors($fa->get('authors')) . '<br />
                    coll. ' . $fa->get('collection')->get('name') . ' ' . numero($fa->get('number')) . '<br />
                    <p>
                        <strong>
                            Offert pour ' . $special_offer_amount . ' titres de la
                            collection ' . $offerCollection->get('name') . ' achetés&nbsp;!
                            <small>(hors numérique)</small><br>
                            <small>' . $sentence . '</small>
                        </strong>
                    </p>
                </td>
                <td class="right">
                    ' . $price . '
                </td>
                <td class="center">
                </td>
            </tr>
        ';
    }
}