<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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
 * up to version 1740317578.
 * Generated on 2025-02-23 13:32:58 by clement */
class PropelMigration_1740317578{
    /**
     * @var string
     */
    public $comment = '';

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
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
    public function getUpSQL(): array
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `libraries`;

ALTER TABLE `events`

  DROP `library_id`;

ALTER TABLE `rights`

  DROP `library_id`;


ALTER TABLE `subscriptions`

  DROP `library_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL(): array
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `events`

  ADD `library_id` int unsigned AFTER `publisher_id`;

ALTER TABLE `rights`

  ADD `library_id` int unsigned AFTER `publisher_id`;


ALTER TABLE `subscriptions`

  ADD `library_id` int unsigned AFTER `publisher_id`;

CREATE TABLE `libraries`
(
    `library_id` int unsigned NOT NULL AUTO_INCREMENT,
    `library_name` VARCHAR(255),
    `library_name_alphabetic` VARCHAR(256),
    `library_url` VARCHAR(128),
    `library_representative` VARCHAR(256),
    `library_address` VARCHAR(256),
    `library_postal_code` VARCHAR(8),
    `library_city` VARCHAR(128),
    `library_country` VARCHAR(128),
    `library_phone` VARCHAR(16),
    `library_fax` VARCHAR(16),
    `library_website` VARCHAR(128),
    `library_email` VARCHAR(128),
    `library_facebook` VARCHAR(128),
    `library_twitter` VARCHAR(15),
    `library_creation_year` VARCHAR(4),
    `library_specialities` VARCHAR(256),
    `library_readings` VARCHAR(512),
    `library_desc` TEXT,
    `library_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `library_updated` TIMESTAMP NULL,
    PRIMARY KEY (`library_id`),
    UNIQUE INDEX `publisher_url` (`library_url`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
