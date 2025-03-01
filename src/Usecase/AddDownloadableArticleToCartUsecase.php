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


namespace Usecase;

use DateTime;
use Model\Article;
use Model\Cart;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class AddDownloadableArticleToCartUsecase
{
    public function __construct()
    {
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function execute(Article $article, Cart $cart): void
    {
        if (!$article->getType()->isDownloadable()) {
            throw new BusinessRuleException(
                "L'article {$article->getTitle()} n'a pas pu être ajouté au panier car il n'est pas téléchargeable."
            );
        }

        if ($article->getPubdate() > new DateTime() && !$article->isPreorder()) {
            throw new BusinessRuleException(
                "L'article {$article->getTitle()} n'a pas pu être ajouté au panier car il n'est pas encore disponible."
            );
        }

        if ($article->getAvailabilityDilicom() === 6) {
            throw new BusinessRuleException(
                "L'article {$article->getTitle()} n'a pas pu être ajouté au panier car il n'est plus disponible."
            );
        }

        $stockItem = StockQuery::create()
            ->filterByArticle($article)
            ->filterBySellingDate(null, Criteria::ISNULL)
            ->filterByLostDate(null, Criteria::ISNULL)
            ->filterByCartDate(null, Criteria::ISNULL)
            ->filterByReturnDate(null, Criteria::ISNULL)
            ->findOne();

        if ($stockItem === null) {
            $stockItem = new Stock();
            $stockItem->setArticle($article);
            $stockItem->setSellingPrice($article->getPrice());
        }

        $stockItem->setCart($cart);
        $stockItem->setCartDate(new DateTime());
        $stockItem->save();

        $cartStockItems = $cart->getStocks();
        $cartAmount = 0;
        $cartCount = 0;
        foreach ($cartStockItems as $cartStockItem) {
            $cartAmount += $cartStockItem->getSellingPrice();
            $cartCount++;
        }
        $cart->setAmount($cartAmount);
        $cart->setCount($cartCount);
        $cart->save();
    }
}