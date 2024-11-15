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
 * up to version 1731649828.
 * Generated on 2024-11-15 05:50:28 by clement */
class PropelMigration_1731649828{
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

DROP INDEX `carts_fi_688976` ON `carts`;

CREATE INDEX `files_fi_3fff59` ON `files` (`article_id`);

DROP INDEX `options_fi_688976` ON `options`;

ALTER TABLE `rayons` CHANGE `site_id` `site_id` int unsigned;

DROP INDEX `rights_fi_688976` ON `rights`;

DROP INDEX `session_fi_688976` ON `session`;

CREATE INDEX `special_offers_fi_db3f76` ON `special_offers` (`site_id`);

DROP INDEX `stock_fi_688976` ON `stock`;
 
ALTER TABLE `stock` CHANGE `site_id` `site_id` int unsigned;

CREATE INDEX `subscriptions_fi_db3f76` ON `subscriptions` (`site_id`);

DROP INDEX `wishlist_fi_688976` ON `wishlist`;

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

CREATE INDEX `carts_fi_688976` ON `carts` (`axys_account_id`);

DROP INDEX `files_fi_3fff59` ON `files`;

CREATE INDEX `options_fi_688976` ON `options` (`axys_account_id`);
 
ALTER TABLE `rayons` CHANGE `site_id` `site_id` int unsigned NOT NULL;

CREATE INDEX `rights_fi_688976` ON `rights` (`axys_account_id`);

CREATE INDEX `session_fi_688976` ON `session` (`axys_account_id`);

DROP INDEX `special_offers_fi_db3f76` ON `special_offers`;

ALTER TABLE `stock` CHANGE `site_id` `site_id` int unsigned NOT NULL;

CREATE INDEX `stock_fi_688976` ON `stock` (`axys_account_id`);

DROP INDEX `subscriptions_fi_db3f76` ON `subscriptions`;‡

CREATE INDEX `wishlist_fi_688976` ON `wishlist` (`axys_account_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
