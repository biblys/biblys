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
 * up to version 1788942469.
 * Generated on 2026-09-09 by clement
 */
class PropelMigration_1788942469
{
    public $comment = 'Make target_collection_id and target_quantity nullable and add target_amount, '
        . 'so a special offer can require a minimum cart amount instead of (or in addition to) '
        . 'a minimum quantity in a collection.';

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

ALTER TABLE `special_offers`

  CHANGE `target_collection_id` `target_collection_id` INTEGER,

  CHANGE `target_quantity` `target_quantity` INTEGER,

  ADD `target_amount` int unsigned AFTER `target_quantity`;
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

ALTER TABLE `special_offers`

  CHANGE `target_collection_id` `target_collection_id` INTEGER NOT NULL,

  CHANGE `target_quantity` `target_quantity` INTEGER NOT NULL,

  DROP `target_amount`;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}
