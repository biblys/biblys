<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace Biblys\Service;

use Biblys\Exception\CannotFindPayableOrderException;
use Model\Order;
use Model\OrderQuery;
use Propel\Runtime\Exception\PropelException;

class PaymentService
{
    /**
     * @throws CannotFindPayableOrderException
     * @throws PropelException
     */
    public function getPayableOrderBySlug(string $slug): Order
    {
        $order = OrderQuery::create()->findOneBySlug($slug);

        if ($order === null) {
            throw new CannotFindPayableOrderException("Commande inconnue");
        }

        if ($order->isPaid()) {
            throw new CannotFindPayableOrderException("Commande déjà payée");
        }

        if ($order->isCancelled()) {
            throw new CannotFindPayableOrderException("Commande annulée");
        }

        return $order;
    }
}