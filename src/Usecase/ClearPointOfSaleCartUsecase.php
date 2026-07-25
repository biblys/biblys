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

use CartManager;
use Exception;
use Propel\Runtime\Exception\PropelException;

class ClearPointOfSaleCartUsecase
{
    /**
     * Retire tous les exemplaires d'un panier caisse, les remet en stock,
     * et réinitialise le client, le titre et la date du panier.
     *
     * @throws PropelException
     * @throws Exception
     */
    public function execute(int $cartId): void
    {
        $cm = new CartManager();
        $cart = $cm->getById($cartId);

        foreach ($cm->getStock($cart) as $stock) {
            $cm->removeStock($stock);
        }

        $cart->set('customer_id', '');
        $cart->set('cart_title', '');
        $cart->set('cart_date', '');
        $cm->updateFromStock($cart);
    }
}
