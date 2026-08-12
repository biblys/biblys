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
use Model\Map\OrderTableMap;
use Model\Payment;
use OrderManager;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;

class CreatePointOfSaleOrderUsecase
{
    /**
     * Crée une commande à partir d'un panier caisse, enregistre les paiements et vide le panier.
     *
     * @throws PropelException
     */
    public function execute(
        int  $cartId,
        ?int $sellerId,
        ?int $customerId,
        int  $cashAmount,
        int  $chequeAmount,
        int  $cardAmount,
        int  $amountToBePaid,
        int  $paymentLeft,
    ): int
    {
        $cm = new CartManager();
        $cart = $cm->getById($cartId);

        $con = Propel::getWriteConnection(OrderTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $om = new OrderManager();
            $order = $om->create();

            if ($sellerId) {
                $order->set('seller_id', $sellerId);
            }
            if ($customerId) {
                $order->set('customer_id', $customerId);
            }

            $order->set('order_type', 'shop');
            $order->set('order_payment_cash', $cashAmount);
            $order->set('order_payment_cheque', $chequeAmount);
            $order->set('order_payment_card', $cardAmount);
            $order->set('order_amount_tobepaid', $amountToBePaid);
            $order->set('order_payment_left', $paymentLeft);
            $order->set('order_payment_date', date('Y-m-d H:i:s'));

            $om->hydrateFromCart($order, $cart);
            $order = $om->update($order);

            $paymentsUsecase = new RecordPointOfSalePaymentsUsecase();
            $paymentsUsecase->execute((int) $order->get('order_id'), [
                Payment::MODE_CASH  => $cashAmount,
                Payment::MODE_CHECK => $chequeAmount,
                Payment::MODE_CARD  => $cardAmount,
            ]);

            $cm->vacuum($cart);
            $cm->delete($cart);

            $con->commit();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }

        return (int) $order->get('order_id');
    }
}
