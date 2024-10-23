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

    /**
     * @throws UnknownJobException
     */
    public function testGetByName()
    {
        // when
        $job = Job::getByName("Auteur");

        // then
        $this->assertEquals(
            "Autrice",
            $job->getFeminineName(),
            "should return author job for id 1"
        );
    }

    public function testGetByNameNonExistingJob()
    {
        // then
        $this->expectException("Biblys\Contributor\UnknownJobException");
        $this->expectExceptionMessage("Cannot find a job named Chauffeur");

        // when
        Job::getByName("Chauffeur");
    }
}
