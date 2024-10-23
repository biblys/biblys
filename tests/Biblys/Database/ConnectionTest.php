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


namespace Biblys\Database;

use Biblys\Service\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ConnectionTest extends TestCase
{
    public function testInitReturnsError500()
    {
        // then
        $this->expectException(ServiceUnavailableHttpException::class);
        $this->expectExceptionMessage("An error ocurred while connecting to database.");

        // given
        $config = new Config(["db" => []]);


        // when
        Connection::init($config);
    }
}
