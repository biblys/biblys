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

use PHPUnit\Framework\Error\Deprecated;

require_once __DIR__."/setUp.php";

class EntityTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a copy.
     */
    public function testBuildSqlQuery()
    {
        $query = EntityManager::buildSqlQuery(
            [
                'key' => 'value',
                'key_null' => 'NULL',
                'key_not_null' => 'NOT NULL',
            ]
        );

        $this->assertEquals(
            [
                'where' => '`key` = :field0 AND `key_null` IS NULL '.
                    'AND `key_not_null` IS NOT NULL',
                'having' => null,
                'params' => [
                    'field0' => 'value',
                ],
            ],
            $query
        );
    }

    public function testCreateEntityWithInvalidProperty()
    {
        // given
        $om = new OrderManager();

        // when
        $order = $om->create(["cgv_checkbox" => 1]);

        // then
        $this->assertTrue(
            !$order->has("cgv_checkbox"),
            "it should create the entity without the invalid property"
        );
    }

    public function testUpdateEntityWithInvalidProperty()
    {
        // given
        $om = new OrderManager();

        // when
        $order = $om->create([]);
        $order->set("newsletter", 1);
        $order = $om->update($order);

        // then
        $this->assertTrue(
            !$order->has("newsletter"),
            "it should create the entity without the invalid property"
        );
    }

    public function testGetQueryWithInvalidOffsetOption()
    {
        // then
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("Offset options cannot be less than 0.");

        // given
        $em = new EntityManager();

        // when
        $em->getQuery("", [], ["offset" => -1]);
    }
}
