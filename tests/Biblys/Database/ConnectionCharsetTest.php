<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Propel;

require_once __DIR__ . "/../../setUp.php";

class ConnectionCharsetTest extends TestCase
{
    public static function connectionProvider(): array
    {
        return [
            "legacy connection" => [fn() => Connection::init(Config::load())],
            "lazy connection" => [fn() => Connection::initLazy(Config::load())],
            "Propel connection" => [fn() => Propel::getConnection()],
        ];
    }

    #[DataProvider("connectionProvider")]
    public function testConnectionUsesUtf8mb4(callable $openConnection): void
    {
        // given
        $connection = $openConnection();

        // when
        $variables = $connection->query(
            "SELECT @@character_set_client AS client,
                @@character_set_connection AS connection,
                @@character_set_results AS results,
                @@collation_connection AS collation"
        )->fetch(PDO::FETCH_ASSOC);

        // then
        $this->assertEquals("utf8mb4", $variables["client"]);
        $this->assertEquals("utf8mb4", $variables["connection"]);
        $this->assertEquals("utf8mb4", $variables["results"]);
        $this->assertEquals("utf8mb4_general_ci", $variables["collation"]);
    }
}
