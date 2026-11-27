<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

use Biblys\Exception\CannotUpdateStockItemPriceException;
use CartManager;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;

class UpdatePointOfSaleCartItemPriceUsecase
{
    /**
     * Modifie le prix d'un exemplaire d'occasion d'un panier caisse en mémorisant son prix d'origine.
     *
     * @return Stock l'exemplaire mis à jour
     * @throws CannotUpdateStockItemPriceException si l'exemplaire est neuf, absent du panier ou si le prix est négatif
     * @throws PropelException
     */
    public function execute(int $cartId, int $stockItemId, int $newPrice): Stock
    {
        $stockItem = StockQuery::create()->filterByCartId($cartId)->findPk($stockItemId);
        if (!$stockItem) {
            throw new CannotUpdateStockItemPriceException(
                "L'exemplaire n° $stockItemId n'est pas présent dans ce panier."
            );
        }

        if ($stockItem->getCondition() === Stock::CONDITION_NEW) {
            throw new CannotUpdateStockItemPriceException(
                "Le prix d'un exemplaire neuf ne peut pas être modifié."
            );
        }

        if ($newPrice < 0) {
            throw new CannotUpdateStockItemPriceException("Le prix ne peut pas être négatif.");
        }

        $originalPrice = $stockItem->getSellingPriceSaved() ?? $stockItem->getSellingPrice();
        $isBackToOriginalPrice = $newPrice === $originalPrice;

        $stockItem->setSellingPriceSaved($isBackToOriginalPrice ? null : $originalPrice);
        $stockItem->setSellingPrice($newPrice);
        $stockItem->save();

        $cm = new CartManager();
        $cm->updateFromStock($cm->getById($cartId));

        return $stockItem;
    }
}
