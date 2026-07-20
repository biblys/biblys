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

use Biblys\Exception\CannotRemoveStockItemFromCartException;
use CartManager;
use Exception;
use Propel\Runtime\Exception\PropelException;
use StockManager;

class RemoveStockItemFromPointOfSaleCartUsecase
{
    /**
     * Retire un exemplaire (stock item) d'un panier caisse et le remet en stock.
     *
     * @throws CannotRemoveStockItemFromCartException si l'exemplaire n'est pas présent dans ce panier
     * @throws PropelException
     * @throws Exception
     */
    public function execute(int $cartId, int $stockItemId): void
    {
        $sm = new StockManager();
        $stock = $sm->getById($stockItemId);
        if (!$stock || (int) $stock->get('cart_id') !== $cartId) {
            throw new CannotRemoveStockItemFromCartException(
                "L'exemplaire n° $stockItemId n'est pas présent dans ce panier."
            );
        }

        $cm = new CartManager();
        $cart = $cm->getById($cartId);
        $cm->removeStock($stock);
        $cm->updateFromStock($cart);
    }
}
