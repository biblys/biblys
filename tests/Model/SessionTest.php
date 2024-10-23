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

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class SessionTest extends TestCase
{
    public function testGenerateToken()
    {
        // when
        $token = Session::generateToken();

        // then
        $this->assertEquals(
            32,
            strlen($token),
            "it should return a 32 chars string"
        );
        $this->assertMatchesRegularExpression(
            '/^[a-zA-Z\d]{32}$/',
            $token,
            "it should only contain alphanumeric characters"
        );
    }
}
