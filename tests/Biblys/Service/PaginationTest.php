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

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../../setUp.php";

class PaginationTest extends TestCase
{

    /**
     * @var Pagination
     */
    private $pagination;

    public function setUp(): void
    {
        $this->pagination = new Pagination(1, 25, 10);
    }

    public function testCreate()
    {
        $this->assertInstanceOf('Biblys\Service\Pagination', $this->pagination);
    }

    public function testCreateWithInvalidPageNumber()
    {
        // then
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("Page number cannot be less than 0");

        // when
        new Pagination(-1, 25, 10);
    }

    public function testGetCurrent()
    {
        $this->assertEquals(2, $this->pagination->getCurrent());
    }

    public function testGetTotal()
    {
        $this->assertEquals(3, $this->pagination->getTotal());
    }

    public function getOffset(): void
    {
        $this->assertEquals(10, $this->pagination->getOffset());
    }

    public function getLimit(): void
    {
        $this->assertEquals(10, $this->pagination->getLimit());
    }

    public function getPrevious(): void
    {
        $this->assertEquals(0, $this->pagination->getPrevious());
    }

    public function getNext(): void
    {
        $this->assertEquals(2, $this->pagination->getNext());
    }

    /** getPreviousQuery */

    public function testGetPreviousQueryForFirstPage()
    {
        $pagination = new Pagination(1, 25, 10);
        $this->assertEquals("", $pagination->getPreviousQuery());
    }

    public function testGetPreviousQueryForFirstPageWithParams()
    {
        $pagination = new Pagination(1, 25, 10);
        $pagination->setQueryParams(["date" => "2013-05-22"]);
        $this->assertEquals("?date=2013-05-22", $pagination->getPreviousQuery());
    }

    public function testGetPreviousQuery()
    {
        $pagination = new Pagination(2, 25, 10);
        $this->assertEquals("?p=1", $pagination->getPreviousQuery());
    }

    public function testGetPreviousQueryWithParams()
    {
        $pagination = new Pagination(2, 25, 10);
        $pagination->setQueryParams(["date" => "2013-05-22"]);
        $this->assertEquals("?date=2013-05-22&p=1", $pagination->getPreviousQuery());
    }

    /** getNextQuery */

    public function testGetNextQuery()
    {
        $pagination = new Pagination(1, 25, 10);
        $this->assertEquals("?p=2", $pagination->getNextQuery());
    }

    public function testGetNextQueryWithParams()
    {
        $pagination = new Pagination(1, 25, 10);
        $pagination->setQueryParams(["date" => "2013-05-22"]);
        $this->assertEquals("?date=2013-05-22&p=2", $pagination->getNextQuery());
    }

    public function testGetNextQueryForLastPage()
    {
        $pagination = new Pagination(3, 25, 10);
        $this->assertEquals("", $pagination->getNextQuery());
    }

    public function testGetNextQueryForLastPageWithParams()
    {
        $pagination = new Pagination(3, 25, 10);
        $pagination->setQueryParams(["date" => "2013-05-22"]);
        $this->assertEquals("?date=2013-05-22", $pagination->getNextQuery());
    }

    /** getQueryForPageNumber */

    public function testGetQueryForPageNumber()
    {
        // given
        $pagination = new Pagination(1, 25, 10);

        // when
        $queryForPageNumber = $pagination->getQueryForPageNumber(2);

        // then
        $this->assertEquals("?p=1", $queryForPageNumber);
    }

    public function testGetQueryForPageNumberForFirstPage()
    {
        // given
        $pagination = new Pagination(1, 25, 10);

        // when
        $queryForPageNumber = $pagination->getQueryForPageNumber(1);

        // then
        $this->assertEquals("", $queryForPageNumber);
    }

    public function testGetQueryForPageNumberWithParams()
    {
        // given
        $pagination = new Pagination(1, 25, 10);
        $pagination->setQueryParams(["date" => "2013-05-22"]);

        // when
        $queryForPageNumber = $pagination->getQueryForPageNumber(2);

        // then
        $this->assertEquals("?date=2013-05-22&p=1", $queryForPageNumber);
    }
}
