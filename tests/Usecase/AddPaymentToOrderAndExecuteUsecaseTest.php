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
use Biblys\Test\ModelFactory;
use DateTime;
use Model\Payment;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AddPaymentToOrderAndExecuteUsecaseTest extends TestCase
{
    /**
     * @throws BusinessRuleException
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws UnreachableExternalServiceException
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testAddPaymentToOrder(): void
    {
        // given
        $order = ModelFactory::createOrder(amount: 999, amountToBePaid: 999);
        $payment = ModelFactory::createPayment(amount: 499, mode: Payment::MODE_CARD);
        $markOrderAsPaidUsecase = $this->createMock(MarkOrderAsPaidUsecase::class);
        $markOrderAsPaidUsecase->expects($this->never())->method('execute');

        // when
        $usecase = new AddPaymentToOrderAndExecuteUsecase($markOrderAsPaidUsecase);
        $usecase->execute($order, $payment);

        // then
        $payment->reload();
        $this->assertEquals($payment->getOrder(), $order);
        $this->assertTrue($payment->isExecuted());
        $order->reload();
        $this->assertEquals(500, $order->getAmountTobepaid());
        $this->assertEquals(499, $order->getPaymentCard());
        $this->assertEquals(Payment::MODE_CARD, $order->getPaymentMode());
    }

    /**
     * @throws BusinessRuleException
     * @throws Exception
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws UnreachableExternalServiceException
     */
    public function testAddPaymentToOrderAndMarkingAsPaid(): void
    {
        // given
        $order = ModelFactory::createOrder(amount: 999, amountToBePaid: 999);
        $payment = ModelFactory::createPayment(
            amount: 999,
            mode: Payment::MODE_CASH,
        );
        $markOrderAsPaidUsecase = $this->createMock(MarkOrderAsPaidUsecase::class);
        $markOrderAsPaidUsecase->expects($this->once())->method('execute')->with(
            $order,
            payedAmountInCents: $payment->getAmount(),
            paymentMode: $payment->getMode()
        );

        // when
        $usecase = new AddPaymentToOrderAndExecuteUsecase($markOrderAsPaidUsecase);
        $usecase->execute($order, $payment);

        // then
        $payment->reload();
        $this->assertEquals($payment->getOrder(), $order);
        $this->assertTrue($payment->isExecuted());
        $order->reload();
        $this->assertEquals(0, $order->getAmountTobepaid());
        $this->assertEquals(999, $order->getPaymentCash());
        $this->assertEquals(Payment::MODE_CASH, $order->getPaymentMode());
    }

    /**
     * @throws BusinessRuleException
     * @throws Exception
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws UnreachableExternalServiceException
     */
    public function testAddPaymentToOrderWhenOrderAlreadyHasPayment(): void
    {
        // given
        $order = ModelFactory::createOrder(amount: 999, amountToBePaid: 499);
        ModelFactory::createPayment(order: $order, amount: 500, mode: Payment::MODE_CASH, executedAt: new DateTime());
        $newPayment = ModelFactory::createPayment(
            amount: 499,
            mode: Payment::MODE_CHECK,
        );
        $markOrderAsPaidUsecase = $this->createMock(MarkOrderAsPaidUsecase::class);
        $markOrderAsPaidUsecase->expects($this->once())->method('execute')->with(
            $order,
            payedAmountInCents: $newPayment->getAmount(),
            paymentMode: $newPayment->getMode()
        );

        // when
        $usecase = new AddPaymentToOrderAndExecuteUsecase($markOrderAsPaidUsecase);
        $usecase->execute($order, $newPayment);

        // then
        $newPayment->reload();
        $this->assertEquals($newPayment->getOrder(), $order);
        $this->assertTrue($newPayment->isExecuted());
        $order->reload();
        $this->assertEquals(0, $order->getAmountTobepaid());
        $this->assertEquals(499, $order->getPaymentCheque());
        $this->assertEquals(Payment::MODE_CHECK, $order->getPaymentMode());
    }

    /**
     * @throws BusinessRuleException
     * @throws Exception
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws UnreachableExternalServiceException
     */
    public function testAddPaymentToOrderClampsAmountToBePaidAtZero(): void
    {
        // given
        $order = ModelFactory::createOrder(amount: 999, amountToBePaid: 100);
        $payment = ModelFactory::createPayment(amount: 500, mode: Payment::MODE_TRANSFER);
        $markOrderAsPaidUsecase = $this->createMock(MarkOrderAsPaidUsecase::class);
        $markOrderAsPaidUsecase->expects($this->once())->method('execute');

        // when
        $usecase = new AddPaymentToOrderAndExecuteUsecase($markOrderAsPaidUsecase);
        $usecase->execute($order, $payment);

        // then
        $order->reload();
        $this->assertEquals(0, $order->getAmountTobepaid());
        $this->assertEquals(500, $order->getPaymentTransfer());
        $this->assertEquals(Payment::MODE_TRANSFER, $order->getPaymentMode());
    }
}
