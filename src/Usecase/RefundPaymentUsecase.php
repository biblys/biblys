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

class RefundPaymentUsecase
{
    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function execute(Payment $payment): Payment
    {
        if ($payment->getRefundedAt() !== null) {
            throw new BusinessRuleException("Ce paiement a déjà été remboursé.");
        }

        if ($payment->getAmount() <= 0) {
            throw new BusinessRuleException("Impossible de rembourser un paiement négatif.");
        }

        $now = new DateTime();

        $payment->setRefundedAt($now);
        $payment->save();

        $refund = new Payment();
        $refund->setOrderId($payment->getOrderId());
        $refund->setMode($payment->getMode());
        $refund->setAmount(-$payment->getAmount());
        $refund->setOriginalId($payment->getId());
        $refund->setExecuted($now);
        $refund->save();

        return $refund;
    }
}
