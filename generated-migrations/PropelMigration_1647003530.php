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

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1647003530.
 * Generated on 2022-03-11 12:58:50 by clement 
 */
class PropelMigration_1647003530 
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

START TRANSACTION;

UPDATE `orders` SET `order_insert` = NULL 
    WHERE CAST(`order_insert` AS CHAR(20)) = '0000-00-00 00:00:00';
UPDATE `orders` SET `order_payment_date` = NULL 
    WHERE CAST(`order_payment_date` AS CHAR(20)) = '0000-00-00 00:00:00';
UPDATE `orders` SET `order_shipping_date` = NULL 
    WHERE CAST(`order_shipping_date` AS CHAR(20)) = '0000-00-00 00:00:00';

ALTER TABLE `orders`
  CHANGE `order_as-a-gift` `order_as_a_gift` VARCHAR(16),
  CHANGE `order_gift-recipient` `order_gift_recipient` int(10) unsigned;

COMMIT;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

START TRANSACTION;

ALTER TABLE `orders`

  CHANGE `order_as_a_gift` `order_as-a-gift` VARCHAR(16),

  CHANGE `order_gift_recipient` `order_gift-recipient` int(10) unsigned;

COMMIT;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}