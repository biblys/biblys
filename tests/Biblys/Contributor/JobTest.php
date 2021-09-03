<?php

namespace Biblys\Contributor;

use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{

    /**
     * @throws UnknownJobException
     */
    public function testGetById()
    {
        // when
        $job = Job::getById(1);

        // then
        $this->assertEquals(
            "Autrice",
            $job->getFeminineName(),
            "should return author job for id 1"
        );
    }

    public function testGetByIdNonExistingJob()
    {
        // then
        $this->expectException("Biblys\Contributor\UnknownJobException");
        $this->expectExceptionMessage("Cannot find a job for id 99999");

        // when
        Job::getById(99999);
    }
}
