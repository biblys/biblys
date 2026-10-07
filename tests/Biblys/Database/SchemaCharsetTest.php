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
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../../setUp.php";

class SchemaCharsetTest extends TestCase
{
    private const COLLATION = "utf8mb4_general_ci";

    public function testAllTablesAndColumnsUseUtf8mb4(): void
    {
        // given
        $connection = Connection::init(Config::load());

        // when
        $misconfigured = $connection->query(
            "SELECT table_name FROM information_schema.tables
            WHERE table_schema = DATABASE()
            AND table_type = 'BASE TABLE'
            AND table_collation <> '" . self::COLLATION . "'
            UNION
            SELECT CONCAT(table_name, '.', column_name) FROM information_schema.columns
            WHERE table_schema = DATABASE()
            AND collation_name IS NOT NULL
            AND collation_name <> '" . self::COLLATION . "'"
        )->fetchAll(PDO::FETCH_COLUMN);

        // then
        $this->assertEquals([], $misconfigured, "Not using " . self::COLLATION);
    }
}
