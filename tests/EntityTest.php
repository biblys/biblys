<?php
/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
require_once 'inc/functions.php';

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
}
