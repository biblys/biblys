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

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Exception\UnreachableExternalServiceException;
use DateTime;
use Model\Order;
use Model\Payment;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AddPaymentToOrderAndExecuteUsecase
{
    public function __construct(private MarkOrderAsPaidUsecase $markOrderAsPaidUsecase)
    {
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws UnreachableExternalServiceException
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(Order $order, Payment $payment): void
    {
        $payment->setOrder($order);
        $payment->setExecuted(new DateTime());
        $payment->save();

        $remainingAmount = $order->getAmountTobepaid() - $payment->getAmount();
        $remainingAmount = $remainingAmount > 0 ? $remainingAmount : 0;
        $order->setAmountTobepaid($remainingAmount);

        switch ($payment->getMode()) {
            case Payment::MODE_CASH:
                $order->setPaymentCash($order->getPaymentCash() + $payment->getAmount());
                break;
            case Payment::MODE_CHECK:
                $order->setPaymentCheque($order->getPaymentCheque() + $payment->getAmount());
                break;
            case Payment::MODE_CARD:
                $order->setPaymentCard($order->getPaymentCard() + $payment->getAmount());
                break;
            case Payment::MODE_TRANSFER:
                $order->setPaymentTransfer($order->getPaymentTransfer() + $payment->getAmount());
                break;
            case Payment::MODE_PAYPAL:
                $order->setPaymentPaypal($order->getPaymentPaypal() + $payment->getAmount());
                break;
            case Payment::MODE_PAYPLUG:
                $order->setPaymentPayplug($order->getPaymentPayplug() + $payment->getAmount());
                break;
        }

        $order->setPaymentMode($payment->getMode());
        $order->save();

        if ($remainingAmount === 0) {
            $this->markOrderAsPaidUsecase->execute(
                $order,
                payedAmountInCents: $payment->getAmount(),
                paymentMode: $payment->getMode()
            );
        }
    }
}