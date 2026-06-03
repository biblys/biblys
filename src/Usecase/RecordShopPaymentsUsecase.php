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

use DateTime;
use Model\Payment;
use Propel\Runtime\Exception\PropelException;

class RecordShopPaymentsUsecase
{
    /**
     * @param int $orderId
     * @param array<string, int> $paymentsByMode [mode => amountInCents]
     * @throws PropelException
     */
    public function execute(int $orderId, array $paymentsByMode): void
    {
        foreach ($paymentsByMode as $mode => $amount) {
            if ($amount <= 0) {
                continue;
            }

            $payment = new Payment();
            $payment->setOrderId($orderId);
            $payment->setMode($mode);
            $payment->setAmount($amount);
            $payment->setExecuted(new DateTime());
            $payment->save();
        }
    }
}
