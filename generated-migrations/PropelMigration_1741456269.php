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
 * up to version 1741456269.
 * Generated on 2025-03-08 17:51:09 by clement */
class PropelMigration_1741456269{
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
            UPDATE `countries` SET `shipping_zone` = 'F' WHERE `country_code` = 'AD';
            UPDATE `countries` SET `shipping_zone` = 'F' WHERE `country_code` = 'MC';
            UPDATE `countries` SET `shipping_zone` = 'A' WHERE `country_code` = 'LI';
            UPDATE `countries` SET `shipping_zone` = 'A' WHERE `country_code` = 'SM';
            UPDATE `countries` SET `shipping_zone` = 'A' WHERE `country_code` = 'BG';
            UPDATE `countries` SET `shipping_zone` = 'A' WHERE `country_code` = 'HR';
            UPDATE `countries` SET `shipping_zone` = 'A' WHERE `country_code` = 'RO';
            UPDATE `countries` SET `shipping_zone` = 'B' WHERE `country_code` = 'AL';
            UPDATE `countries` SET `shipping_zone` = 'B' WHERE `country_code` = 'BY';
            UPDATE `countries` SET `shipping_zone` = 'B' WHERE `country_code` = 'GI';
            UPDATE `countries` SET `shipping_zone` = 'B' WHERE `country_code` = 'MK';
            UPDATE `countries` SET `shipping_zone` = 'B' WHERE `country_code` = 'MD';
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
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
