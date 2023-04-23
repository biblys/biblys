<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1682267685.
 * Generated on 2023-04-23 16:34:45 by clement */
class PropelMigration_1682267685{
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

DROP TABLE IF EXISTS `ticket`;

DROP TABLE IF EXISTS `ticket_comment`;


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


CREATE TABLE `ticket`
(
    `ticket_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `site_id` INTEGER,
    `ticket_type` VARCHAR(16) DEFAULT '',
    `ticket_title` VARCHAR(255),
    `ticket_content` TEXT,
    `ticket_priority` INTEGER DEFAULT 0,
    `ticket_created` TIMESTAMP NULL,
    `ticket_updated` TIMESTAMP NULL,
    `ticket_resolved` TIMESTAMP NULL,
    `ticket_closed` TIMESTAMP NULL,
    PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB;

CREATE TABLE `ticket_comment`
(
    `ticket_comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id` INTEGER,
    `user_id` INTEGER,
    `ticket_comment_content` TEXT,
    `ticket_comment_created` TIMESTAMP NULL,
    `ticket_comment_update` TIMESTAMP NULL,
    PRIMARY KEY (`ticket_comment_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
