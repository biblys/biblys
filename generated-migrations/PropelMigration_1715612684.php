<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1715612684.
 * Generated on 2024-05-13 15:04:44 by clement */
class PropelMigration_1715612684{
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

DROP TABLE IF EXISTS `axys_accounts`;

DROP TABLE IF EXISTS `axys_apps`;

DROP TABLE IF EXISTS `axys_consents`;


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


CREATE TABLE `axys_accounts`
(
    `axys_account_id` int unsigned NOT NULL AUTO_INCREMENT,
    `axys_account_email` VARCHAR(255),
    `axys_account_password` VARCHAR(255),
    `axys_account_key` TEXT,
    `axys_account_email_key` VARCHAR(32),
    `axys_account_screen_name` VARCHAR(128),
    `axys_account_slug` VARCHAR(32),
    `axys_account_signup_date` TIMESTAMP NULL,
    `axys_account_login_date` TIMESTAMP NULL,
    `axys_account_last_name` TEXT,
    `axys_account_first_name` TEXT,
    `axys_account_update` TIMESTAMP NULL,
    `email_verified_at` TIMESTAMP NULL,
    `marked_for_email_verification_at` TIMESTAMP NULL,
    `warned_before_deletion_at` TIMESTAMP NULL,
    `axys_account_created` TIMESTAMP NULL,
    `axys_account_updated` TIMESTAMP NULL,
    PRIMARY KEY (`axys_account_id`),
    UNIQUE INDEX `axys_account_screen_name` (`axys_account_screen_name`),
    UNIQUE INDEX `axys_account_slug` (`axys_account_slug`)
) ENGINE=InnoDB;

CREATE TABLE `axys_apps`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `client_id` VARCHAR(32) NOT NULL,
    `client_secret` VARCHAR(64) NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    `redirect_uri` VARCHAR(256) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `axys_consents`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `app_id` INTEGER NOT NULL,
    `axys_account_id` INTEGER NOT NULL,
    `scopes` VARCHAR(256) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `axys_consents_fi_711fdd` (`app_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
