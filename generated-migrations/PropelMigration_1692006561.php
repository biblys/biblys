<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1692006561.
 * Generated on 2023-08-14 09:49:21 by clement */
class PropelMigration_1692006561{
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

RENAME TABLE `axys_users` TO `axys_accounts`;

DROP INDEX `axys_consents_fi_b4bf15` ON `axys_consents`;
CREATE INDEX `axys_consents_fi_e717d8` ON `axys_consents` (`user_id`);

DROP INDEX `carts_fi_b89a7c` ON `carts`;
CREATE INDEX `carts_fi_17bd41` ON `carts` (`axys_user_id`);

DROP INDEX `options_fi_b89a7c` ON `options`;
CREATE INDEX `options_fi_17bd41` ON `options` (`axys_user_id`);

DROP INDEX `rights_fi_b89a7c` ON `rights`;
CREATE INDEX `rights_fi_17bd41` ON `rights` (`axys_user_id`);

DROP INDEX `session_fi_b89a7c` ON `session`;
CREATE INDEX `session_fi_17bd41` ON `session` (`axys_user_id`);

DROP INDEX `stock_fi_b89a7c` ON `stock`;
CREATE INDEX `stock_fi_17bd41` ON `stock` (`axys_user_id`);

DROP INDEX `wishlist_fi_b89a7c` ON `wishlist`;
CREATE INDEX `wishlist_fi_17bd41` ON `wishlist` (`axys_user_id`);


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

RENAME TABLE `axys_accounts` TO `axys_users`;

DROP INDEX `axys_consents_fi_e717d8` ON `axys_consents`;
CREATE INDEX `axys_consents_fi_b4bf15` ON `axys_consents` (`user_id`);

DROP INDEX `carts_fi_17bd41` ON `carts`;
CREATE INDEX `carts_fi_b89a7c` ON `carts` (`axys_user_id`);

DROP INDEX `options_fi_17bd41` ON `options`;
CREATE INDEX `options_fi_b89a7c` ON `options` (`axys_user_id`);

DROP INDEX `rights_fi_17bd41` ON `rights`;
CREATE INDEX `rights_fi_b89a7c` ON `rights` (`axys_user_id`);

DROP INDEX `session_fi_17bd41` ON `session`;
CREATE INDEX `session_fi_b89a7c` ON `session` (`axys_user_id`);

DROP INDEX `stock_fi_17bd41` ON `stock`;
CREATE INDEX `stock_fi_b89a7c` ON `stock` (`axys_user_id`);

DROP INDEX `wishlist_fi_17bd41` ON `wishlist`;
CREATE INDEX `wishlist_fi_b89a7c` ON `wishlist` (`axys_user_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
