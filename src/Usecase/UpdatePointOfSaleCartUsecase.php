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

use Biblys\Exception\CannotFindCustomerException;
use Model\CartQuery;
use Model\CustomerQuery;
use Propel\Runtime\Exception\PropelException;

class UpdatePointOfSaleCartUsecase
{
    /**
     * Renomme un panier caisse et/ou change le client qui lui est associé.
     *
     * @param string|null $title Nouveau titre du panier, ou null pour ne pas le modifier.
     * @param int|null $customerId Nouveau client, 0 pour désassocier le client, ou null pour ne pas le modifier.
     *
     * @throws CannotFindCustomerException si le client indiqué n'existe pas
     * @throws PropelException
     */
    public function execute(int $cartId, ?string $title = null, ?int $customerId = null): void
    {
        $cart = CartQuery::create()->findPk($cartId);

        if ($title !== null) {
            $cart->setTitle($title);
        }

        if ($customerId !== null) {
            if ($customerId === 0) {
                $cart->setCustomerId(null);
            } else {
                $customer = CustomerQuery::create()->findPk($customerId);
                if (!$customer) {
                    throw new CannotFindCustomerException("Client n° $customerId introuvable.");
                }
                $cart->setCustomerId($customerId);
            }
        }

        $cart->save();
    }
}
