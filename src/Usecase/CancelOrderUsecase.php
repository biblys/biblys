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

use Model\OrderQuery;
use OrderManager;
use Propel\Runtime\Exception\PropelException;
use Repository\PaymentRepository;

class CancelOrderUsecase
{
    public function __construct(private readonly PaymentRepository $paymentRepository)
    {
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     */
    public function execute(int $orderId): void
    {
        $order = OrderQuery::create()->findPk($orderId);

        $executedPayments = $this->paymentRepository->findExecutedByOrder($order);
        $totalPaid = array_reduce(
            $executedPayments,
            fn($carry, $payment) => $carry + $payment->getAmount(),
            0
        );
        if ($totalPaid > 0) {
            throw new BusinessRuleException(
                "Cette commande ne peut pas être annulée car elle a été payée (total : " .
                currency($totalPaid / 100) . "). Effectuez d'abord un remboursement."
            );
        }

        $om = new OrderManager();
        $legacyOrder = $om->getById($orderId);
        $om->cancel($legacyOrder);
    }
}
