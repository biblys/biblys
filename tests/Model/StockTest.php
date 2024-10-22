<?php

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
