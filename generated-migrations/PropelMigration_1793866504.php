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
 * up to version 1793866504.
 */
class PropelMigration_1793866504
{
    public $comment = 'Merge cycles sharing the same URL, then add a unique index on cycle_url '
        . 'so duplicates cannot be created again.';

    /**
     * Written in plain SQL on purpose: a migration must keep working when
     * models or services change later.
     */
    public function preUp(MigrationManager $manager)
    {
        $connection = $manager->getAdapterConnection('default');

        // Empty URLs would collide on the unique index: store them as NULL
        $connection->exec("UPDATE `cycles` SET `cycle_url` = NULL WHERE `cycle_url` = ''");

        // Grouping uses the column collation, like the unique index will
        $groups = $connection->query(
            "SELECT `cycle_url`, GROUP_CONCAT(`cycle_id` ORDER BY `cycle_id`) AS ids
            FROM `cycles` WHERE `cycle_url` IS NOT NULL
            GROUP BY `cycle_url` HAVING COUNT(*) > 1"
        )->fetchAll(PDO::FETCH_ASSOC);

        $select = $connection->prepare(
            "SELECT `cycle_id`, `cycle_desc`, `cycle_noosfere_id` FROM `cycles` WHERE `cycle_id` = ?"
        );
        $moveArticles = $connection->prepare("UPDATE `articles` SET `cycle_id` = ? WHERE `cycle_id` = ?");
        $deleteCycle = $connection->prepare("DELETE FROM `cycles` WHERE `cycle_id` = ?");
        $updateCycle = $connection->prepare(
            "UPDATE `cycles` SET `cycle_desc` = ?, `cycle_noosfere_id` = ? WHERE `cycle_id` = ?"
        );

        $rows = [];
        foreach ($groups as $group) {
            $cycles = [];
            foreach (explode(",", $group["ids"]) as $id) {
                $select->execute([$id]);
                $cycles[] = $select->fetch(PDO::FETCH_ASSOC);
            }

            // Two cycles with different Noosfere ids cannot be told to be the same series
            $noosfereIds = array_unique(array_filter(array_column($cycles, "cycle_noosfere_id")));
            if (count($noosfereIds) > 1) {
                throw new RuntimeException(
                    "Cannot merge cycles with URL \"{$group["cycle_url"]}\" (ids {$group["ids"]}): "
                    . "different Noosfere ids. Fix them manually before migrating."
                );
            }

            $rows[] = $cycles;
        }

        $connection->beginTransaction();
        try {
            foreach ($rows as $cycles) {
                $kept = array_shift($cycles);
                $desc = $kept["cycle_desc"];
                $noosfereId = $kept["cycle_noosfere_id"];

                foreach ($cycles as $duplicate) {
                    $moveArticles->execute([$kept["cycle_id"], $duplicate["cycle_id"]]);
                    $deleteCycle->execute([$duplicate["cycle_id"]]);
                    $desc = $desc ?: $duplicate["cycle_desc"];
                    $noosfereId = $noosfereId ?: $duplicate["cycle_noosfere_id"];
                }

                $updateCycle->execute([$desc, $noosfereId, $kept["cycle_id"]]);
            }
            $connection->commit();
        } catch (Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }
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
        // merged cycles cannot be restored
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

CREATE UNIQUE INDEX `cycle_url` ON `cycles` (`cycle_url`(191));
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

DROP INDEX `cycle_url` ON `cycles`;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}
