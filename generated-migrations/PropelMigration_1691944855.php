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
 * up to version 1691944855.
 * Generated on 2023-08-13 16:40:55 by clement */
class PropelMigration_1691944855{
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

RENAME TABLE `users` TO `axys_users`;

DROP INDEX `axys_consents_fi_69bd79` ON `axys_consents`;
CREATE INDEX `axys_consents_fi_b4bf15` ON `axys_consents` (`user_id`);

DROP INDEX `users_fi_db3f76` ON `axys_users`;
CREATE INDEX `axys_users_fi_db3f76` ON `axys_users` (`site_id`);

DROP INDEX `carts_fi_69bd79` ON `carts`;
CREATE INDEX `carts_fi_b4bf15` ON `carts` (`user_id`);

DROP INDEX `options_fi_69bd79` ON `options`;
CREATE INDEX `options_fi_b4bf15` ON `options` (`user_id`);

DROP INDEX `rights_fi_69bd79` ON `rights`;
CREATE INDEX `rights_fi_b4bf15` ON `rights` (`user_id`);

DROP INDEX `session_fi_69bd79` ON `session`;
CREATE INDEX `session_fi_b4bf15` ON `session` (`user_id`);

DROP INDEX `stock_fi_69bd79` ON `stock`;
CREATE INDEX `stock_fi_b4bf15` ON `stock` (`user_id`);

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

RENAME TABLE `axys_users` TO `users`;

DROP INDEX `axys_consents_fi_b4bf15` ON `axys_consents`;
CREATE INDEX `axys_consents_fi_69bd79` ON `axys_consents` (`user_id`);

DROP INDEX `axys_users_fi_db3f76` ON `axys_users`;
CREATE INDEX `users_fi_db3f76` ON `axys_users` (`site_id`);

DROP INDEX `carts_fi_b4bf15` ON `carts`;
CREATE INDEX `carts_fi_69bd79` ON `carts` (`user_id`);

DROP INDEX `options_fi_b4bf15` ON `options`;
CREATE INDEX `options_fi_69bd79` ON `options` (`user_id`);

DROP INDEX `rights_fi_b4bf15` ON `rights`;
CREATE INDEX `rights_fi_69bd79` ON `rights` (`user_id`);

DROP INDEX `session_fi_b4bf15` ON `session`;
CREATE INDEX `session_fi_69bd79` ON `session` (`user_id`);

DROP INDEX `stock_fi_b4bf15` ON `stock`;
CREATE INDEX `stock_fi_69bd79` ON `stock` (`user_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
