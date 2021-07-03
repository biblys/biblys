<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Service\Pagination;

require_once __DIR__ . "/../../setUp.php";

class PaginationTest extends PHPUnit\Framework\TestCase
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
        $this->assertEquals($this->pagination->getCurrent(), 2);
    }

    public function testGetTotal()
    {
        $this->assertEquals($this->pagination->getTotal(), 3);
    }

    public function getOffset()
    {
        $this->assertEquals($this->pagination->getOffset(), 10);
    }

    public function getLimit()
    {
        $this->assertEquals($this->pagination->getLimit(), 10);
    }

    public function getPrevious()
    {
        $this->assertEquals($this->pagination->getPrevious(), 0);
    }

    public function getNext()
    {
        $this->assertEquals($this->pagination->getNext(), 2);
    }

}
