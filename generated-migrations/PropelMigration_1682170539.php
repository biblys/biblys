<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1682170539.
 * Generated on 2023-04-22 13:35:39 by clement */
class PropelMigration_1682170539{
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

ALTER TABLE `mailing`

  CHANGE `site_id` `site_id` tinyint(3) unsigned,

  CHANGE `mailing_email` `mailing_email` VARCHAR(256) DEFAULT '',

  CHANGE `mailing_block` `mailing_block` TINYINT(1),

  CHANGE `mailing_checked` `mailing_checked` TINYINT(1);


ALTER TABLE `stock`

  CHANGE `stock_depot` `stock_depot` TINYINT(1) DEFAULT 0,

  CHANGE `stock_allow_predownload` `stock_allow_predownload` TINYINT(1) DEFAULT 0,

  CHANGE `stock_media_ok` `stock_media_ok` TINYINT(1) DEFAULT 0,

  CHANGE `stock_file_updated` `stock_file_updated` TINYINT(1) DEFAULT 0,

  CHANGE `stock_dl` `stock_dl` TINYINT(1) DEFAULT 0;

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

ALTER TABLE `mailing`

  CHANGE `site_id` `site_id` tinyint(3) unsigned NOT NULL,

  CHANGE `mailing_email` `mailing_email` VARCHAR(256) DEFAULT '' NOT NULL,

  CHANGE `mailing_block` `mailing_block` TINYINT(1) NOT NULL,

  CHANGE `mailing_checked` `mailing_checked` TINYINT(1) NOT NULL;


ALTER TABLE `stock`

  CHANGE `stock_depot` `stock_depot` TINYINT(1) DEFAULT 0 NOT NULL,

  CHANGE `stock_allow_predownload` `stock_allow_predownload` TINYINT(1) DEFAULT 0 NOT NULL,

  CHANGE `stock_media_ok` `stock_media_ok` TINYINT(1) DEFAULT 0 NOT NULL,

  CHANGE `stock_file_updated` `stock_file_updated` TINYINT(1) DEFAULT 0 NOT NULL,

  CHANGE `stock_dl` `stock_dl` TINYINT(1) DEFAULT 0 NOT NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
