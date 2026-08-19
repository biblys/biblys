<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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

use Model\PaymentHash;
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1783278001.
 * Generated on 2026-07-05 19:00:01 by clement */
class PropelMigration_1783278001{
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
        $con = $manager->getAdapterConnection('default');

        $rows = $con
            ->query("SELECT payment_id, payment_amount, payment_created, order_id
                     FROM payments ORDER BY payment_id")
            ->fetchAll(\PDO::FETCH_ASSOC);

        $update = $con->prepare(
            "UPDATE payments SET payment_hash = :hash, payment_hash_version = 1
             WHERE payment_id = :id"
        );

        $previousHash = "";
        foreach ($rows as $row) {
            $createdAt = $row['payment_created'] !== null
                ? new DateTime($row['payment_created'])
                : null;

            $hash = PaymentHash::compute(
                1, // version PINÉE — jamais CURRENT_VERSION dans une migration
                $row['payment_amount'] !== null ? (int) $row['payment_amount'] : null,
                $createdAt,
                $row['order_id'] !== null ? (int) $row['order_id'] : null,
                $previousHash
            );

            $update->execute([':hash' => $hash, ':id' => $row['payment_id']]);
            $previousHash = $hash;
        }
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

ALTER TABLE `payments`

  ADD `payment_hash` VARCHAR(64) AFTER `payment_original_id`,

  ADD `payment_hash_version` TINYINT DEFAULT 1 AFTER `payment_hash`;

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

ALTER TABLE `payments`

  DROP `payment_hash`,

  DROP `payment_hash_version`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
