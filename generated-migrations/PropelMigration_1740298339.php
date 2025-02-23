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
 * up to version 1740298339.
 * Generated on 2025-02-23 08:12:19 by clement */
class PropelMigration_1740298339{
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

DROP TABLE IF EXISTS `axys_sessions`;

DROP TABLE IF EXISTS `bookshops`;

ALTER TABLE `events`

  CHANGE `event_date` `event_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  DROP `bookshop_id`;

ALTER TABLE `rights`

  DROP `bookshop_id`;


ALTER TABLE `sites`

  DROP `site_bookshop`,

  DROP `site_bookshop_id`;

ALTER TABLE `subscriptions`

  DROP `bookshop_id`;

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

  CHANGE `event_date` `event_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  ADD `bookshop_id` int unsigned AFTER `publisher_id`;

ALTER TABLE `rights`

  ADD `bookshop_id` int unsigned AFTER `publisher_id`;


ALTER TABLE `sites`

  ADD `site_bookshop` TINYINT(1) DEFAULT 0 AFTER `site_payment_transfer`,

  ADD `site_bookshop_id` int unsigned AFTER `site_bookshop`;

ALTER TABLE `subscriptions`

  ADD `bookshop_id` int unsigned AFTER `publisher_id`;

CREATE TABLE `axys_sessions`
(
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `axys_account_id` int unsigned,
    `token` VARCHAR(32),
    `created_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `bookshops`
(
    `bookshop_id` int unsigned NOT NULL AUTO_INCREMENT,
    `bookshop_name` VARCHAR(255),
    `bookshop_name_alphabetic` VARCHAR(256),
    `bookshop_url` VARCHAR(128),
    `bookshop_representative` VARCHAR(256),
    `bookshop_address` VARCHAR(256),
    `bookshop_postal_code` VARCHAR(8),
    `bookshop_city` VARCHAR(128),
    `bookshop_country` VARCHAR(128),
    `bookshop_phone` VARCHAR(16),
    `bookshop_fax` VARCHAR(16),
    `bookshop_website` VARCHAR(128),
    `bookshop_email` VARCHAR(128),
    `bookshop_facebook` VARCHAR(32),
    `bookshop_twitter` VARCHAR(15),
    `bookshop_legal_form` VARCHAR(128),
    `bookshop_creation_year` VARCHAR(4),
    `bookshop_specialities` VARCHAR(256),
    `bookshop_membership` VARCHAR(512),
    `bookshop_motto` VARCHAR(128),
    `bookshop_desc` TEXT,
    `bookshop_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `bookshop_updated` TIMESTAMP NULL,
    PRIMARY KEY (`bookshop_id`),
    UNIQUE INDEX `publisher_url` (`bookshop_url`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
