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

use DateTime;
use PHPUnit\Framework\TestCase;
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
}
