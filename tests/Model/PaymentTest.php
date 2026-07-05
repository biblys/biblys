<?php
/*
 * Copyright (C) 2024 Clément Latzarus
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


namespace Model;

use Biblys\Test\ModelFactory;
use DateTime;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class PaymentTest extends TestCase
{

    /** isExecuted */

    /**
     * @throws PropelException
     */
    public function testIsExecutedReturnsFalseWhenNoExecutionDate(): void
    {
        // given
        $payment = new Payment();

        // when
        $result = $payment->isExecuted();

        // then
        $this->assertFalse($result);
    }

    /**
     * @throws PropelException
     */
    public function testIsExecutedReturnsTrueWhenExecutionDateIsSet(): void
    {
        // given
        $payment = new Payment();
        $payment->setExecuted(new DateTime());

        // when
        $result = $payment->isExecuted();

        // then
        $this->assertTrue($result);
    }

    /** preInsert */

    /**
     * @throws PropelException
     */
    public function testPreInsertBuildsSignatureChain(): void
    {
        // given
        $order = ModelFactory::createOrder();

        // when — trois paiements créés successivement
        $first = ModelFactory::createPayment($order, 1500);
        $second = ModelFactory::createPayment($order, 900);
        $third = ModelFactory::createPayment($order, 300);

        // then — chaque hash est calculable à partir du précédent
        $this->assertSame(PaymentHash::CURRENT_VERSION, $third->getHashVersion());
        $this->assertSame(
            PaymentHash::compute(
                PaymentHash::CURRENT_VERSION,
                $second->getAmount(),
                $second->getCreatedAt(),
                $second->getOrderId(),
                $first->getHash()
            ),
            $second->getHash()
        );
        $this->assertSame(
            PaymentHash::compute(
                PaymentHash::CURRENT_VERSION,
                $third->getAmount(),
                $third->getCreatedAt(),
                $third->getOrderId(),
                $second->getHash()
            ),
            $third->getHash()
        );
    }
}
