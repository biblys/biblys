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

use Biblys\Exception\CannotDeleteEntityWithImage;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class StockTest extends TestCase
{

    /** isLost */

    /**
     * @throws PropelException
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

    /**
     * @throws PropelException
     */
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

    /** delete */

    /**
     * @throws PropelException
     */
    public function testDeleteSucceedsIfNoImage()
    {
        // given
        $stockItem = ModelFactory::createStockItem();

        // when
        $stockItem->delete();

        // then
        $this->assertFalse(StockQuery::create()->filterById($stockItem->getId())->exists());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteFailsIfImageExists()
    {
        // given
        $stockItem = ModelFactory::createStockItem();
        ModelFactory::createImage(stockItem: $stockItem);

        // when
        $exception = Helpers::runAndCatchException(fn() => $stockItem->delete());

        // then
        $this->assertInstanceOf(CannotDeleteEntityWithImage::class, $exception);
        $this->assertTrue(StockQuery::create()->filterById($stockItem->getId())->exists());
    }
}
