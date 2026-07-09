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
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class LazyPdoTest extends TestCase
{
    public function testIsInstanceOfPdo()
    {
        // given
        $connection = Connection::initLazy(Config::load());

        // then
        $this->assertInstanceOf(PDO::class, $connection);
    }

    public function testDoesNotConnectWithInvalidConfigUntilUsed()
    {
        // when a lazy connection is built with an unusable config
        $connection = Connection::initLazy(new Config(["db" => []]));

        // then no connection is attempted, so no exception is thrown yet
        $this->assertInstanceOf(PDO::class, $connection);
    }

    public function testThrowsServiceUnavailableOnFirstUseWhenConnectionFails()
    {
        // given a lazy connection built with an unusable config
        $connection = Connection::initLazy(new Config(["db" => []]));

        // then using it triggers the deferred connection and fails
        $this->expectException(ServiceUnavailableHttpException::class);
        $this->expectExceptionMessage("Une erreur est survenue lors de la connexion à la base de données. Consultez les logs pour plus de détails.");

        // when the connection is actually used
        $connection->query("SELECT 1");
    }

    public function testConnectsAndQueriesLazilyWithValidConfig()
    {
        // given a lazy connection built with the working test config
        $connection = Connection::initLazy(Config::load());

        // when it is used for the first time
        $statement = $connection->query("SELECT 1 AS one");
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // then it connects transparently and returns the result
        $this->assertEquals(1, $result["one"]);
    }

    public function testStoresConnectionInGlobals()
    {
        // when
        $connection = Connection::initLazy(Config::load());

        // then the legacy global points to the lazy connection
        $this->assertSame($connection, $GLOBALS["_SQL"]);
    }
}
