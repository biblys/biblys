<?php

namespace Model;

use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{

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
