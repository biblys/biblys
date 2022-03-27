<?php
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

    public function getOffset()
    {
        $this->assertEquals(10, $this->pagination->getOffset());
    }

    public function getLimit()
    {
        $this->assertEquals(10, $this->pagination->getLimit());
    }

    public function getPrevious()
    {
        $this->assertEquals(0, $this->pagination->getPrevious());
    }

    public function getNext()
    {
        $this->assertEquals(2, $this->pagination->getNext());
    }

}
