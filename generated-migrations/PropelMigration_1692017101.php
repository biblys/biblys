<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1692017101.
 * Generated on 2023-08-14 12:45:01 by clement */
class PropelMigration_1692017101{
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

DROP INDEX `user_screen_name` ON `axys_accounts`;

DROP INDEX `user_slug` ON `axys_accounts`;

ALTER TABLE `axys_accounts`

  DROP PRIMARY KEY,

  CHANGE `id` `axys_account_id` int(11) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `Email` `axys_account_email` VARCHAR(255),

  CHANGE `user_password` `axys_account_password` VARCHAR(255),

  CHANGE `email_key` `axys_account_email_key` VARCHAR(32),

  CHANGE `user_screen_name` `axys_account_screen_name` VARCHAR(128),

  CHANGE `user_slug` `axys_account_slug` VARCHAR(32),

  CHANGE `DateInscription` `axys_account_signup_date` TIMESTAMP NULL,

  CHANGE `DateConnexion` `axys_account_login_date` TIMESTAMP NULL,

  CHANGE `user_key` `axys_account_key` TEXT,
  
  CHANGE `user_prenom` `axys_account_first_name` TEXT,

  CHANGE `user_nom` `axys_account_last_name` TEXT,

  CHANGE `user_update` `axys_account_update` TIMESTAMP NULL,
  
  CHANGE `user_created` `axys_account_created` TIMESTAMP NULL,
  
  CHANGE `user_updated` `axys_account_updated` TIMESTAMP NULL,

  ADD PRIMARY KEY (`axys_account_id`);

CREATE UNIQUE INDEX `axys_account_screen_name` ON `axys_accounts` (`axys_account_screen_name`);

CREATE UNIQUE INDEX `axys_account_slug` ON `axys_accounts` (`axys_account_slug`);

DROP INDEX `axys_consents_fi_e717d8` ON `axys_consents`;


CREATE INDEX `axys_consents_fi_400426` ON `axys_consents` (`user_id`);

DROP INDEX `carts_fi_c59114` ON `carts`;


CREATE INDEX `carts_fi_688976` ON `carts` (`axys_account_id`);

DROP INDEX `options_fi_c59114` ON `options`;


CREATE INDEX `options_fi_688976` ON `options` (`axys_account_id`);

DROP INDEX `rights_fi_c59114` ON `rights`;


CREATE INDEX `rights_fi_688976` ON `rights` (`axys_account_id`);


DROP INDEX `session_fi_c59114` ON `session`;


CREATE INDEX `session_fi_688976` ON `session` (`axys_account_id`);

DROP INDEX `stock_fi_c59114` ON `stock`;


CREATE INDEX `stock_fi_688976` ON `stock` (`axys_account_id`);

DROP INDEX `wishlist_fi_c59114` ON `wishlist`;


CREATE INDEX `wishlist_fi_688976` ON `wishlist` (`axys_account_id`);

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

DROP INDEX `axys_account_screen_name` ON `axys_accounts`;

DROP INDEX `axys_account_slug` ON `axys_accounts`;

ALTER TABLE `axys_accounts`

  DROP PRIMARY KEY,

  CHANGE `axys_account_id` `id` int(11) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_email` `Email` VARCHAR(255),

  CHANGE `axys_account_password` `user_password` VARCHAR(255),

  CHANGE `axys_account_email_key` `email_key` VARCHAR(32),

  CHANGE `axys_account_screen_name` `user_screen_name` VARCHAR(128),

  CHANGE `axys_account_slug` `user_slug` VARCHAR(32),

  CHANGE `axys_account_signup_date` `DateInscription` TIMESTAMP NULL,

  CHANGE `axys_account_login_date` `DateConnexion` TIMESTAMP NULL,

  CHANGE `axys_account_first_name` `user_key` TEXT,

  CHANGE `axys_account_last_name` `user_nom` TEXT,

  CHANGE `axys_account_update` `user_update` TIMESTAMP NULL,
  
  CHANGE `axys_account_created` `user_created` TIMESTAMP NULL,
  
  CHANGE `axys_account_updated` `user_updated` TIMESTAMP NULL,

  ADD PRIMARY KEY (`id`);

CREATE UNIQUE INDEX `user_screen_name` ON `axys_accounts` (`user_screen_name`);

CREATE UNIQUE INDEX `user_slug` ON `axys_accounts` (`user_slug`);

DROP INDEX `axys_consents_fi_400426` ON `axys_consents`;


CREATE INDEX `axys_consents_fi_e717d8` ON `axys_consents` (`user_id`);

DROP INDEX `carts_fi_688976` ON `carts`;


CREATE INDEX `carts_fi_c59114` ON `carts` (`axys_account_id`);

DROP INDEX `options_fi_688976` ON `options`;


CREATE INDEX `options_fi_c59114` ON `options` (`axys_account_id`);

DROP INDEX `rights_fi_688976` ON `rights`;


CREATE INDEX `rights_fi_c59114` ON `rights` (`axys_account_id`);


DROP INDEX `session_fi_688976` ON `session`;


CREATE INDEX `session_fi_c59114` ON `session` (`axys_account_id`);

DROP INDEX `stock_fi_688976` ON `stock`;


CREATE INDEX `stock_fi_c59114` ON `stock` (`axys_account_id`);

DROP INDEX `wishlist_fi_688976` ON `wishlist`;


CREATE INDEX `wishlist_fi_c59114` ON `wishlist` (`axys_account_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
