<?php

namespace Biblys\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Model\Article;
use Model\ArticleCategoryQuery;
use Model\ArticleQuery;
use Model\LinkQuery;
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
                                    aria-label="Ajouter au panier"
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
}