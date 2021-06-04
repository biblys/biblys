<?php
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
        $this->expectException("Exception");
        $this->expectExceptionMessage(
            "Trying to create Order with invalid property cgv_checkbox"
        );

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
        $this->expectException("Exception");
        $this->expectExceptionMessage(
            "Trying to update Order with invalid property newsletter"
        );

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
}
