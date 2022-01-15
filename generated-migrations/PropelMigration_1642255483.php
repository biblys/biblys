<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1642255483.
 * Generated on 2022-01-15 15:04:43 by root 
 */
class PropelMigration_1642255483 
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

CREATE INDEX `option_fi_69bd79` ON `option` (`user_id`);

CREATE INDEX `option_fi_db3f76` ON `option` (`site_id`);

CREATE INDEX `rights_fi_861e9e` ON `rights` (`publisher_id`);

ALTER TABLE `shipping`

  CHANGE `site_id` `site_id` INTEGER,

  CHANGE `shipping_min_weight` `shipping_min_weight` INTEGER,

  CHANGE `shipping_fee` `shipping_fee` INTEGER;

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

DROP INDEX `option_fi_69bd79` ON `option`;

DROP INDEX `option_fi_db3f76` ON `option`;

DROP INDEX `rights_fi_861e9e` ON `rights`;

ALTER TABLE `shipping`

  CHANGE `site_id` `site_id` INTEGER NOT NULL,

  CHANGE `shipping_min_weight` `shipping_min_weight` INTEGER NOT NULL,

  CHANGE `shipping_fee` `shipping_fee` INTEGER NOT NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}