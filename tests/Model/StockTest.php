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

class StockTest extends TestCase
{

    /**
     * # isLost
     */

    public function testIsLostReturnsFalse(): void
    {
        // given
        $stockItem = new Stock();

        // when
        $isLost = $stockItem->isLost();

        // then
        $this->assertFalse($isLost);
    }

    public function testIsLostReturnsTrue(): void
    {
        // given
        $stockItem = new Stock();
        $stockItem->setLostDate(new DateTime());

        // when
        $isLost = $stockItem->isLost();

        // then
        $this->assertTrue($isLost);
    }

    /**
     * # isWatermarked
     */

    public function testIsWatermarkedReturnsTrue(): void
    {
        // given
        $libraryItem = new Stock();
        $libraryItem->setLemoninkTransactionId("abcd1234");
        $libraryItem->setLemoninkTransactionToken("5678efgh");

        // when
        $isWatermarked = $libraryItem->isWatermarked();

        // then
        $this->assertTrue($isWatermarked);
    }

    public function testIsWatermarkedReturnsFalseIfNoId(): void
    {
        // given
        $libraryItem = new Stock();
        $libraryItem->setLemoninkTransactionId(null);
        $libraryItem->setLemoninkTransactionToken("5678efgh");

        // when
        $isWatermarked = $libraryItem->isWatermarked();

        // then
        $this->assertFalse($isWatermarked);
    }

    public function testIsWatermarkedReturnsFalseIfNoToken(): void
    {
        // given
        $libraryItem = new Stock();
        $libraryItem->setLemoninkTransactionId("abcd1234");
        $libraryItem->setLemoninkTransactionToken(null);

        // when
        $isWatermarked = $libraryItem->isWatermarked();

        // then
        $this->assertFalse($isWatermarked);
    }
}
