<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1643992103.
 * Generated on 2022-02-04 16:28:23 by clement 
 */
class PropelMigration_1643992103 
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

DROP INDEX `option_fi_69bd79` ON `options`;

DROP INDEX `option_fi_db3f76` ON `options`;

CREATE INDEX `options_fi_69bd79` ON `options` (`user_id`);

CREATE INDEX `options_fi_db3f76` ON `options` (`site_id`);

ALTER TABLE `rayons`

  CHANGE `rayon_url` `rayon_url` VARCHAR(256);

ALTER TABLE `sites`

  DROP `site_alerts`;

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

DROP INDEX `options_fi_69bd79` ON `options`;

DROP INDEX `options_fi_db3f76` ON `options`;

CREATE INDEX `option_fi_69bd79` ON `options` (`user_id`);

CREATE INDEX `option_fi_db3f76` ON `options` (`site_id`);

ALTER TABLE `rayons`

  CHANGE `rayon_url` `rayon_url` TEXT;

ALTER TABLE `sites`

  ADD `site_alerts` TINYINT(1) AFTER `site_shipping_fee`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}