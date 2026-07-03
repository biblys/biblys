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

use Biblys\Exception\CannotAddStockItemToCartException;
use CartManager;
use Exception;
use Propel\Runtime\Exception\PropelException;

class AddStockItemToPointOfSaleCartUsecase
{
    /**
     * Ajoute un exemplaire (stock item) à un panier caisse et met à jour ses totaux.
     *
     * @throws CannotAddStockItemToCartException si l'exemplaire est déjà réservé dans un panier caisse
     * @throws PropelException
     * @throws Exception si l'exemplaire est introuvable ou indisponible
     */
    public function execute(int $cartId, int $stockItemId): void
    {
        $cm = new CartManager();
        $cart = $cm->getById($cartId);
        $cm->addStock($cart, $stockItemId);
        $cm->updateFromStock($cart);
    }
}
