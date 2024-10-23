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

use Biblys\Service\CurrentUser;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;

class UpdateStockItemPriceUsecase
{
    private CurrentUser $currentUser;

    public function __construct(CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function execute(Stock $stockItem, int $newPrice): void
    {
        if (!$stockItem->getArticle()->isPriceEditable()) {
            throw new BusinessRuleException("Le prix de cet article n'est pas libre.");
        }

        if ($newPrice < $stockItem->getArticle()->getPrice()) {
            throw new BusinessRuleException("Le prix doit être supérieur ou égal à " . currency($stockItem->getArticle()->getPrice() / 100));
        }

        if (!$this->currentUser->hasStockItemInCart($stockItem)) {
            throw new BusinessRuleException("Cet exemplaire n'est pas dans votre panier.");
        }

        $stockItem->setSellingPrice($newPrice);
        $stockItem->save();

        $amount = 0;
        $count = 0;
        $currentUserCart = $this->currentUser->getCart();
        $cartStockItems = $currentUserCart->getStocks();
        /** @var Stock $cartStockItem */
        foreach ($cartStockItems as $cartStockItem) {
            $amount += $cartStockItem->getSellingPrice();
            $count += 1;
        }
        $currentUserCart->setAmount($amount);
        $currentUserCart->setCount($count);
        $currentUserCart->save();
    }
}