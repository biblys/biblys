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

namespace Repository;

use Model\Order;
use Model\Payment;
use Model\PaymentQuery;
use Propel\Runtime\Exception\PropelException;

class PaymentRepository
{
    /**
     * @throws PropelException
     */
    public function findById(int $id): ?Payment
    {
        return PaymentQuery::create()->findPk($id);
    }

    /**
     * @throws PropelException
     */
    public function findByOrder(Order $order): array
    {
        return PaymentQuery::create()->filterByOrder($order)->find()->getArrayCopy();
    }

    /**
     * @throws PropelException
     */
    public function save(Payment $payment): void
    {
        $payment->save();
    }
}
